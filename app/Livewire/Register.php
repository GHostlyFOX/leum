<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Club\Services\ClubService;
use Modules\Reference\Models\RefClubType;
use Modules\Reference\Models\RefSportType;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamMember;
use Modules\User\Models\User;

/**
 * Мультишаговый визард регистрации.
 *
 * Шаг 1: Создание аккаунта (имя, фамилия, email, пароль)
 * Шаг 2: Выбор роли (admin / coach / parent-player)
 *
 * Если admin:
 *   Шаг 3: Создание клуба/лиги (название, вид спорта, описание)
 *   Шаг 4: Создание команды (название, цвет, логотип)
 *   → Регистрация + вход + redirect → dashboard
 *
 * Если другая роль:
 *   → Регистрация + вход + redirect → onboarding (EnsureOnboarded middleware)
 */
#[Layout('layouts.custom-app')]
class Register extends Component
{
    use WithFileUploads;

    // ── Wizard state ────────────────────────────────────────────────
    public int $step = 1;

    // ── Step 1: Account ─────────────────────────────────────────────
    public string $firstName = '';
    public string $lastName = '';
    public string $email = '';
    public string $password = '';

    // ── Step 2: Role selection ──────────────────────────────────────
    public string $selectedRole = ''; // admin, coach, parent

    // ── Step 3 (admin): Club/League ─────────────────────────────────
    public string $clubName = '';
    public ?int $clubTypeId = null;
    public ?int $sportTypeId = null;
    public string $clubDescription = '';

    // ── Step 4 (admin): Team ────────────────────────────────────────
    public string $teamName = '';
    public string $teamColor = '#3B82F6'; // default blue
    public $teamLogo;

    // Предустановленные цвета для выбора
    public array $presetColors = [
        '#EF4444', // red
        '#F97316', // orange
        '#EAB308', // yellow
        '#22C55E', // green
        '#3B82F6', // blue
        '#8B5CF6', // purple
        '#EC4899', // pink
        '#6B7280', // gray
    ];

    // ── Validation messages ─────────────────────────────────────────
    protected array $messages = [
        'firstName.required'  => 'Введите имя',
        'lastName.required'   => 'Введите фамилию',
        'email.required'      => 'Введите email',
        'email.email'         => 'Введите корректный email',
        'email.unique'        => 'Этот email уже зарегистрирован',
        'password.required'   => 'Введите пароль',
        'password.min'        => 'Пароль должен содержать минимум 8 символов',
        'clubName.required'   => 'Введите название клуба или лиги',
        'clubTypeId.required' => 'Выберите тип клуба',
        'sportTypeId.required' => 'Выберите вид спорта',
    ];

    // ── Computed properties ─────────────────────────────────────────

    #[Computed]
    public function totalSteps(): int
    {
        if ($this->selectedRole === 'admin') {
            return 4;
        }
        return 2; // account + role → register
    }

    // ── Lifecycle ───────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.register', [
            'clubTypes'  => RefClubType::orderBy('name')->get(),
            'sportTypes' => RefSportType::orderBy('name')->get(),
        ]);
    }

    // ── Step 1 → Step 2: Validate account data ─────────────────────

    public function nextToRole()
    {
        $this->validate([
            'firstName' => 'required|string|max:100',
            'lastName'  => 'required|string|max:100',
            'email'     => 'required|email|max:255|unique:users,email',
            'password'  => 'required|string|min:8',
        ]);

        $this->step = 2;
    }

    // ── Step 2: Select role ─────────────────────────────────────────

    public function selectRole(string $role)
    {
        if (! in_array($role, ['admin', 'coach', 'parent'])) {
            return;
        }

        $this->selectedRole = $role;
    }

    /**
     * Step 2 → Step 3 (admin) или финальная регистрация (coach/parent).
     */
    public function submitRole()
    {
        if (empty($this->selectedRole)) {
            $this->addError('selectedRole', 'Выберите роль');
            return;
        }

        if ($this->selectedRole === 'admin') {
            $this->step = 3;
        } else {
            // Для coach / parent — регистрируем и отправляем на онбординг
            return $this->registerAndRedirect();
        }
    }

    // ── Step 3 (admin): Save club info → Step 4 ────────────────────

    public function nextToTeam()
    {
        $this->validate([
            'clubName'    => 'required|string|max:255',
            'clubTypeId'  => 'required|exists:ref_club_types,id',
            'sportTypeId' => 'required|exists:ref_sport_types,id',
            'clubDescription' => 'nullable|string|max:1000',
        ]);

        $this->step = 4;
    }

    // ── Step 4 (admin): Create account + club + team ───────────────

    public function selectColor(string $color)
    {
        $this->teamColor = $color;
    }

    public function finishAdminRegistration()
    {
        $this->validate([
            'teamName' => 'nullable|string|max:255',
            'teamLogo' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () {
            // 1. Create user
            $user = User::create([
                'first_name'    => $this->firstName,
                'last_name'     => $this->lastName,
                'email'         => $this->email,
                'password_hash' => Hash::make($this->password),
                'birth_date'    => '2000-01-01', // placeholder, можно обновить позже
                'gender'        => 'male',       // placeholder
                'global_role'   => 'admin',
                'onboarded_at'  => now(),         // admin проходит полный визард при регистрации
            ]);

            // 2. Create club
            /** @var ClubService $clubService */
            $clubService = app(ClubService::class);
            $club = $clubService->create([
                'name'          => $this->clubName,
                'description'   => $this->clubDescription ?: null,
                'club_type_id'  => $this->clubTypeId,
                'sport_type_id' => $this->sportTypeId,
            ]);

            // 3. Create team (if name provided or use club name)
            $teamData = [
                'name'          => $this->teamName ?: $this->clubName,
                'club_id'       => $club->id,
                'sport_type_id' => $this->sportTypeId,
                'team_color'    => $this->teamColor,
            ];

            // Upload team logo if provided
            if ($this->teamLogo) {
                $logoFile = app(\Modules\File\Services\FileService::class)
                    ->uploadPublic($this->teamLogo, 'teams');
                $teamData['logo_file_id'] = $logoFile->id;
            }

            $team = Team::create($teamData);

            // 4. Add admin as team member
            TeamMember::create([
                'user_id'   => $user->id,
                'club_id'   => $club->id,
                'team_id'   => $team->id,
                'role_id'   => 7, // Администратор клуба
                'joined_at' => now(),
                'is_active' => true,
            ]);

            // 5. Login
            Auth::login($user);
        });

        session()->flash('success', 'Добро пожаловать в Сбор! Клуб «' . $this->clubName . '» создан.');
        return redirect()->route('home');
    }

    // ── Non-admin registration ──────────────────────────────────────

    private function registerAndRedirect()
    {
        DB::transaction(function () {
            $globalRole = match ($this->selectedRole) {
                'coach'  => 'coach',
                'parent' => 'parent',
                default  => 'player',
            };

            $user = User::create([
                'first_name'    => $this->firstName,
                'last_name'     => $this->lastName,
                'email'         => $this->email,
                'password_hash' => Hash::make($this->password),
                'birth_date'    => '2000-01-01', // placeholder
                'gender'        => 'male',       // placeholder
                'global_role'   => $globalRole,
                // onboarded_at = null → EnsureOnboarded middleware перенаправит на /onboarding
            ]);

            Auth::login($user);
        });

        session()->flash('success', 'Аккаунт создан! Давайте настроим ваш профиль.');
        return redirect()->route('onboarding');
    }

    // ── Navigation ──────────────────────────────────────────────────

    public function prevStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }
}
