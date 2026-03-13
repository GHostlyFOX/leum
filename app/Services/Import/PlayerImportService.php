<?php

declare(strict_types=1);

namespace App\Services\Import;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Reference\Models\RefDominantFoot;
use Modules\Reference\Models\RefPosition;
use Modules\Team\Models\Team;
use Modules\User\Models\CoachProfile;
use Modules\User\Models\PlayerProfile;
use Modules\User\Models\User;
use Modules\User\Models\UserParentPlayer;

class PlayerImportService
{
    /**
     * Импорт игроков из массива данных Excel
     *
     * @param array $data Массив строк из Excel
     * @param int $teamId ID команды
     * @param int $clubId ID клуба
     * @return array Результат импорта
     */
    public function import(array $data, int $teamId, int $clubId): array
    {
        $results = [
            'success' => [],
            'errors' => [],
            'total' => count($data),
        ];

        $team = Team::find($teamId);
        if (!$team) {
            throw new \InvalidArgumentException('Команда не найдена');
        }

        foreach ($data as $index => $row) {
            $rowNumber = $index + 2; // +2 потому что первая строка - заголовок

            try {
                $player = $this->processRow($row, $teamId, $clubId);
                $results['success'][] = [
                    'row' => $rowNumber,
                    'name' => $player->full_name,
                    'id' => $player->id,
                ];
            } catch (ValidationException $e) {
                $results['errors'][] = [
                    'row' => $rowNumber,
                    'errors' => $e->validator->errors()->all(),
                ];
            } catch (\Exception $e) {
                $results['errors'][] = [
                    'row' => $rowNumber,
                    'errors' => [$e->getMessage()],
                ];
            }
        }

        return $results;
    }

    /**
     * Обработка одной строки Excel
     *
     * @param array $row
     * @param int $teamId
     * @param int $clubId
     * @return User
     * @throws ValidationException
     */
    private function processRow(array $row, int $teamId, int $clubId): User
    {
        // Нормализация ключей
        $data = $this->normalizeRow($row);

        // Валидация
        $validator = Validator::make($data, [
            'last_name' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255|unique:users,email',
            'position' => 'nullable|string',
            'dominant_foot' => 'nullable|string',
            'parent_name' => 'nullable|string',
            'parent_phone' => 'nullable|string',
            'parent_email' => 'nullable|email',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Создание пользователя
        $user = User::create([
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'email' => $data['email'] ?? $this->generateTempEmail(),
            'phone' => $data['phone'] ?? null,
            'password_hash' => bcrypt(uniqid()), // Временный пароль
            'birth_date' => $data['birth_date'] ?? '2010-01-01',
            'gender' => $data['gender'] ?? 'male',
            'notifications_on' => true,
        ]);

        // Добавление в команду
        $user->teams()->attach($teamId, [
            'club_id' => $clubId,
            'role_id' => 10, // player
            'joined_at' => now(),
            'is_active' => true,
        ]);

        // Создание профиля игрока
        $positionId = $this->getPositionId($data['position'] ?? null);
        $footId = $this->getDominantFootId($data['dominant_foot'] ?? null);

        PlayerProfile::create([
            'user_id' => $user->id,
            'sport_type_id' => 1, // football по умолчанию
            'position_id' => $positionId,
            'dominant_foot_id' => $footId ?? 1,
        ]);

        // Создание родителя (если указан)
        if (!empty($data['parent_name'])) {
            $this->createParent($data, $user->id);
        }

        return $user;
    }

    /**
     * Нормализация строки из Excel
     *
     * @param array $row
     * @return array
     */
    private function normalizeRow(array $row): array
    {
        $mapping = [
            'фамилия' => 'last_name',
            'фамилия*' => 'last_name',
            'фамилия *' => 'last_name',
            'имя' => 'first_name',
            'имя*' => 'first_name',
            'имя *' => 'first_name',
            'отчество' => 'middle_name',
            'дата рождения' => 'birth_date',
            'дата_рождения' => 'birth_date',
            'дата_рождения' => 'birth_date',
            'пол' => 'gender',
            'телефон' => 'phone',
            'тел' => 'phone',
            'email' => 'email',
            'e-mail' => 'email',
            'почта' => 'email',
            'позиция' => 'position',
            'амплуа' => 'position',
            'рабочая нога' => 'dominant_foot',
            'нога' => 'dominant_foot',
            'фио родителя' => 'parent_name',
            'родитель' => 'parent_name',
            'телефон родителя' => 'parent_phone',
            'тел родителя' => 'parent_phone',
            'email родителя' => 'parent_email',
            'почта родителя' => 'parent_email',
        ];

        $normalized = [];
        foreach ($row as $key => $value) {
            $lowerKey = mb_strtolower(trim($key));
            $normalizedKey = $mapping[$lowerKey] ?? $lowerKey;
            $normalized[$normalizedKey] = $value;
        }

        return $normalized;
    }

    /**
     * Получить ID позиции по названию
     *
     * @param string|null $position
     * @return int|null
     */
    private function getPositionId(?string $position): ?int
    {
        if (!$position) {
            return null;
        }

        $position = mb_strtolower(trim($position));
        
        $mapping = [
            'вратарь' => 1,
            'вр' => 1,
            'goalkeeper' => 1,
            'gk' => 1,
            'защитник' => 2,
            'защ' => 2,
            'defender' => 2,
            'df' => 2,
            'полузащитник' => 3,
            'пз' => 3,
            'midfielder' => 3,
            'mf' => 3,
            'нападающий' => 4,
            'нап' => 4,
            'forward' => 4,
            'fw' => 4,
        ];

        $id = $mapping[$position] ?? null;
        
        if (!$id) {
            // Поиск в БД
            $ref = RefPosition::whereRaw('LOWER(name) = ?', [$position])->first();
            $id = $ref?->id;
        }

        return $id;
    }

    /**
     * Получить ID рабочей ноги
     *
     * @param string|null $foot
     * @return int|null
     */
    private function getDominantFootId(?string $foot): ?int
    {
        if (!$foot) {
            return null;
        }

        $foot = mb_strtolower(trim($foot));

        $mapping = [
            'левая' => 1,
            'л' => 1,
            'left' => 1,
            'правая' => 2,
            'п' => 2,
            'right' => 2,
            'обе' => 3,
            'обе ноги' => 3,
            'both' => 3,
        ];

        $id = $mapping[$foot] ?? null;

        if (!$id) {
            $ref = RefDominantFoot::whereRaw('LOWER(name) = ?', [$foot])->first();
            $id = $ref?->id;
        }

        return $id;
    }

    /**
     * Создать временный email
     *
     * @return string
     */
    private function generateTempEmail(): string
    {
        return 'temp_' . uniqid() . '@sbor.team';
    }

    /**
     * Создать родителя и связать с игроком
     *
     * @param array $data
     * @param int $playerUserId
     * @return void
     */
    private function createParent(array $data, int $playerUserId): void
    {
        $names = explode(' ', $data['parent_name'], 2);
        $lastName = $names[0] ?? $data['parent_name'];
        $firstName = $names[1] ?? '';

        $parent = User::create([
            'last_name' => $lastName,
            'first_name' => $firstName,
            'email' => $data['parent_email'] ?? $this->generateTempEmail(),
            'phone' => $data['parent_phone'] ?? null,
            'password_hash' => bcrypt(uniqid()),
            'birth_date' => '1980-01-01',
            'gender' => 'female',
            'notifications_on' => true,
        ]);

        // Создание связи родитель-игрок
        UserParentPlayer::create([
            'parent_user_id' => $parent->id,
            'player_user_id' => $playerUserId,
            'kinship_type_id' => 2, // Мама по умолчанию
        ]);
    }
}
