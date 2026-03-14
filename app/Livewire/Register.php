<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Club\Services\ClubService;
use Modules\Reference\Models\RefClubType;
use Modules\Reference\Models\RefSportType;
use Modules\Team\Models\InviteLink;
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
 *
 * Если приглашение (invite token):
 *   → Только шаг 1 → автоматическая привязка к команде → dashboard
 */
#[Layout('layouts.custom-app')]
class Register extends Component
{
    use WithFileUploads;

    // ── Invite mode ─────────────────────────────────────────────────
    #[Url(as: 'invite')]
    public ?string $inviteToken = null;
    public bool $hasInvite = false;
    public ?InviteLink $invite = null;
    public ?string $inviteTeamName = null;
    public ?string $inviteClubName = null;
    public ?string $inviteRoleName = null;

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
        'teamLogo.image'      => 'Файл должен быть изображением',
        'teamLogo.max'        => 'Размер файла не должен превышать 2 МБ',
    ];

    // ── Computed properties ─────────────────────────────────────────

    #[Computed]
    public function totalSteps(): int
    {
        // При приглашении только 1 шаг
        if ($this->hasInvite) {
            return 1;
        }
        
        if ($this->selectedRole === 'admin') {
            return 4;
        }
        return 2; // account + role → register
    }

    // ── Lifecycle ───────────────────────────────────────────────────

    public function mount()
    {
        // Проверяем токен в URL или сессии
        $token = $this->inviteToken ?? session('invite_token');
        
        if ($token) {
            $this->loadInvite($token);
        }
    }

    private function loadInvite(string $token): void
    {
        $invite = InviteLink::where('token', $token)->with('team.club')->first();

        if (!$invite) {
            return;
        }

        if ($invite->isExpired() || $invite->isLimitReached()) {
            return;
        }

        $this->hasInvite = true;
        $this->invite = $invite;
        $this->inviteToken = $token;
        
        $this->inviteTeamName = $invite->team->name ?? 'Неизвестная команда';
        $this->inviteClubName = $invite->team->club->name ?? '';
        $this->inviteRoleName = match ($invite->role) {
            'coach'  => 'Тренер',
            'parent' => 'Родитель',
            default  => 'Игрок',
        };

        // Сохраняем в сессию на случай перезагрузки
        session(['invite_token' => $token]);
    }

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

        // Если есть приглашение - сразу регистрируем и привязываем
        if ($this->hasInvite && $this->invite) {
            return $this->registerWithInvite();
        }

        $this->step = 2;
    }

    // ── Registration with invite ────────────────────────────────────

    private function registerWithInvite()
    {
        $invite = $this->invite;

        $user = DB::transaction(function () use ($invite) {
            // Определяем роль из приглашения
            $globalRole = match ($invite->role) {
                'coach'  => 'coach',
                'parent' => 'parent',
                default  => 'player',
            };

            // Для тренеров не пропускаем онбординг - нужно заполнить профиль
            $shouldSkipOnboarding = in_array($invite->role, ['player', 'parent']);

            // Создаём пользователя
            $user = User::create([
                'first_name'    => $this->firstName,
                'last_name'     => $this->lastName,
                'email'         => $this->email,
                'password_hash' => Hash::make($this->password),
                'birth_date'    => '2000-01-01', // placeholder
                'gender'        => 'male',       // placeholder
                'global_role'   => $globalRole,
                'onboarded_at'  => $shouldSkipOnboarding ? now() : null, // Только игроки/родители пропускают онбординг
            ]);

            // Map role to role_id
            $roleId = match ($invite->role) {
                'coach'  => 2, // Тренер
                'parent' => 9, // Родитель
                default  => 6, // Игрок
            };

            // Привязываем к команде
            TeamMember::create([
                'user_id'   => $user->id,
                'club_id'   => $invite->team->club_id,
                'team_id'   => $invite->team_id,
                'role_id'   => $roleId,
                'joined_at' => now(),
                'is_active' => true,
            ]);

            // Для тренеров создаём пустой профиль
            if ($invite->role === 'coach') {
                \Modules\User\Models\CoachProfile::create([
                    'user_id' => $user->id,
                    'sport_type_id' => $invite->team->sport_type_id,
                ]);
            }

            // Увеличиваем счётчик использований
            $invite->incrementUsage();

            return $user;
        });

        // Авторизуем
        Auth::login($user);

        // Очищаем сессию
        session()->forget('invite_token');

        // Для тренеров перенаправляем на онбординг для заполнения профиля
        if ($invite->role === 'coach') {
            session()->flash('success', 'Добро пожаловать! Заполните профиль тренера.');
            return redirect()->route('onboarding');
        }

        session()->flash('success', 'Добро пожаловать в команду «' . $this->inviteTeamName . '»!');
        return redirect()->route('home');
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
