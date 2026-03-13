<?php

declare(strict_types=1);

namespace Modules\Team\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\PDF\TournamentApplicationPdfGenerator;
use Illuminate\Http\Response;
use Modules\Team\Models\Team;
use Modules\Tournament\Models\Tournament;

class PdfController extends Controller
{
    public function __construct(
        private TournamentApplicationPdfGenerator $applicationGenerator
    ) {}

    /**
     * Заявочный лист для турнира
     *
     * GET /tournaments/{tournamentId}/teams/{teamId}/application.pdf
     */
    public function tournamentApplication(int $tournamentId, int $teamId): Response
    {
        $tournament = Tournament::find($tournamentId);
        if (!$tournament) {
            return response('Турнир не найден', 404);
        }

        $team = Team::find($teamId);
        if (!$team) {
            return response('Команда не найдена', 404);
        }

        $this->authorize('view', $team);

        $html = $this->applicationGenerator->generateApplicationHtml($tournamentId, $teamId);

        // Если dompdf установлен - генерируем PDF
        if (class_exists('Dompdf\Dompdf')) {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $filename = 'application_' . $team->name . '_' . $tournament->name . '.pdf';

            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        }

        // Иначе возвращаем HTML
        return response($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
        ]);
    }

    /**
     * Состав команды (PDF)
     *
     * GET /teams/{teamId}/roster.pdf
     */
    public function teamRoster(int $teamId): Response
    {
        $team = Team::with([
            'club',
            'members' => function ($query) {
                $query->where('role_id', 10)
                    ->where('is_active', true)
                    ->with('user.playerProfile.position');
            }
        ])->find($teamId);

        if (!$team) {
            return response('Команда не найдена', 404);
        }

        $this->authorize('view', $team);

        $html = $this->generateRosterHtml($team);

        if (class_exists('Dompdf\Dompdf')) {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $filename = 'roster_' . $team->name . '.pdf';

            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        }

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
        ]);
    }

    /**
     * Генерация HTML для состава команды
     *
     * @param Team $team
     * @return string
     */
    private function generateRosterHtml(Team $team): string
    {
        $players = $team->members->map(fn($m) => $m->user);

        $html = '
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
                font-size: 18pt;
                color: #1f2937;
                margin: 0 0 5px 0;
            }
            .info {
                margin-bottom: 20px;
                color: #6b7280;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th {
                background: #f0fdf4;
                border: 1px solid #8fbd56;
                padding: 8px;
                text-align: left;
                font-weight: bold;
            }
            td {
                border: 1px solid #e5e7eb;
                padding: 6px 8px;
            }
            tr:nth-child(even) {
                background: #f8f9fa;
            }
            .num { width: 5%; text-align: center; }
            .date { width: 15%; text-align: center; }
        </style>
        </head>
        <body>
            <div class="header">
                <h1>СОСТАВ КОМАНДЫ</h1>
                <div class="info">
                    ' . htmlspecialchars($team->club->name) . ' — ' . htmlspecialchars($team->name) . '<br>
                    ' . $team->birth_year . ' г.р.
                </div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th class="num">№</th>
                        <th>ФИО</th>
                        <th class="date">Дата рождения</th>
                        <th>Позиция</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($players as $index => $player) {
            $profile = $player->playerProfile;
            $html .= '
                    <tr>
                        <td class="num">' . ($index + 1) . '</td>
                        <td>' . htmlspecialchars($player->full_name) . '</td>
                        <td class="date">' . ($player->birth_date?->format('d.m.Y') ?? '') . '</td>
                        <td>' . htmlspecialchars($profile?->position?->name ?? '') . '</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>
            
            <div style="margin-top: 20px; text-align: right; color: #6b7280; font-size: 9pt;">
                Всего игроков: ' . $players->count() . '<br>
                Сформировано: ' . now()->format('d.m.Y H:i') . '
            </div>
        </body>
        </html>';

        return $html;
    }
}
