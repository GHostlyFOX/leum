<?php

namespace Modules\Auth\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Страница входа.
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
     *
     * Auth::attempt автоматически хеширует 'password' и сравнивает
     * с результатом User::getAuthPassword(), который возвращает password_hash.
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $field = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $field     => $data['login'],
            'password' => $data['password'],
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

    /**
     * Форма «Забыли пароль».
     */
    public function showForm()
    {
        return view('auth::forgot-password');
    }

    /**
     * Обработка запроса восстановления пароля.
     * Токен сохраняется в таблице password_resets (стандартная Laravel).
     */
    public function handleRequest(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
        ]);

        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        /** @var User $user */
        $user = User::where($field, $login)->first();

        if (! $user) {
            return back()->withErrors(['login' => 'Пользователь не найден']);
        }

        $token = $field === 'email'
            ? Str::random(64)
            : str_pad(random_int(10000, 99999), 5, '0', STR_PAD_LEFT);

        // Сохраняем токен в password_resets
        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($token), 'created_at' => now()],
        );

        if ($field === 'email') {
            Mail::send('auth::emails.password_reset', ['token' => $token], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Восстановление пароля — Детская лига');
            });
            return back()->with('status', 'Ссылка отправлена на email');
        }

        // TODO: подключить SmsService
        // SmsService::send($user->phone, "Код восстановления: $token");

        return redirect()->route('password.sms.form')->with('phone', $user->phone);
    }

    /**
     * Проверка токена из email-ссылки.
     */
    public function verifyToken($token)
    {
        $reset = DB::table('password_resets')->first();

        if (! $reset || ! Hash::check($token, $reset->token)) {
            return redirect()->route('password.request')
                ->withErrors(['token' => 'Неверный или просроченный токен']);
        }

        return redirect()->route('password.reset', ['token' => $token]);
    }

    /**
     * Форма ввода SMS-кода.
     */
    public function showSmsForm()
    {
        return view('auth::sms-verify');
    }

    /**
     * Проверка SMS-кода.
     */
    public function verifySmsCode(Request $request)
    {
        $request->validate(['code' => 'required|string|size:5']);

        // Для SMS-кода: ищем запись в password_resets по коду
        $resets = DB::table('password_resets')->get();
        $found = null;
        foreach ($resets as $reset) {
            if (Hash::check($request->input('code'), $reset->token)) {
                $found = $reset;
                break;
            }
        }

        if (! $found) {
            return back()->withErrors(['code' => 'Неверный код']);
        }

        return redirect()->route('password.reset', ['token' => $request->input('code')]);
    }

    /**
     * Форма сброса пароля.
     */
    public function showResetForm($token)
    {
        return view('auth::reset-password', compact('token'));
    }

    /**
     * Сброс пароля.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Находим запись по email из password_resets
        $resets = DB::table('password_resets')->get();
        $found = null;
        foreach ($resets as $reset) {
            if (Hash::check($request->token, $reset->token)) {
                $found = $reset;
                break;
            }
        }

        if (! $found) {
            return back()->withErrors(['token' => 'Неверный или просроченный токен']);
        }

        $user = User::where('email', $found->email)->first();
        if (! $user) {
            return back()->withErrors(['token' => 'Пользователь не найден']);
        }

        $user->password_hash = Hash::make($request->password);
        $user->save();

        // Удаляем использованный токен
        DB::table('password_resets')->where('email', $found->email)->delete();

        return redirect()->route('auth.index')->with('status', 'Пароль успешно обновлён');
    }
}
