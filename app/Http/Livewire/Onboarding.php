<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Club\Services\ClubService;
use Modules\Reference\Models\City;
use Modules\Reference\Models\Country;
use Modules\Reference\Models\RefClubType;
use Modules\Reference\Models\RefDominantFoot;
use Modules\Reference\Models\RefPosition;
use Modules\Reference\Models\RefSportType;
use Modules\Reference\Models\RefUserRole;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamMember;
use Modules\User\Models\CoachProfile;
use Modules\User\Models\PlayerProfile;
use Modules\User\Models\User;

class Onboarding extends Component
{
    use WithFileUploads;

    // ── Role selection (step 1) ─────────────────────────────────
    public string $selectedRole = ''; // admin, coach, parent, player

    // ── Wizard state ────────────────────────────────────────────
    public int $step = 1; // 1 = role selection, then role-specific steps
    public ?int $clubId = null;

    // ── Admin flow: Step 2 — Club ───────────────────────────────
    public string $clubName = '';
    public string $clubDescription = '';
    public ?int $sportTypeId = null;
    public ?int $clubTypeId = null;
    public ?int $countryId = null;
    public ?int $cityId = null;
    public string $clubAddress = '';
    public string $clubEmail = '';
    public string $clubPhone = '';
    public $clubLogo;

    // ── Admin flow: Step 3 — Teams ──────────────────────────────
    public array $teams = [];
    public string $teamName = '';
    public string $teamGender = 'male';
    public string $teamBirthYear = '';

    // ── Admin flow: Step 4 — Coaches ────────────────────────────
    public array $coaches = [];
    public string $coachFirstName = '';
    public string $coachLastName = '';
    public string $coachEmail = '';
    public string $coachPhone = '';
    public ?int $coachTeamId = null;

    // ── Admin flow: Step 5 — Players ────────────────────────────
    public array $players = [];
    public string $playerFirstName = '';
    public string $playerLastName = '';
    public string $playerEmail = '';
    public string $playerBirthDate = '';
    public string $playerGender = 'male';
    public ?int $playerTeamId = null;

    // ── Coach flow: Step 2 — Profile ────────────────────────────
    public string $coachSpecialty = '';
    public string $coachLicense = '';
    public string $coachCareerStart = '';
    public ?int $coachSportTypeId = null;

    // ── Player flow: Step 2 — Profile ───────────────────────────
    public ?int $playerPositionId = null;
    public ?int $playerDominantFootId = null;
    public ?int $playerSportTypeId = null;

    // ── Parent flow: Step 2 — Children ──────────────────────────
    public array $children = [];
    public string $childFirstName = '';
    public string $childLastName = '';
    public string $childBirthDate = '';
    public string $childGender = 'male';

    // ── Validation ──────────────────────────────────────────────

    protected array $messages = [
        'selectedRole.required' => 'Выберите вашу роль',
        'clubName.required'     => 'Введите название клуба',
        'sportTypeId.required'  => 'Выберите вид спорта',
        'countryId.required'    => 'Выберите страну',
        'cityId.required'       => 'Выберите город',
        'teamName.required'     => 'Введите название команды',
    ];

    // ── Lifecycle ───────────────────────────────────────────────

    public function mount()
    {
        $this->teamBirthYear = (string) (date('Y') - 10);
    }

    public function render()
    {
        return view('livewire.onboarding', [
            'sportTypes'    => RefSportType::orderBy('name')->get(),
            'clubTypes'     => RefClubType::orderBy('name')->get(),
            'countries'     => Country::orderBy('name')->get(),
            'cities'        => $this->countryId
                ? City::where('country_id', $this->countryId)->orderBy('name')->get()
                : collect(),
            'positions'     => RefPosition::orderBy('name')->get(),
            'dominantFeet'  => RefDominantFoot::orderBy('name')->get(),
        ])->layout('layouts.app');
    }

    public function updatedCountryId()
    {
        $this->cityId = null;
    }

    // ── Step count per role ─────────────────────────────────────

    public function getTotalStepsProperty(): int
    {
        return match ($this->selectedRole) {
            'admin'  => 5, // role → club → teams → coaches → players
            'coach'  => 3, // role → profile → done
            'player' => 3, // role → profile → done
            'parent' => 3, // role → children → done
            default  => 1,
        };
    }

    public function getStepLabelsProperty(): array
    {
        return match ($this->selectedRole) {
            'admin'  => [1 => 'Роль', 2 => 'Клуб', 3 => 'Команды', 4 => 'Тренеры', 5 => 'Игроки'],
            'coach'  => [1 => 'Роль', 2 => 'Профиль', 3 => 'Готово'],
            'player' => [1 => 'Роль', 2 => 'Профиль', 3 => 'Готово'],
            'parent' => [1 => 'Роль', 2 => 'Дети', 3 => 'Готово'],
            default  => [1 => 'Роль'],
        };
    }

    // ══════════════════════════════════════════════════════════════
    //  STEP 1: Role selection
    // ══════════════════════════════════════════════════════════════

    public function selectRole(string $role)
    {
        if (! in_array($role, ['admin', 'coach', 'player', 'parent'])) {
            return;
        }

        $this->selectedRole = $role;

        // Update user's global_role
        $user = Auth::user();
        if ($user) {
            $user->global_role = $role === 'admin' ? 'admin' : $role;
            $user->save();
        }

        $this->step = 2;
    }

    // ══════════════════════════════════════════════════════════════
    //  ADMIN FLOW: Club → Teams → Coaches → Players
    // ══════════════════════════════════════════════════════════════

    public function saveClub()
    {
        $this->validate([
            'clubName'    => 'required|string|max:255',
            'sportTypeId' => 'required|exists:ref_sport_types,id',
            'countryId'   => 'required|exists:countries,id',
            'cityId'      => 'required|exists:cities,id',
            'clubTypeId'  => 'nullable|exists:ref_club_types,id',
            'clubLogo'    => 'nullable|image|max:2048',
            'clubEmail'   => 'nullable|email|max:255',
            'clubPhone'   => 'nullable|string|max:30',
        ]);

        $data = [
            'name'          => $this->clubName,
            'description'   => $this->clubDescription ?: null,
            'sport_type_id' => $this->sportTypeId,
            'club_type_id'  => $this->clubTypeId,
            'country_id'    => $this->countryId,
            'city_id'       => $this->cityId,
            'address'       => $this->clubAddress ?: null,
            'email'         => $this->clubEmail ?: null,
            'phones'        => $this->clubPhone ? [$this->clubPhone] : null,
        ];

        /** @var ClubService $service */
        $service = app(ClubService::class);
        $club = $service->create($data, $this->clubLogo);

        $this->clubId = $club->id;
        $this->step = 3;
    }

    public function addTeam()
    {
        $this->validate([
            'teamName'      => 'required|string|max:255',
            'teamGender'    => 'required|in:male,female',
            'teamBirthYear' => 'required|digits:4',
        ], [
            'teamName.required' => 'Введите название команды',
        ]);

        $team = Team::create([
            'name'          => $this->teamName,
            'gender'        => $this->teamGender,
            'birth_year'    => (int) $this->teamBirthYear,
            'club_id'       => $this->clubId,
            'sport_type_id' => $this->sportTypeId,
            'country_id'    => $this->countryId,
            'city_id'       => $this->cityId,
        ]);

        $this->teams[] = [
            'id'         => $team->id,
            'name'       => $team->name,
            'gender'     => $team->gender,
            'birth_year' => $team->birth_year,
        ];

        $this->teamName = '';
        $this->teamBirthYear = (string) (date('Y') - 10);
    }

    public function removeTeam(int $index)
    {
        if (isset($this->teams[$index])) {
            $teamId = $this->teams[$index]['id'];
            Team::where('id', $teamId)->delete();
            unset($this->coaches[$teamId], $this->players[$teamId]);
            array_splice($this->teams, $index, 1);
        }
    }

    public function goToCoaches()
    {
        if (empty($this->teams)) {
            $this->addError('teams', 'Добавьте хотя бы одну команду');
            return;
        }
        $this->coachTeamId = $this->teams[0]['id'];
        $this->step = 4;
    }

    public function addCoach()
    {
        $this->validate([
            'coachFirstName' => 'required|string|max:100',
            'coachLastName'  => 'required|string|max:100',
            'coachEmail'     => 'required|email|max:255',
            'coachTeamId'    => 'required|integer',
        ], [
            'coachFirstName.required' => 'Введите имя тренера',
            'coachLastName.required'  => 'Введите фамилию тренера',
            'coachEmail.required'     => 'Введите email тренера',
        ]);

        $teamId = $this->coachTeamId;

        $user = User::where('email', $this->coachEmail)->first();
        $isNew = false;

        if (! $user) {
            $user = User::create([
                'first_name'    => $this->coachFirstName,
                'last_name'     => $this->coachLastName,
                'email'         => $this->coachEmail,
                'phone'         => $this->coachPhone ?: null,
                'password_hash' => Hash::make('password123'),
                'birth_date'    => '1990-01-01',
                'gender'        => 'male',
                'global_role'   => 'coach',
            ]);
            $isNew = true;
        }

        CoachProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['sport_type_id' => $this->sportTypeId]
        );

        $coachRoleId = RefUserRole::where('name', 'coach')->value('id');
        TeamMember::firstOrCreate(
            ['user_id' => $user->id, 'team_id' => $teamId],
            [
                'club_id'   => $this->clubId,
                'role_id'   => $coachRoleId,
                'joined_at' => now()->toDateString(),
                'is_active' => true,
            ]
        );

        $this->coaches[$teamId][] = [
            'user_id' => $user->id,
            'name'    => $user->short_name,
            'email'   => $user->email,
            'is_new'  => $isNew,
        ];

        $this->coachFirstName = '';
        $this->coachLastName = '';
        $this->coachEmail = '';
        $this->coachPhone = '';
    }

    public function goToPlayers()
    {
        $this->playerTeamId = $this->teams[0]['id'];
        $this->step = 5;
    }

    public function addPlayer()
    {
        $this->validate([
            'playerFirstName' => 'required|string|max:100',
            'playerLastName'  => 'required|string|max:100',
            'playerBirthDate' => 'required|date',
            'playerGender'    => 'required|in:male,female',
            'playerTeamId'    => 'required|integer',
        ], [
            'playerFirstName.required' => 'Введите имя игрока',
            'playerLastName.required'  => 'Введите фамилию игрока',
            'playerBirthDate.required' => 'Введите дату рождения',
        ]);

        $teamId = $this->playerTeamId;

        $user = null;
        if ($this->playerEmail) {
            $user = User::where('email', $this->playerEmail)->first();
        }

        $isNew = false;
        if (! $user) {
            $userData = [
                'first_name'    => $this->playerFirstName,
                'last_name'     => $this->playerLastName,
                'password_hash' => Hash::make('password123'),
                'birth_date'    => $this->playerBirthDate,
                'gender'        => $this->playerGender,
                'global_role'   => 'player',
            ];
            if ($this->playerEmail) {
                $userData['email'] = $this->playerEmail;
            }
            $user = User::create($userData);
            $isNew = true;
        }

        PlayerProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['sport_type_id' => $this->sportTypeId]
        );

        $playerRoleId = RefUserRole::where('name', 'player')->value('id');
        TeamMember::firstOrCreate(
            ['user_id' => $user->id, 'team_id' => $teamId],
            [
                'club_id'   => $this->clubId,
                'role_id'   => $playerRoleId,
                'joined_at' => now()->toDateString(),
                'is_active' => true,
            ]
        );

        $this->players[$teamId][] = [
            'user_id' => $user->id,
            'name'    => "{$this->playerLastName} {$this->playerFirstName}",
            'email'   => $user->email ?? '—',
            'is_new'  => $isNew,
        ];

        $this->playerFirstName = '';
        $this->playerLastName = '';
        $this->playerEmail = '';
        $this->playerBirthDate = '';
    }

    // ══════════════════════════════════════════════════════════════
    //  COACH FLOW: Profile setup
    // ══════════════════════════════════════════════════════════════

    public function saveCoachProfile()
    {
        $this->validate([
            'coachSportTypeId' => 'required|exists:ref_sport_types,id',
        ], [
            'coachSportTypeId.required' => 'Выберите вид спорта',
        ]);

        $user = Auth::user();

        CoachProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'sport_type_id'  => $this->coachSportTypeId,
                'license_number' => $this->coachLicense ?: null,
                'career_start'   => $this->coachCareerStart ?: null,
            ]
        );

        $this->step = 3;
    }

    // ══════════════════════════════════════════════════════════════
    //  PLAYER FLOW: Profile setup
    // ══════════════════════════════════════════════════════════════

    public function savePlayerProfile()
    {
        $this->validate([
            'playerSportTypeId' => 'required|exists:ref_sport_types,id',
        ], [
            'playerSportTypeId.required' => 'Выберите вид спорта',
        ]);

        $user = Auth::user();

        PlayerProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'sport_type_id'    => $this->playerSportTypeId,
                'position_id'      => $this->playerPositionId,
                'dominant_foot_id' => $this->playerDominantFootId,
            ]
        );

        $this->step = 3;
    }

    // ══════════════════════════════════════════════════════════════
    //  PARENT FLOW: Add children
    // ══════════════════════════════════════════════════════════════

    public function addChild()
    {
        $this->validate([
            'childFirstName' => 'required|string|max:100',
            'childLastName'  => 'required|string|max:100',
            'childBirthDate' => 'required|date',
            'childGender'    => 'required|in:male,female',
        ], [
            'childFirstName.required' => 'Введите имя ребёнка',
            'childLastName.required'  => 'Введите фамилию ребёнка',
            'childBirthDate.required' => 'Введите дату рождения',
        ]);

        $child = User::create([
            'first_name'    => $this->childFirstName,
            'last_name'     => $this->childLastName,
            'password_hash' => Hash::make('password123'),
            'birth_date'    => $this->childBirthDate,
            'gender'        => $this->childGender,
            'global_role'   => 'player',
        ]);

        PlayerProfile::firstOrCreate(
            ['user_id' => $child->id],
            []
        );

        // TODO: Link parent ↔ child relation when parent_children table is ready

        $this->children[] = [
            'id'   => $child->id,
            'name' => "{$this->childLastName} {$this->childFirstName}",
            'birth_date' => $this->childBirthDate,
        ];

        $this->childFirstName = '';
        $this->childLastName = '';
        $this->childBirthDate = '';
    }

    public function goToParentDone()
    {
        if (empty($this->children)) {
            $this->addError('children', 'Добавьте хотя бы одного ребёнка');
            return;
        }
        $this->step = 3;
    }

    // ══════════════════════════════════════════════════════════════
    //  NAVIGATION
    // ══════════════════════════════════════════════════════════════

    public function prevStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function finish()
    {
        // Отмечаем пользователя как прошедшего онбординг
        $user = Auth::user();
        if ($user) {
            $user->onboarded_at = now();
            $user->save();
        }

        $message = match ($this->selectedRole) {
            'admin'  => 'Клуб «' . $this->clubName . '» успешно создан!',
            'coach'  => 'Профиль тренера настроен!',
            'player' => 'Профиль игрока настроен!',
            'parent' => 'Профиль родителя настроен!',
            default  => 'Настройка завершена!',
        };

        session()->flash('success', $message);

        return redirect()->route('home');
    }
}
