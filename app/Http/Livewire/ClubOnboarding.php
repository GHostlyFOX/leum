<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Club\Models\Club;
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

class ClubOnboarding extends Component
{
    use WithFileUploads;

    // ── Wizard state ─────────────────────────────────────────────
    public int $step = 1;
    public ?int $clubId = null;

    // ── Step 1: Club ─────────────────────────────────────────────
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

    // ── Step 2: Teams ────────────────────────────────────────────
    public array $teams = [];      // [{id, name, gender, birth_year}]
    public string $teamName = '';
    public string $teamGender = 'male';
    public string $teamBirthYear = '';

    // ── Step 3: Coaches ──────────────────────────────────────────
    public array $coaches = [];    // teamId => [{user_id, name, email, is_new}]
    public string $coachFirstName = '';
    public string $coachLastName = '';
    public string $coachEmail = '';
    public string $coachPhone = '';
    public ?int $coachTeamId = null;

    // ── Step 4: Players ──────────────────────────────────────────
    public array $players = [];    // teamId => [{user_id, name, email, is_new}]
    public string $playerFirstName = '';
    public string $playerLastName = '';
    public string $playerEmail = '';
    public string $playerBirthDate = '';
    public string $playerGender = 'male';
    public ?int $playerTeamId = null;

    // ── Validation rules by step ─────────────────────────────────

    protected function stepRules(): array
    {
        return match ($this->step) {
            1 => [
                'clubName'    => 'required|string|max:255',
                'sportTypeId' => 'required|exists:ref_sport_types,id',
                'countryId'   => 'required|exists:countries,id',
                'cityId'      => 'required|exists:cities,id',
                'clubTypeId'  => 'nullable|exists:ref_club_types,id',
                'clubLogo'    => 'nullable|image|max:2048',
                'clubEmail'   => 'nullable|email|max:255',
                'clubPhone'   => 'nullable|string|max:30',
            ],
            default => [],
        };
    }

    protected array $messages = [
        'clubName.required'    => 'Введите название клуба',
        'sportTypeId.required' => 'Выберите вид спорта',
        'countryId.required'   => 'Выберите страну',
        'cityId.required'      => 'Выберите город',
        'teamName.required'    => 'Введите название команды',
    ];

    // ── Lifecycle ────────────────────────────────────────────────

    public function mount()
    {
        $this->teamBirthYear = (string) (date('Y') - 10);
    }

    public function render()
    {
        return view('livewire.club-onboarding', [
            'sportTypes' => RefSportType::orderBy('name')->get(),
            'clubTypes'  => RefClubType::orderBy('name')->get(),
            'countries'  => Country::orderBy('name')->get(),
            'cities'     => $this->countryId
                ? City::where('country_id', $this->countryId)->orderBy('name')->get()
                : collect(),
            'positions'     => RefPosition::orderBy('name')->get(),
            'dominantFeet'  => RefDominantFoot::orderBy('name')->get(),
        ]);
    }

    public function updatedCountryId()
    {
        $this->cityId = null;
    }

    // ── Step 1: Save Club ────────────────────────────────────────

    public function saveClub()
    {
        $this->validate($this->stepRules());

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
        $this->step = 2;
    }

    // ── Step 2: Teams ────────────────────────────────────────────

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

        // Reset form
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
        $this->step = 3;
    }

    // ── Step 3: Coaches ──────────────────────────────────────────

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

        // Find or create user
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

        // Ensure coach profile
        CoachProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['sport_type_id' => $this->sportTypeId]
        );

        // Add to team
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

        // Track in local state
        $this->coaches[$teamId][] = [
            'user_id' => $user->id,
            'name'    => $user->short_name,
            'email'   => $user->email,
            'is_new'  => $isNew,
        ];

        // Reset form
        $this->coachFirstName = '';
        $this->coachLastName = '';
        $this->coachEmail = '';
        $this->coachPhone = '';
    }

    public function goToPlayers()
    {
        $this->playerTeamId = $this->teams[0]['id'];
        $this->step = 4;
    }

    // ── Step 4: Players ──────────────────────────────────────────

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

        // Find by email or create
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

        // Ensure player profile
        PlayerProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['sport_type_id' => $this->sportTypeId]
        );

        // Add to team
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

        // Track in local state
        $this->players[$teamId][] = [
            'user_id' => $user->id,
            'name'    => "{$this->playerLastName} {$this->playerFirstName}",
            'email'   => $user->email ?? '—',
            'is_new'  => $isNew,
        ];

        // Reset form
        $this->playerFirstName = '';
        $this->playerLastName = '';
        $this->playerEmail = '';
        $this->playerBirthDate = '';
    }

    // ── Navigation ───────────────────────────────────────────────

    public function prevStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function finish()
    {
        session()->flash('success', 'Клуб «' . $this->clubName . '» успешно создан!');
        return redirect()->route('club.index');
    }
}
