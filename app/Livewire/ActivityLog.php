<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\ActivityLog as ActivityLogModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ActivityLog extends Component
{
    use WithPagination;

    public string $actionFilter = '';
    public string $userFilter = '';
    public string $dateFrom = '';
    public string $dateTo = '';

    protected $queryString = ['actionFilter', 'userFilter', 'dateFrom', 'dateTo'];

    public function mount()
    {
        // Проверяем права администратора
        $user = Auth::user();
        if (!$user->hasPermission('users.view')) {
            return redirect()->route('home');
        }

        $this->dateFrom = now()->subDays(7)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function resetFilters()
    {
        $this->reset(['actionFilter', 'userFilter', 'dateFrom', 'dateTo']);
        $this->dateFrom = now()->subDays(7)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function render()
    {
        $query = ActivityLogModel::with('user')
            ->orderBy('created_at', 'desc');

        if ($this->actionFilter) {
            $query->where('action', $this->actionFilter);
        }

        if ($this->userFilter) {
            $query->whereHas('user', function ($q) {
                $q->where('first_name', 'ilike', '%' . $this->userFilter . '%')
                  ->orWhere('last_name', 'ilike', '%' . $this->userFilter . '%')
                  ->orWhere('email', 'ilike', '%' . $this->userFilter . '%');
            });
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $logs = $query->paginate(20);

        // Статистика
        $stats = [
            'total' => ActivityLogModel::count(),
            'today' => ActivityLogModel::whereDate('created_at', today())->count(),
            'create' => ActivityLogModel::where('action', 'create')->count(),
            'update' => ActivityLogModel::where('action', 'update')->count(),
            'delete' => ActivityLogModel::where('action', 'delete')->count(),
        ];

        // Действия для фильтра
        $actions = [
            'create' => 'Создание',
            'update' => 'Изменение',
            'delete' => 'Удаление',
            'login' => 'Вход',
            'export' => 'Экспорт',
            'import' => 'Импорт',
        ];

        return view('livewire.activity-log', [
            'logs' => $logs,
            'stats' => $stats,
            'actions' => $actions,
        ]);
    }
}
