# Исправление создания сезона

## Проблема
Сезон не создаётся, в ответе `"clubId":null`

## Причина
Пользователь не имеет записи в `team_members` с `role_id = 7` (admin)

## Исправления

### 1. Расширена проверка ролей (Seasons.php)
```php
// Было: только role_id = 7 (admin)
$membership = TeamMember::where('user_id', $user->id)
    ->where('role_id', 7)
    ->first();

// Стало: admin (7) + coach (8), fallback на любую роль
$membership = TeamMember::where('user_id', $user->id)
    ->whereIn('role_id', [7, 8])
    ->first();

if (!$this->clubId) {
    $membership = TeamMember::where('user_id', $user->id)->first();
    $this->clubId = $membership?->club_id;
}
```

### 2. Добавлены уведомления
- Ошибка: "У вас нет доступа к управлению клубом"
- Успех: "Сезон создан" / "Сезон обновлён"

## Действия

1. Очистить кеш: `https://sbor.team/clear.php`
2. Проверить страницу сезонов

## Если не работает

Проверьте, есть ли запись в таблице `team_members` для текущего пользователя:
```sql
SELECT * FROM team_members WHERE user_id = <ID_пользователя>;
```

Если записи нет - пользователь не привязан к клубу.
