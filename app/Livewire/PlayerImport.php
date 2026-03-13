<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Services\Import\PlayerImportService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamMember;

#[Layout('layouts.app')]
class PlayerImport extends Component
{
    use WithFileUploads;

    public ?int $teamId = null;
    public $importFile = null;
    public bool $isImporting = false;
    public ?array $importResult = null;
    public bool $showPreview = false;
    public array $previewData = [];
    
    // Список команд пользователя
    public array $userTeams = [];

    public function mount()
    {
        $user = Auth::user();
        
        // Получаем команды где пользователь - админ или тренер
        $memberships = TeamMember::where('user_id', $user->id)
            ->whereIn('role_id', [7, 8, 11]) // admin, coach, assistant
            ->with('team.club')
            ->get();

        $this->userTeams = $memberships->map(fn($m) => [
            'id' => $m->team_id,
            'name' => $m->team->name,
            'club' => $m->team->club->name,
        ])->toArray();

        if (count($this->userTeams) === 1) {
            $this->teamId = $this->userTeams[0]['id'];
        }
    }

    public function updatedImportFile()
    {
        $this->validate([
            'importFile' => 'file|mimes:csv,xlsx,xls|max:5120',
            'teamId' => 'required|integer|exists:teams,id',
        ]);

        $this->showPreview = true;
        $this->loadPreview();
    }

    public function loadPreview()
    {
        if (!$this->importFile) {
            return;
        }

        $path = $this->importFile->getRealPath();
        $extension = $this->importFile->getClientOriginalExtension();

        if ($extension === 'csv') {
            $this->previewData = $this->readCsvPreview($path, 5); // Первые 5 строк
        } else {
            $this->previewData = [];
            $this->addError('importFile', 'Предпросмотр .xlsx требует установки maatwebsite/excel');
        }
    }

    public function importPlayers(PlayerImportService $importService)
    {
        $this->validate([
            'importFile' => 'required|file|mimes:csv,xlsx,xls|max:5120',
            'teamId' => 'required|integer|exists:teams,id',
        ]);

        $this->isImporting = true;

        try {
            $path = $this->importFile->getRealPath();
            $extension = $this->importFile->getClientOriginalExtension();
            $team = Team::find($this->teamId);

            if ($extension === 'csv') {
                $data = $this->readCsvFull($path);
            } else {
                // Для Excel используем maatwebsite/excel если установлен
                $this->addError('importFile', 'Формат .xlsx требует установки: composer require maatwebsite/excel');
                $this->isImporting = false;
                return;
            }

            if (empty($data)) {
                $this->addError('importFile', 'Файл пустой или имеет неверный формат');
                $this->isImporting = false;
                return;
            }

            // Пропускаем заголовок
            array_shift($data);

            $result = $importService->import($data, $this->teamId, $team->club_id);

            $this->importResult = $result;
            $this->showPreview = false;
            $this->importFile = null;

            if (count($result['success']) > 0) {
                $this->dispatch('notify', 
                    type: 'success', 
                    message: 'Импортировано игроков: ' . count($result['success'])
                );
            }

        } catch (\Exception $e) {
            $this->addError('importFile', 'Ошибка импорта: ' . $e->getMessage());
        }

        $this->isImporting = false;
    }

    public function downloadTemplate()
    {
        return response()->streamDownload(function () {
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

            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM
            fputcsv($output, $headers, ';');
            fputcsv($output, $example, ';');
            fclose($output);
        }, 'players_template.csv', [
            'Content-Type' => 'text/csv; charset=utf-8',
        ]);
    }

    public function resetImport()
    {
        $this->importFile = null;
        $this->importResult = null;
        $this->showPreview = false;
        $this->previewData = [];
        $this->resetErrorBag();
    }

    private function readCsvPreview(string $path, int $limit): array
    {
        $data = [];
        $handle = fopen($path, 'r');
        
        if (!$handle) {
            return [];
        }

        // Пропускаем BOM если есть
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $count = 0;
        while (($row = fgetcsv($handle, 0, ';')) !== false && $count < $limit) {
            $data[] = $row;
            $count++;
        }

        fclose($handle);
        return $data;
    }

    private function readCsvFull(string $path): array
    {
        $data = [];
        $handle = fopen($path, 'r');
        
        if (!$handle) {
            return [];
        }

        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            $data[] = $row;
        }

        fclose($handle);
        return $data;
    }

    public function render()
    {
        return view('livewire.player-import');
    }
}
