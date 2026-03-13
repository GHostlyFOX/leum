<?php

declare(strict_types=1);

namespace Modules\Team\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\Export\AttendanceExportService;
use App\Services\Export\PlayerExportService;
use App\Services\Import\PlayerImportService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Modules\Team\Models\Team;

class ImportExportController extends Controller
{
    public function __construct(
        private PlayerImportService $importService,
        private PlayerExportService $exportService,
        private AttendanceExportService $attendanceExportService
    ) {}

    /**
     * Импорт игроков из CSV/Excel
     *
     * POST /teams/{teamId}/players/import
     */
    public function importPlayers(Request $request, int $teamId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,xlsx,xls|max:5120', // max 5MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $team = Team::find($teamId);
        if (!$team) {
            return response()->json(['error' => 'Команда не найдена'], 404);
        }

        $this->authorize('update', $team);

        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            // Чтение CSV
            if ($extension === 'csv') {
                $data = $this->readCsv($file->getPathname());
            } else {
                // Для Excel - пока возвращаем ошибку, требуется maatwebsite/excel
                return response()->json([
                    'error' => 'Формат .xlsx поддерживается через библиотеку maatwebsite/excel. Установите: composer require maatwebsite/excel'
                ], 400);
            }

            if (empty($data)) {
                return response()->json(['error' => 'Файл пустой или имеет неверный формат'], 400);
            }

            // Пропускаем заголовок
            array_shift($data);

            $result = $this->importService->import($data, $teamId, $team->club_id);

            return response()->json([
                'success' => true,
                'message' => 'Импорт завершен',
                'total' => $result['total'],
                'imported' => count($result['success']),
                'errors_count' => count($result['errors']),
                'details' => $result,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Скачать шаблон для импорта
     *
     * GET /teams/players/import/template
     */
    public function downloadTemplate(): Response
    {
        $headers = [
            'Фамилия*',
            'Имя*',
            'Отчество',
            'Дата рождения (ДД.ММ.ГГГГ)',
            'Пол (male/female)',
            'Телефон',
            'Email',
            'Позиция (Вратарь/Защитник/Полузащитник/Нападающий)',
            'Рабочая нога (левая/правая/обе)',
            'ФИО родителя',
            'Телефон родителя',
            'Email родителя',
        ];

        $example = [
            'Иванов',
            'Петр',
            'Сергеевич',
            '15.05.2012',
            'male',
            '+79001234567',
            'player@example.com',
            'Нападающий',
            'правая',
            'Иванова Мария Петровна',
            '+79009876543',
            'parent@example.com',
        ];

        $output = fopen('php://temp', 'r+');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM
        fputcsv($output, $headers, ';');
        fputcsv($output, $example, ';');

        rewind($output);
        $content = stream_get_contents($output);
        fclose($output);

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="players_template.csv"',
        ]);
    }

    /**
     * Экспорт игроков команды
     *
     * GET /teams/{teamId}/players/export
     */
    public function exportPlayers(int $teamId): Response
    {
        $team = Team::find($teamId);
        if (!$team) {
            return response('Команда не найдена', 404);
        }

        $this->authorize('view', $team);

        $content = $this->exportService->exportToCsv($teamId);
        $filename = 'players_' . $team->name . '_' . now()->format('Y-m-d') . '.csv';

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Экспорт посещаемости
     *
     * GET /teams/{teamId}/attendance/export
     */
    public function exportAttendance(Request $request, int $teamId): Response
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 422);
        }

        $team = Team::find($teamId);
        if (!$team) {
            return response('Команда не найдена', 404);
        }

        $this->authorize('view', $team);

        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        $data = $this->attendanceExportService->exportTeamAttendance($teamId, $startDate, $endDate);
        $content = $this->attendanceExportService->toCsv($data);

        $filename = 'attendance_' . $team->name . '_' . $startDate->format('Y-m-d') . '.csv';

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Чтение CSV файла
     *
     * @param string $path
     * @return array
     */
    private function readCsv(string $path): array
    {
        $data = [];
        $handle = fopen($path, 'r');
        
        if (!$handle) {
            return [];
        }

        // Определение кодировки и конвертация
        $bom = fread($handle, 3);
        rewind($handle);
        
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            $data[] = $row;
        }

        fclose($handle);
        return $data;
    }
}
