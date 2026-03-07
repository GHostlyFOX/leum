<?php

namespace Modules\Auth\Http\Controllers;

use Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth::register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name'  => ['required', 'string', 'max:100'],
            'last_name'   => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'email'       => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'phone'       => ['required', 'regex:/^(\+7|8)\d{10}$/', 'unique:users,phone'],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
            'birth_date'  => ['required', 'date'],
            'gender'      => ['required', 'in:male,female'],
            'consent_personal_data'  => ['accepted'],
            'notifications_on'       => ['nullable'],
        ], [
            'phone.regex' => 'Телефон должен быть в формате +7XXXXXXXXXX или 8XXXXXXXXXX',
            'consent_personal_data.accepted' => 'Вы должны согласиться с условиями обработки персональных данных.',
        ]);

        $user = User::create([
            'first_name'       => $validated['first_name'],
            'last_name'        => $validated['last_name'],
            'middle_name'      => $validated['middle_name'] ?? null,
            'email'            => $validated['email'] ?? null,
            'phone'            => $validated['phone'],
            'password_hash'    => Hash::make($validated['password']),
            'birth_date'       => $validated['birth_date'],
            'gender'           => $validated['gender'],
            'notifications_on' => isset($validated['notifications_on']),
        ]);

        auth()->login($user);

        return redirect()->route('home');
    }

    public function agreement(Request $request)
    {
        return view('auth::agreement');
    }
}
