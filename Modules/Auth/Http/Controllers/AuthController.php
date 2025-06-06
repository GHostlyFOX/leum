<?php

namespace Modules\Auth\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth::index');
    }

    /**
     * Обработать попытку входа.
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Определяем, это email или телефон
        $field = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $field    => $data['login'],
            'password'=> $data['password'],
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        throw ValidationException::withMessages([
            'login' => 'Неверный E-Mail или телефон, или неверный пароль.',
        ]);
    }

    /**
     * Выход из системы.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.loginForm');
    }

    public function credentials(Request $request)
    {
        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        return [
            $field => $login,
            'password' => $request->input('password'),
        ];
    }

    public function showForm()
    {
        return view('auth::forgot-password');
    }
    public function handleRequest(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
        ]);

        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        /** @var User $user */
        $user = User::where($field, $login)->first();

        if (!$user) {
            return back()->withErrors(['login' => 'Пользователь не найден']);
        }

        $token = $field === 'email' ? Str::random(64) : str_pad(random_int(10000, 99999), 5, '0', STR_PAD_LEFT);
        $user->forgot_token = $token;
        $user->save();

        if ($field === 'email') {
            Mail::send('auth::emails.password_reset', ['token' => $token], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Восстановление пароля на liga.ru');
            });
            return back()->with('status', 'Ссылка отправлена на email');
        }

        // Здесь вставить логику отправки SMS
        // SmsService::send($user->phone, "Код восстановления: $token");

        return redirect()->route('password.sms.form')->with('phone', $user->phone);
    }

    public function verifyToken($token)
    {
        $user = User::where('forgot_token', $token)->first();
        if (!$user) {
            return redirect()->route('password.request')->withErrors(['token' => 'Неверный токен']);
        }

        return redirect()->route('password.reset', ['token' => $token]);
    }

    public function showSmsForm()
    {
        return view('auth::sms-verify');
    }

    public function verifySmsCode(Request $request)
    {
        $request->validate(['code' => 'required|string|size:5']);
        $user = User::where('forgot_token', $request->input('code'))->first();

        if (!$user) {
            return back()->withErrors(['code' => 'Неверный код']);
        }

        return redirect()->route('password.reset', ['token' => $user->forgot_token]);
    }

    public function showResetForm($token)
    {
        return view('auth::reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::where('forgot_token', $request->token)->first();

        if (!$user) {
            return back()->withErrors(['token' => 'Неверный токен']);
        }

        $user->password = Hash::make($request->password);
        $user->forgot_token = null;
        $user->save();

        return redirect()->route('auth.index')->with('status', 'Пароль успешно обновлён');
    }
}
