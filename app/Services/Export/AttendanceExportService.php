<?php

declare(strict_types=1);

namespace App\Services\Export;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\Team\Models\Team;
use Modules\Training\Models\Training;

class AttendanceExportService
{
    /**
     * Экспорт посещаемости за период
     *
     * @param int $teamId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function exportTeamAttendance(int $teamId, Carbon $startDate, Carbon $endDate): array
    {
        $team = Team::with([
            'members' => function ($query) {
                $query->where('role_id', 10) // player
                    ->with('user');
            }
        ])->findOrFail($teamId);

        $trainings = Training::where('team_id', $teamId)
            ->whereBetween('training_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->orderBy('training_date')
            ->with('attendance.user')
            ->get();

        $players = $team->members->map(fn($m) => $m->user)->keyBy('id');

        // Заголовки: ФИО + даты тренировок
        $headers = ['Фамилия', 'Имя'];
        foreach ($trainings as $training) {
            $headers[] = $training->training_date->format('d.m');
        }
        $headers[] = 'Всего';
        $headers[] = '%';

        // Данные по каждому игроку
        $rows = [];
        foreach ($players as $player) {
            $row = [
                $player->last_name,
                $player->first_name,
            ];

            $presentCount = 0;
            $totalCount = 0;

            foreach ($trainings as $training) {
                $attendance = $training->attendance->firstWhere('player_user_id', $player->id);
                $status = $attendance?->status ?? 'pending';
                
                $row[] = $this->formatAttendanceStatus($status);
                
                if ($status === 'present') {
                    $presentCount++;
                }
                if (in_array($status, ['present', 'absent'])) {
                    $totalCount++;
                }
            }

            $percentage = $totalCount > 0 ? round(($presentCount / $totalCount) * 100) : 0;
            
            $row[] = $presentCount . '/' . $totalCount;
            $row[] = $percentage . '%';

            $rows[] = $row;
        }

        // Добавляем итоговую строку
        $totalRow = ['', 'Итого присутствовало:'];
        foreach ($trainings as $training) {
            $present = $training->attendance->where('status', 'present')->count();
            $total = $training->attendance->whereIn('status', ['present', 'absent'])->count();
            $totalRow[] = $present . '/' . $total;
        }
        $totalRow[] = '';
        $totalRow[] = '';
        $rows[] = $totalRow;

        return [
            'headers' => $headers,
            'rows' => $rows,
            'period' => $startDate->format('d.m.Y') . ' - ' . $endDate->format('d.m.Y'),
            'team_name' => $team->name,
        ];
    }

    /**
     * Форматировать статус посещаемости
     *
     * @param string $status
     * @return string
     */
    private function formatAttendanceStatus(string $status): string
    {
        return match ($status) {
            'present' => '+',
            'absent' => '-',
            'pending' => '?',
            default => '?',
        };
    }

    /**
     * Конвертировать в CSV
     *
     * @param array $data
     * @return string
     */
    public function toCsv(array $data): string
    {
        $output = fopen('php://temp', 'r+');
        
        // BOM для UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Инфо о периоде
        fputcsv($output, ['Команда:', $data['team_name']], ';');
        fputcsv($output, ['Период:', $data['period']], ';');
        fputcsv($output, [], ';');
        
        // Заголовки
        fputcsv($output, $data['headers'], ';');

        // Данные
        foreach ($data['rows'] as $row) {
            fputcsv($output, $row, ';');
        }

        rewind($output);
        $content = stream_get_contents($output);
        fclose($output);

        return $content;
    }
}
