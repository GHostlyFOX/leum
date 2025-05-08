<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Восстановление пароля</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f8fa;
            padding: 20px;
        }
        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .btn {
            background-color: #007bff;
            color: #ffffff;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .token-box {
            font-weight: bold;
            font-size: 18px;
            color: #333;
            background: #f0f0f0;
            padding: 10px 15px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }
        .footer {
            font-size: 12px;
            color: #888;
            margin-top: 40px;
        }
    </style>
</head>
<body>
<div class="email-container">
    <h2>Восстановление пароля на liga.ru</h2>

    <p>Здравствуйте!</p>

    <p>Вы (или кто-то другой) запросили восстановление пароля для аккаунта, связанного с этим адресом на сайте <strong>liga.ru</strong>.</p>

    <p>Если это были вы — используйте следующий код:</p>

    <div class="token-box">{{ $token }}</div>

    <p>Введите его на <a href="{{ route('password.token', ['token' => '']) }}">странице восстановления</a>.</p>

    <p>Или просто нажмите на кнопку ниже:</p>

    <a href="{{ route('password.token', ['token' => $token]) }}" class="btn">Сбросить пароль</a>

    <p style="margin-top: 30px;">Если вы не запрашивали сброс пароля, просто проигнорируйте это письмо — ваш аккаунт останется в безопасности.</p>

    <div class="footer">
        С уважением,<br>
        Команда liga.ru
    </div>
</div>
</body>
</html>
