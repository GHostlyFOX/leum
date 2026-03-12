<?php

namespace Modules\Club\Http\Controllers;

use Modules\Club\Models\Club;
use Modules\Reference\Models\Country;
use Modules\Reference\Models\City;
use Modules\File\Models\File;
use Modules\Reference\Models\RefClubType;
use Modules\Reference\Models\RefSportType;
use Modules\Club\Services\ClubService;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamMember;
use Modules\Team\Models\Season;
use Modules\Training\Models\Venue;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ClubController extends Controller
{
    /**
     * Главная страница клуба текущего пользователя.
     */
    public function index()
    {
        $currentUser = Auth::user();
        if (empty($currentUser)) {
            return redirect()->route('auth.index');
        }

        // Получаем клуб пользователя через team_members
        $membership = TeamMember::where('user_id', $currentUser->id)
            ->whereIn('role_id', [7, 8]) // admin или coach
            ->first();

        if (!$membership) {
            $membership = TeamMember::where('user_id', $currentUser->id)->first();
        }

        if (!$membership) {
            return redirect()->route('home')
                ->with('error', 'Вы не привязаны к клубу');
        }

        $clubId = $membership->club_id;

        // Команды клуба с количеством игроков
        $teams = Team::where('club_id', $clubId)
            ->withCount(['members' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();

        // Активные сезоны
        $seasons = Season::where('club_id', $clubId)
            ->where('status', 'active')
            ->orderBy('start_date', 'desc')
            ->get();

        // Тренеры клуба (через team_members с role_id = 8 - coach)
        $coaches = TeamMember::where('club_id', $clubId)
            ->where('role_id', 8)
            ->with(['user', 'team'])
            ->get()
            ->unique('user_id');

        // Места проведения тренировок
        $venues = Venue::where('club_id', $clubId)
            ->orderBy('name')
            ->get();

        $club = Club::find($clubId);

        return view('club::index', compact('club', 'teams', 'seasons', 'coaches', 'venues'));
    }

    /**
     * Обработать POST-запрос на сохранение нового клуба.
     */
    public function saveClub(Request $request)
    {
        $currentUser = Auth::user();
        if (empty($currentUser)) {
            return redirect()->route('home');
        }

        $validated = $request->validate([
            'name'          => 'required|string|max:255|unique:clubs,name',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description'   => 'nullable|string',
            'sport_type_id' => 'required|exists:ref_sport_types,id',
            'country_id'    => 'required|exists:countries,id',
            'city_id'       => 'required|exists:cities,id',
            'address'       => 'required|string|max:255',
            'email'         => 'nullable|email|unique:clubs,email',
            'phones'        => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $items = is_array($value) ? $value : json_decode($value, true);
                    if (! is_array($items)) {
                        return $fail("Поле \"{$attribute}\" должно быть JSON-массивом.");
                    }
                    foreach ($items as $phone) {
                        if (! preg_match('/^\(\d{3}\) \d{3}-\d{2}-\d{2}$/', $phone)) {
                            return $fail("Номер \"{$phone}\" должен соответствовать формату (XXX) XXX-XX-XX.");
                        }
                    }
                },
            ],
            'club_type_id' => 'required|exists:ref_club_types,id',
        ]);

        // Преобразуем телефоны в формат +7XXXXXXXXXX
        $formattedPhones = [];
        foreach ($validated['phones'] as $raw) {
            $digits = preg_replace('/\D/', '', $raw);
            if (strlen($digits) === 10) {
                $formattedPhones[] = '+7' . $digits;
            } elseif (strlen($digits) === 11 && str_starts_with($digits, '7')) {
                $formattedPhones[] = '+' . $digits;
            } else {
                $formattedPhones[] = '+7' . $digits;
            }
        }

        // Обработка логотипа через таблицу files
        $logoFileId = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = $file->store('clubs', 'public');
            $fileModel = File::create([
                'path'       => $path,
                'mime_type'  => $file->getMimeType(),
                'size_bytes' => $file->getSize(),
            ]);
            $logoFileId = $fileModel->id;
        }

        $club = Club::create([
            'name'          => $validated['name'],
            'description'   => $validated['description'] ?? null,
            'logo_file_id'  => $logoFileId,
            'sport_type_id' => $validated['sport_type_id'],
            'country_id'    => $validated['country_id'],
            'city_id'       => $validated['city_id'],
            'address'       => $validated['address'],
            'email'         => $validated['email'] ?? null,
            'phones'        => $formattedPhones,
            'club_type_id'  => $validated['club_type_id'],
        ]);

        return redirect()
            ->route('home')
            ->with('success', 'Клуб успешно создан.');
    }

    public function stuff()
    {
        return view('club::index');
    }

    public function refs()
    {
        return view('club::index');
    }

    public function list()
    {
        $currentUser = Auth::user();
        if (empty($currentUser)) {
            return redirect()->route('auth.index');
        }

        $clubs = Club::orderBy('name')->simplePaginate(15);
        return view('club::list', compact('clubs'));
    }

    public function add()
    {
        $typeSports = RefSportType::orderBy('name')->get();
        $countries  = Country::orderBy('name')->get();
        $cities     = City::orderBy('name')->get();
        $typeClubs  = RefClubType::orderBy('name')->get();

        return view('club::add', compact('typeSports', 'countries', 'cities', 'typeClubs'));
    }

    public function teamList()
    {
        return view('club::team.list');
    }

    public function teamAdd()
    {
        return view('club::team.add');
    }

    public function create()
    {
        return view('club::create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return view('club::show');
    }

    public function edit($id)
    {
        return view('club::edit');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
