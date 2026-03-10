# Примеры использования API (curl)

Эта папка содержит примеры curl-команд для работы с API.

## Использование

1. Установите `BASE_URL` и `TOKEN` в начале каждого скрипта
2. Сделайте скрипт исполняемым: `chmod +x auth.sh`
3. Запустите: `./auth.sh`

## Файлы

| Файл | Описание |
|------|----------|
| `auth.sh` | Регистрация, вход, выход, сброс пароля |
| `clubs.sh` | CRUD операции с клубами |
| `teams.sh` | CRUD операции с командами |
| `trainings.sh` | Тренировки и посещаемость |
| `matches.sh` | Матчи, события, составы |
| `references.sh` | Публичные справочники (без авторизации) |

## Быстрый старт

```bash
# 1. Регистрация
curl -X POST "http://localhost:8000/api/v1/auth/register" \
  -H "Content-Type: application/json" \
  -d '{"first_name":"Иван","last_name":"Петров","email":"ivan@example.com","password":"password123","password_confirmation":"password123","birth_date":"2010-05-15","gender":"male"}'

# 2. Вход (сохраните token из ответа)
curl -X POST "http://localhost:8000/api/v1/auth/login" \
  -H "Content-Type: application/json" \
  -d '{"login":"ivan@example.com","password":"password123"}'

# 3. Использование API с токеном
export TOKEN="your_token_here"
curl -X GET "http://localhost:8000/api/v1/me" \
  -H "Authorization: Bearer ${TOKEN}"
```

## Формат ответов

Все ответы возвращаются в формате JSON. Успешные ответы имеют код 200/201, ошибки валидации — 422.

```json
// Успешный ответ (200)
{
  "user": { ... },
  "token": "1|abc123..."
}

// Ошибка валидации (422)
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```
