<?php
namespace Modules\Auth\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth::register');
    }
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'regex:/^(\+7|8)\d{10}$/', 'unique:users,phone'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'consent_personal_data' => ['accepted'],
            'is_send_notifications' => ['nullable'],
        ], [
            'phone.regex' => 'Телефон должен быть в формате +7XXXXXXXXXX или 8XXXXXXXXXX',
            'consent_personal_data.accepted' => 'Вы должны согласиться с условиями обработки персональных данных.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'lastname' => $validated['lastname'],
            'middlename' => $validated['middlename'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'is_send_notifications' => isset($validated['is_send_notifications']),
        ]);

        // Если хочешь автоматически логинить пользователя:
        auth()->login($user);

        return redirect()->route('home');
    }

    public function agreement(Request $request)
    {
        return view('auth::agreement');
    }
}
