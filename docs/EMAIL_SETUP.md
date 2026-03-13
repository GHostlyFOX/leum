# Настройка Email-уведомлений

## Быстрая настройка

Для отправки email-уведомлений необходимо настроить SMTP-сервер в файле `.env`.

### 1. Настройка через Gmail/Google Workspace

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Сбор - Спортивная платформа"
```

**Важно:** Для Gmail нужно использовать **App Password** (пароль приложения), а не обычный пароль:
1. Включите двухфакторную аутентификацию в Google Account
2. Перейдите в Security → App passwords
3. Создайте пароль для приложения "Mail"

### 2. Настройка через Yandex Mail

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.yandex.ru
MAIL_PORT=465
MAIL_USERNAME=your-email@yandex.ru
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=your-email@yandex.ru
MAIL_FROM_NAME="Сбор - Спортивная платформа"
```

### 3. Настройка через Mail.ru

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.ru
MAIL_PORT=465
MAIL_USERNAME=your-email@mail.ru
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=your-email@mail.ru
MAIL_FROM_NAME="Сбор - Спортивная платформа"
```

### 4. Настройка через SendGrid (рекомендуется для продакшена)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@sbor.team
MAIL_FROM_NAME="Сбор - Спортивная платформа"
```

### 5. Настройка через Mailgun

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@your-domain.mailgun.org
MAIL_PASSWORD=your-mailgun-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="Сбор - Спортивная платформа"
```

## Шаблоны email

Все шаблоны email находятся в папке `resources/views/email_templates/`:

- `layout.blade.php` — базовый layout для всех писем
- `welcome.blade.php` — приветственное письмо после регистрации
- `invite.blade.php` — приглашение в команду/клуб
- `password-reset.blade.php` — восстановление пароля

## Добавление нового шаблона

1. Создайте новый файл `.blade.php` в `resources/views/email_templates/`
2. Используйте `@extends('email_templates.layout')` для наследования базового layout
3. Определите секции `title`, `header` и `content`

Пример:

```php
@extends('email_templates.layout')

@section('title', 'Название письма')

@section('header', 'Заголовок в шапке')

@section('content')
<h2 style="color: #2d4a14;">Привет!</h2>
<p>Содержимое письма...</p>
@endsection
```

## Отправка email из кода

```php
use Illuminate\Support\Facades\Mail;

// Отправка простого письма
Mail::send('email_templates.welcome', [
    'userName' => $user->first_name,
    'email' => $user->email,
    'dashboardLink' => route('home')
], function ($message) use ($user) {
    $message->to($user->email, $user->full_name)
            ->subject('Добро пожаловать в Сбор!');
});

// Использование Mailable класса
Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user));
```

## Тестирование email

Для тестирования можно использовать сервис Mailtrap:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```

## Очереди для email

Для отправки email в фоновом режиме используйте очереди:

```php
// Отправка в очередь
Mail::to($user->email)->queue(new WelcomeMail($user));

// Отправка с задержкой
Mail::to($user->email)->later(now()->addMinutes(10), new WelcomeMail($user));
```

Не забудьте настроить очередь в `.env`:

```env
QUEUE_CONNECTION=database
```

И запустить воркер:

```bash
php artisan queue:work
```

## Устранение неполадок

### Письма не отправляются

1. Проверьте настройки в `.env`
2. Очистите кэш конфигурации: `php artisan config:clear`
3. Проверьте логи: `storage/logs/laravel.log`

### Ошибка аутентификации

- Убедитесь, что используете App Password для Gmail/Yandex/Mail.ru
- Проверьте, что включен доступ к SMTP в настройках почтового сервиса

### Письма попадают в спам

- Используйте DKIM и SPF записи для домена
- Настройте MAIL_FROM_ADDRESS на домен вашего приложения
- Используйте проверенные SMTP-сервисы (SendGrid, Mailgun, Postmark)
