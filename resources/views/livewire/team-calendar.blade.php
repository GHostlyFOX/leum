<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="page-title" style="font-size: 1.5rem; font-weight: 700; color: #1f2937;">
                Календарь команды
            </h2>
            <p class="text-secondary" style="color: #6b7280;">
                Расписание тренировок и матчей
            </p>
        </div>
    </div>

    <!-- Выбор команды -->
    <div class="row mb-4">
        <div class="col-md-4">
            <select wire:model.change="teamId" class="form-control-custom" style="width: 100%; padding: 10px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 0.9rem;">
                <option value="">-- Выберите команду --</option>
                @foreach($userTeams as $team)
                    <option value="{{ $team['id'] }}">{{ $team['name'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if($teamId)
    <!-- Навигация по месяцам -->
    <div class="row mb-4">
        <div class="col-12">
            <div style="display: flex; justify-content: space-between; align-items: center; background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 16px 24px;">
                <button wire:click="previousMonth" style="background: none; border: none; color: #6b7280; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                    Предыдущий
                </button>
                <h4 style="margin: 0; color: #1f2937; font-weight: 600;">{{ $monthName }} {{ $year }}</h4>
                <button wire:click="nextMonth" style="background: none; border: none; color: #6b7280; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    Следующий
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Календарь -->
    <div class="row">
        <div class="col-12">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 24px;">
                <!-- Дни недели -->
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; margin-bottom: 8px;">
                    @foreach(['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'] as $dayName)
                        <div style="text-align: center; padding: 10px; font-weight: 600; color: #6b7280; font-size: 0.875rem;">
                            {{ $dayName }}
                        </div>
                    @endforeach
                </div>

                <!-- Дни месяца -->
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px;">
                    @foreach($days as $day)
                        <div style="min-height: 100px; padding: 8px; border-radius: 10px; background: {{ $day['isToday'] ? '#f0fdf4' : ($day['isCurrentMonth'] ? '#f8f9fa' : '#f3f4f6') }}; border: {{ $day['isToday'] ? '2px solid #8fbd56' : '1px solid transparent' }};">
                            <div style="text-align: right; margin-bottom: 4px;">
                                <span style="font-weight: {{ $day['isCurrentMonth'] ? '600' : '400' }}; color: {{ $day['isToday'] ? '#8fbd56' : ($day['isCurrentMonth'] ? '#1f2937' : '#9ca3af') }};">
                                    {{ $day['date']->format('j') }}
                                </span>
                            </div>
                            
                            @foreach($day['events'] as $event)
                                <a href="{{ $event['type'] === 'training' ? '/training/' . $event['id'] : '/match/' . $event['id'] }}" 
                                    style="display: block; padding: 4px 8px; margin-bottom: 4px; border-radius: 6px; font-size: 0.75rem; text-decoration: none; font-weight: 500;
                                    {{ $event['type'] === 'training' 
                                        ? 'background: #f0fdf4; color: #6d9e3a;' 
                                        : 'background: #fef3c7; color: #92400e;' }}">
                                    <span style="font-weight: 700;">{{ $event['time'] }}</span>
                                    {{ $event['type'] === 'training' ? 'Тренировка' : 'Матч' }}
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <!-- Легенда -->
                <div style="margin-top: 20px; display: flex; gap: 20px; justify-content: center;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 16px; height: 16px; background: #f0fdf4; border-radius: 4px;"></div>
                        <span style="font-size: 0.875rem; color: #6b7280;">Тренировка</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 16px; height: 16px; background: #fef3c7; border-radius: 4px;"></div>
                        <span style="font-size: 0.875rem; color: #6b7280;">Матч</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 60px; text-align: center;">
                <div style="width: 80px; height: 80px; background: #f0fdf4; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#8fbd56" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
                <h4 style="color: #1f2937; font-weight: 600; margin-bottom: 8px;">Выберите команду</h4>
                <p style="color: #6b7280; margin: 0;">Для просмотра календаря выберите команду из списка выше</p>
            </div>
        </div>
    </div>
    @endif
</div>
