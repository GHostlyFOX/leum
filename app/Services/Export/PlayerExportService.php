<?php

declare(strict_types=1);

namespace App\Services\Export;

use Illuminate\Support\Collection;
use Modules\Team\Models\Team;
use Modules\User\Models\User;

class PlayerExportService
{
    /**
     * Заголовки для экспорта игроков
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Фамилия',
            'Имя',
            'Отчество',
            'Дата рождения',
            'Пол',
            'Телефон',
            'Email',
            'Позиция',
            'Рабочая нога',
            'Дата вступления',
            'Статус',
        ];
    }

    /**
     * Получить данные игроков команды для экспорта
     *
     * @param int $teamId
     * @return Collection
     */
    public function getTeamPlayersData(int $teamId): Collection
    {
        $team = Team::with([
            'members' => function ($query) {
                $query->where('role_id', 10) // player
                    ->with('user.playerProfile.position', 'user.playerProfile.dominantFoot');
            }
        ])->findOrFail($teamId);

        return $team->members->map(function ($member) {
            $user = $member->user;
            $profile = $user->playerProfile;

            return [
                'last_name' => $user->last_name,
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'birth_date' => $user->birth_date?->format('d.m.Y'),
                'gender' => $this->formatGender($user->gender),
                'phone' => $user->phone,
                'email' => $this->formatEmail($user->email),
                'position' => $profile?->position?->name ?? '',
                'dominant_foot' => $profile?->dominantFoot?->name ?? '',
                'joined_at' => $member->joined_at?->format('d.m.Y'),
                'status' => $member->is_active ? 'Активен' : 'Неактивен',
            ];
        });
    }

    /**
     * Форматировать пол
     *
     * @param string|null $gender
     * @return string
     */
    private function formatGender(?string $gender): string
    {
        return match ($gender) {
            'male' => 'Мужской',
            'female' => 'Женский',
            default => '',
        };
    }

    /**
     * Форматировать email (скрыть временные)
     *
     * @param string|null $email
     * @return string
     */
    private function formatEmail(?string $email): string
    {
        if (!$email || str_starts_with($email, 'temp_')) {
            return '';
        }
        return $email;
    }

    /**
     * Создать простой CSV экспорт
     *
     * @param int $teamId
     * @return string CSV content
     */
    public function exportToCsv(int $teamId): string
    {
        $headers = $this->getHeaders();
        $data = $this->getTeamPlayersData($teamId);

        $output = fopen('php://temp', 'r+');
        
        // BOM для UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Заголовки
        fputcsv($output, $headers, ';');

        // Данные
        foreach ($data as $row) {
            fputcsv($output, [
                $row['last_name'],
                $row['first_name'],
                $row['middle_name'],
                $row['birth_date'],
                $row['gender'],
                $row['phone'],
                $row['email'],
                $row['position'],
                $row['dominant_foot'],
                $row['joined_at'],
                $row['status'],
            ], ';');
        }

        rewind($output);
        $content = stream_get_contents($output);
        fclose($output);

        return $content;
    }

    /**
     * Получить массив для Excel
     *
     * @param int $teamId
     * @return array
     */
    public function exportToArray(int $teamId): array
    {
        $headers = $this->getHeaders();
        $data = $this->getTeamPlayersData($teamId);

        $result = [$headers];

        foreach ($data as $row) {
            $result[] = [
                $row['last_name'],
                $row['first_name'],
                $row['middle_name'],
                $row['birth_date'],
                $row['gender'],
                $row['phone'],
                $row['email'],
                $row['position'],
                $row['dominant_foot'],
                $row['joined_at'],
                $row['status'],
            ];
        }

        return $result;
    }
}
