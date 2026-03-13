<?php

declare(strict_types=1);

namespace App\Services\PDF;

use Modules\Team\Models\Team;
use Modules\Tournament\Models\Tournament;

class TournamentApplicationPdfGenerator
{
    /**
     * Генерация HTML для заявочного листа турнира
     *
     * @param int $tournamentId
     * @param int $teamId
     * @return string HTML
     */
    public function generateApplicationHtml(int $tournamentId, int $teamId): string
    {
        $tournament = Tournament::with('tournamentType')->findOrFail($tournamentId);
        $team = Team::with([
            'club',
            'members' => function ($query) {
                $query->where('role_id', 10) // player
                    ->where('is_active', true)
                    ->with('user.playerProfile.position');
            }
        ])->findOrFail($teamId);

        $players = $team->members->map(fn($m) => $m->user);
        $coaches = $team->members()
            ->whereIn('role_id', [8, 11]) // coach, assistant
            ->where('is_active', true)
            ->with('user')
            ->get()
            ->map(fn($m) => $m->user);

        $html = $this->getStyles();
        $html .= $this->renderHeader($tournament, $team);
        $html .= $this->renderCoaches($coaches);
        $html .= $this->renderPlayers($players);
        $html .= $this->renderFooter();

        return $html;
    }

    /**
     * CSS стили для PDF
     *
     * @return string
     */
    private function getStyles(): string
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
        <meta charset="UTF-8">
        <style>
            @page { margin: 15mm; }
            body { 
                font-family: DejaVu Sans, Arial, sans-serif; 
                font-size: 10pt; 
                line-height: 1.4;
                color: #333;
            }
            .header {
                text-align: center;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 2px solid #8fbd56;
            }
            .header h1 {
                font-size: 16pt;
                color: #1f2937;
                margin: 0 0 5px 0;
            }
            .header h2 {
                font-size: 12pt;
                color: #6b7280;
                margin: 0;
                font-weight: normal;
            }
            .info-block {
                margin-bottom: 15px;
            }
            .info-block table {
                width: 100%;
                border-collapse: collapse;
            }
            .info-block td {
                padding: 5px 10px;
                vertical-align: top;
            }
            .info-block td:first-child {
                width: 30%;
                color: #6b7280;
            }
            .section-title {
                font-size: 12pt;
                font-weight: bold;
                color: #1f2937;
                margin: 20px 0 10px 0;
                padding-bottom: 5px;
                border-bottom: 1px solid #e5e7eb;
            }
            table.players {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            table.players th {
                background: #f0fdf4;
                border: 1px solid #8fbd56;
                padding: 8px;
                text-align: left;
                font-weight: bold;
                color: #1f2937;
            }
            table.players td {
                border: 1px solid #e5e7eb;
                padding: 6px 8px;
            }
            table.players tr:nth-child(even) {
                background: #f8f9fa;
            }
            .num { width: 5%; text-align: center; }
            .fio { width: 35%; }
            .birth { width: 15%; text-align: center; }
            .position { width: 20%; }
            .number { width: 15%; text-align: center; }
            .signature { width: 20%; }
            .coaches-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            .coaches-table th {
                background: #fef3c7;
                border: 1px solid #f59e0b;
                padding: 8px;
                text-align: left;
            }
            .coaches-table td {
                border: 1px solid #e5e7eb;
                padding: 6px 8px;
            }
            .footer {
                margin-top: 30px;
                padding-top: 15px;
                border-top: 1px solid #e5e7eb;
            }
            .signature-block {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
            }
            .signature-item {
                width: 45%;
            }
            .signature-line {
                border-bottom: 1px solid #333;
                height: 30px;
                margin-top: 5px;
            }
            .date-generated {
                text-align: right;
                color: #6b7280;
                font-size: 9pt;
                margin-top: 20px;
            }
        </style>
        </head>
        <body>
        ';
    }

    /**
     * Шапка документа
     *
     * @param Tournament $tournament
     * @param Team $team
     * @return string
     */
    private function renderHeader(Tournament $tournament, Team $team): string
    {
        return '
        <div class="header">
            <h1>ЗАЯВОЧНЫЙ ЛИСТ</h1>
            <h2>' . htmlspecialchars($tournament->name) . '</h2>
        </div>
        
        <div class="info-block">
            <table>
                <tr>
                    <td>Клуб:</td>
                    <td><strong>' . htmlspecialchars($team->club->name) . '</strong></td>
                </tr>
                <tr>
                    <td>Команда:</td>
                    <td><strong>' . htmlspecialchars($team->name) . '</strong> (' . $team->birth_year . ' г.р.)</td>
                </tr>
                <tr>
                    <td>Турнир:</td>
                    <td>' . htmlspecialchars($tournament->name) . '</td>
                </tr>
                <tr>
                    <td>Дата проведения:</td>
                    <td>' . $tournament->starts_at->format('d.m.Y') . ' - ' . $tournament->ends_at->format('d.m.Y') . '</td>
                </tr>
                <tr>
                    <td>Вид спорта:</td>
                    <td>Футбол</td>
                </tr>
            </table>
        </div>
        ';
    }

    /**
     * Секция тренеров
     *
     * @param \Illuminate\Support\Collection $coaches
     * @return string
     */
    private function renderCoaches(\Illuminate\Support\Collection $coaches): string
    {
        $html = '<div class="section-title">Тренерский штаб</div>';
        $html .= '<table class="coaches-table">
            <thead>
                <tr>
                    <th style="width: 5%;">№</th>
                    <th style="width: 40%;">ФИО</th>
                    <th style="width: 30%;">Должность</th>
                    <th style="width: 25%;">Подпись</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($coaches as $index => $coach) {
            $role = $coach->pivot?->role_id === 8 ? 'Главный тренер' : 'Ассистент';
            $html .= '
                <tr>
                    <td style="text-align: center;">' . ($index + 1) . '</td>
                    <td>' . htmlspecialchars($coach->full_name) . '</td>
                    <td>' . $role . '</td>
                    <td></td>
                </tr>';
        }

        $html .= '</tbody></table>';
        return $html;
    }

    /**
     * Секция игроков
     *
     * @param \Illuminate\Support\Collection $players
     * @return string
     */
    private function renderPlayers(\Illuminate\Support\Collection $players): string
    {
        $html = '<div class="section-title">Состав команды</div>';
        $html .= '<table class="players">
            <thead>
                <tr>
                    <th class="num">№</th>
                    <th class="fio">Фамилия, Имя</th>
                    <th class="birth">Дата рождения</th>
                    <th class="position">Позиция</th>
                    <th class="number">Номер</th>
                    <th class="signature">Подпись</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($players as $index => $player) {
            $profile = $player->playerProfile;
            $html .= '
                <tr>
                    <td class="num">' . ($index + 1) . '</td>
                    <td class="fio">' . htmlspecialchars($player->last_name . ' ' . $player->first_name) . '</td>
                    <td class="birth">' . ($player->birth_date?->format('d.m.Y') ?? '') . '</td>
                    <td class="position">' . htmlspecialchars($profile?->position?->name ?? '') . '</td>
                    <td class="number"></td>
                    <td class="signature"></td>
                </tr>';
        }

        // Добавляем пустые строки для ручного заполнения
        for ($i = $players->count() + 1; $i <= 25; $i++) {
            $html .= '
                <tr>
                    <td class="num">' . $i . '</td>
                    <td class="fio"></td>
                    <td class="birth"></td>
                    <td class="position"></td>
                    <td class="number"></td>
                    <td class="signature"></td>
                </tr>';
        }

        $html .= '</tbody></table>';
        
        $html .= '
        <div style="margin-top: 10px; font-size: 9pt; color: #6b7280;">
            Всего игроков в заявке: ' . $players->count() . '
        </div>';
        
        return $html;
    }

    /**
     * Подвал документа
     *
     * @return string
     */
    private function renderFooter(): string
    {
        return '
        <div class="footer">
            <div class="signature-block">
                <div class="signature-item">
                    <div>Представитель команды:</div>
                    <div class="signature-line"></div>
                    <div style="font-size: 9pt; color: #6b7280; margin-top: 3px;">(подпись, дата)</div>
                </div>
                <div class="signature-item">
                    <div>Председатель судейской коллегии:</div>
                    <div class="signature-line"></div>
                    <div style="font-size: 9pt; color: #6b7280; margin-top: 3px;">(подпись, дата)</div>
                </div>
            </div>
            
            <div class="date-generated">
                Документ сформирован: ' . now()->format('d.m.Y H:i') . '
            </div>
        </div>
        </body>
        </html>';
    }
}
