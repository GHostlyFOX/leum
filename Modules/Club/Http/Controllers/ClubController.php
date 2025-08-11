<?php

namespace Modules\Club\Http\Controllers;

use App\Models\Club;
use App\Models\RefRegion;
use App\Models\RefTypeClub;
use App\Models\RefTypeSport;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $currentUser = Auth::user();
        if (empty($currentUser)) {
            return redirect()->route('auth.index');
        }
        $clubs = Club::where('admin_id', $currentUser->id)->orderBy('name')->simplePaginate(15);
        return view('club::index', compact('clubs'));
    }

    /**
     * Обработать POST-запрос на сохранение нового клуба.
     */
    public function saveClub(Request $request)
    {
        // Валидация входных данных
        $validated = $request->validate([
            'name'           => 'required|string|max:255|unique:club,name',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description'    => 'nullable|string',
            'ref_type_sport' => 'required|exists:ref_type_sport,id',
            'country'        => 'required|exists:ref_regions,id',
            'city'           => 'required|exists:ref_regions,id',
            'address'        => 'required|string|max:4000',
            'email'          => 'nullable|email|unique:club,email',
            'phones'         => [
                'required',
                'array',
                function($attribute, $value, $fail) {
                    // Проверяем, что value — корректный JSON-массив строк
                    if (!is_array($value)){
                        $decoded = json_decode($value, true);
                        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                            return $fail('Поле "' . $attribute . '" должно быть JSON-массивом.');
                        }
                    }else{
                        $decoded = $value;
                    }
                    foreach ($decoded as $phone) {
                        // Ожидаем формат (000) 000-00-00
                        if (!preg_match('/^\(\d{3}\) \d{3}-\d{2}-\d{2}$/', $phone)) {
                            return $fail("Номер \"{$phone}\" должен соответствовать формату (XXX) XXX-XX-XX.");
                        }
                    }
                }
            ],
            'ref_type_club'  => 'required|exists:ref_type_club,id',
        ]);

        // Обработка файла логотипа
        $logoPath = null;
        if ($request->hasFile('logo')) {
            // Сохраняем в папку storage/app/public/clubs логотип, возвращаем относительный путь
            $path = $request->file('logo')->store('clubs', 'public');
            $logoPath = $path; // Позже можем сохранить путь или привязать к отдельной таблице файлов
        }

        // Преобразуем телефоны в формат +7XXXXXXXXXX
        $formattedPhones = [];
        foreach ($validated['phones'] as $raw) {
            // Удаляем всё кроме цифр
            $digits = preg_replace('/\D/', '', $raw);
            // Если строка 10 цифр, добавляем код страны 7
            if (strlen($digits) === 10) {
                $formattedPhones[] = '+7' . $digits;
            } else {
                // На случай, если вводят 11 цифр без символов: проверяем, начинается ли с 7
                if (strlen($digits) === 11 && substr($digits, 0, 1) === '7') {
                    $formattedPhones[] = '+' . $digits;
                } else {
                    // Неправильное количество цифр — игнорируем или можно бросить исключение
                    // Но до этого момента валидация уже отфильтровала по шаблону, так что сюда не попадём.
                    $formattedPhones[] = '+7' . $digits;
                }
            }
        }
        $currentUser = Auth::user();
        if (!empty($currentUser)){
            $validated['created_by'] = $currentUser->id;
            $validated['updated_by'] = $currentUser->id;
            $validated['admin_id'] = $currentUser->id;
        }else{
            return redirect()->route('home');
        }
        // Создаём запись в таблице club
        $club = new Club();
        $club->name           = $validated['name'];
        $club->description    = $validated['description'] ?? null;
        // Если у модели поле logo — integer (например, ID файла), нужно дополнительное решение.
        // Здесь мы предполагаем, что в таблице club есть колонка `logo` типа string или nullable, куда можно записать относительный путь.
        if ($logoPath) {
            $club->logo = $logoPath;
        }
        $club->ref_type_sport = $validated['ref_type_sport'];
        $club->country        = $validated['country'];
        $club->city           = $validated['city'];
        $club->address        = $validated['address'];
        $club->email          = $validated['email'] ?? null;
        // Сохраняем телефоны как JSON
        $club->phones         = json_encode($formattedPhones);
        $club->ref_type_club  = $validated['ref_type_club'];
        $club->created_by     = $validated['created_by'] ?? null;
        $club->updated_by     = $validated['updated_by'] ?? null;
        $club->admin_id     = $validated['admin_id'] ?? null;
        $club->count_employees = 0;
        $club->count_players = 0;

        $club->save();

        // После успешного сохранения — редиректим на список или страницу просмотра
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
        $clubs = Club::where('admin_id', $currentUser->id)->orderBy('name')->simplePaginate(15);
        return view('club::list', compact('clubs'));
    }

    public function add()
    {
        // Получаем списки для выпадающих полей
        $typeSports = RefTypeSport::orderBy('name')->get();
        $countries  = RefRegion::where('type', 1)->orderBy('name')->get(); // type = 1 – страны
        // Можно сразу вытягивать города, но обычно их подгружают через AJAX после выбора страны.
        // Для простоты — все города:
        $cities     = RefRegion::where('type', 3)->orderBy('name')->get(); // type = 3 – города
        $typeClubs  = RefTypeClub::orderBy('name')->get();
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

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('club::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('club::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('club::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
