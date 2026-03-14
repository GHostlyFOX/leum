<div>
    <style>
        .filter-bar {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            align-items: flex-end;
        }
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            display: block;
        }
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            padding: 12px 16px;
            width: 100%;
        }
        .form-control:focus, .form-select:focus {
            border-color: #8fbd56;
            box-shadow: 0 0 0 3px rgba(143, 189, 86, 0.1);
        }
        .training-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid #8fbd56;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .training-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        .training-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .training-datetime {
            font-weight: 600;
            color: #111827;
            font-size: 16px;
        }
        .training-time {
            color: #6b7280;
            font-size: 14px;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }
        .status-scheduled {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-cancelled {
            background-color: #fee2e2;
            color: #7f1d1d;
        }
        .training-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            font-size: 14px;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        .detail-label {
            color: #9ca3af;
            font-weight: 500;
            margin-bottom: 4px;
        }
        .detail-value {
            color: #374151;
            font-weight: 500;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .empty-icon {
            font-size: 48px;
            color: #d1d5db;
            margin-bottom: 16px;
        }
        .empty-text {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 24px;
        }
        .btn-create {
            background: #8fbd56;
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-create:hover {
            background: #6d9e3a;
            color: #fff;
            text-decoration: none;
        }
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">Расписание тренировок</h1>
        </div>
        <div>
            <a href="{{ url('trainings/create') }}" class="btn btn-create">
                <i class="fe fe-plus"></i> Создать тренировку
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-row">
            <div>
                <label class="form-label">Команда</label>
                <select wire:model.live="filterTeamId" class="form-select">
                    <option value="">Все команды</option>
                    @foreach($teams as $team)
                        <option value="{{ $team['id'] }}">{{ $team['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Статус</label>
                <select wire:model.live="filterStatus" class="form-select">
                    <option value="">Все</option>
                    <option value="scheduled">Запланированные</option>
                    <option value="completed">Завершённые</option>
                    <option value="cancelled">Отменённые</option>
                </select>
            </div>
            <div>
                <label class="form-label">Дата от</label>
                <input type="date" wire:model.live="filterDateFrom" class="form-control">
            </div>
            <div>
                <label class="form-label">Дата до</label>
                <input type="date" wire:model.live="filterDateTo" class="form-control">
            </div>
        </div>
    </div>

    <!-- Trainings List -->
    <div>
        @forelse($trainings as $training)
            <a href="{{ url('training/' . $training->id) }}" class="training-card">
                <div class="training-header">
                    <div>
                        <div class="training-datetime">
                            <i class="fe fe-calendar"></i>
                            {{ $training->training_date->format('d.m.Y') }}
                        </div>
                        <div class="training-time">
                            <i class="fe fe-clock"></i>
                            {{ $training->start_time }}
                        </div>
                    </div>
                    <div>
                        @if($training->status === 'scheduled')
                            <span class="status-badge status-scheduled">Запланирована</span>
                        @elseif($training->status === 'completed')
                            <span class="status-badge status-completed">Завершена</span>
                        @elseif($training->status === 'cancelled')
                            <span class="status-badge status-cancelled">Отменена</span>
                        @endif
                    </div>
                </div>
                <div class="training-details">
                    <div class="detail-item">
                        <span class="detail-label">Команда</span>
                        <span class="detail-value">{{ $training->team->name }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Место проведения</span>
                        <span class="detail-value">{{ $training->venue->name ?? '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Тренер</span>
                        <span class="detail-value">{{ $training->coach->first_name ?? '' }} {{ $training->coach->last_name ?? '' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Длительность</span>
                        <span class="detail-value">{{ $training->duration_minutes }} мин</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fe fe-inbox"></i>
                </div>
                <p class="empty-text">Тренировки не найдены</p>
                <a href="{{ url('trainings/create') }}" class="btn btn-create">
                    <i class="fe fe-plus"></i> Создать тренировку
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($trainings->count() > 0)
        <div class="mt-4">
            {{ $trainings->links() }}
        </div>
    @endif
</div>
