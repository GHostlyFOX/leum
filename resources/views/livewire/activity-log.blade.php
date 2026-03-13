<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="page-title" style="font-size: 1.5rem; font-weight: 700; color: #1f2937;">
                Журнал активности
            </h2>
            <p class="text-secondary" style="color: #6b7280;">
                История действий пользователей в системе
            </p>
        </div>
    </div>

    <!-- Статистика -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px; text-align: center;">
                <div style="font-size: 2rem; font-weight: 700; color: #1f2937;">{{ $stats['total'] }}</div>
                <div style="color: #6b7280; font-size: 0.875rem;">Всего записей</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px; text-align: center;">
                <div style="font-size: 2rem; font-weight: 700; color: #8fbd56;">{{ $stats['today'] }}</div>
                <div style="color: #6b7280; font-size: 0.875rem;">Сегодня</div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card-custom" style="background: #f0fdf4; border: 1px solid #8fbd56; border-radius: 14px; padding: 20px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: 700; color: #22c55e;">{{ $stats['create'] }}</div>
                <div style="color: #6b7280; font-size: 0.75rem;">Создано</div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card-custom" style="background: #fef3c7; border: 1px solid #f59e0b; border-radius: 14px; padding: 20px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: 700; color: #f59e0b;">{{ $stats['update'] }}</div>
                <div style="color: #6b7280; font-size: 0.75rem;">Изменено</div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card-custom" style="background: #fef2f2; border: 1px solid #ef4444; border-radius: 14px; padding: 20px; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: 700; color: #ef4444;">{{ $stats['delete'] }}</div>
                <div style="color: #6b7280; font-size: 0.75rem;">Удалено</div>
            </div>
        </div>
    </div>

    <!-- Фильтры -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px;">
                <div style="display: flex; gap: 16px; flex-wrap: wrap; align-items: end;">
                    <div style="flex: 1; min-width: 150px;">
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 6px; font-size: 0.875rem;">Действие</label>
                        <select wire:model.live="actionFilter" style="width: 100%; padding: 8px 12px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 0.875rem;">
                            <option value="">Все</option>
                            @foreach($actions as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 6px; font-size: 0.875rem;">Пользователь</label>
                        <input type="text" wire:model.live="userFilter" placeholder="Имя или email..."
                            style="width: 100%; padding: 8px 12px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 0.875rem;">
                    </div>
                    <div style="flex: 1; min-width: 150px;">
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 6px; font-size: 0.875rem;">С</label>
                        <input type="date" wire:model.live="dateFrom"
                            style="width: 100%; padding: 8px 12px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 0.875rem;">
                    </div>
                    <div style="flex: 1; min-width: 150px;">
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 6px; font-size: 0.875rem;">По</label>
                        <input type="date" wire:model.live="dateTo"
                            style="width: 100%; padding: 8px 12px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 0.875rem;">
                    </div>
                    <div>
                        <button wire:click="resetFilters" 
                            style="padding: 8px 16px; background: #f8f9fa; border: 1.5px solid #e5e7eb; border-radius: 8px; color: #6b7280; font-weight: 600; cursor: pointer; font-size: 0.875rem;">
                            Сбросить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Таблица -->
    <div class="row">
        <div class="col-12">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px;">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f0fdf4;">
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #8fbd56; font-weight: 600; color: #1f2937; font-size: 0.875rem;">Дата/Время</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #8fbd56; font-weight: 600; color: #1f2937; font-size: 0.875rem;">Пользователь</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #8fbd56; font-weight: 600; color: #1f2937; font-size: 0.875rem;">Действие</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #8fbd56; font-weight: 600; color: #1f2937; font-size: 0.875rem;">Сущность</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #8fbd56; font-weight: 600; color: #1f2937; font-size: 0.875rem;">IP адрес</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 12px; font-size: 0.875rem; color: #6b7280;">
                                        {{ $log->created_at->format('d.m.Y H:i') }}
                                    </td>
                                    <td style="padding: 12px; font-size: 0.875rem;">
                                        @if($log->user)
                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                <div style="width: 28px; height: 28px; background: #8fbd56; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 12px; font-weight: 600;">
                                                    {{ substr($log->user->first_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div style="color: #1f2937; font-weight: 500;">{{ $log->user->full_name }}</div>
                                                    <div style="color: #6b7280; font-size: 0.75rem;">{{ $log->user->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span style="color: #9ca3af;">Система</span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px;">
                                        @php
                                            $badgeClass = match($log->action) {
                                                'create' => 'background: #dcfce7; color: #16a34a;',
                                                'update' => 'background: #fef3c7; color: #f59e0b;',
                                                'delete' => 'background: #fef2f2; color: #ef4444;',
                                                default => 'background: #f3f4f6; color: #6b7280;',
                                            };
                                        @endphp
                                        <span style="padding: 4px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 600; {{ $badgeClass }}">
                                            {{ $log->getActionLabel() }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px; font-size: 0.875rem; color: #1f2937;">
                                        {{ $log->getEntityLabel() }}
                                        @if($log->entity_id)
                                            <span style="color: #6b7280;">#{{ $log->entity_id }}</span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px; font-size: 0.875rem; color: #6b7280; font-family: monospace;">
                                        {{ $log->ip_address }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="padding: 40px; text-align: center; color: #6b7280;">
                                        <div class="icon-circle-light" style="width: 60px; height: 60px; border-radius: 50%; background: #f0fdf4; display: flex; align-items: center; justify-content: center; color: #8fbd56; margin: 0 auto 16px;">
                                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                            </svg>
                                        </div>
                                        <p>Нет записей для отображения</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 20px;">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
