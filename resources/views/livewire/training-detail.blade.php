<div class="container-fluid">
    @if($training)
    <div class="row mb-4">
        <div class="col-12">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <a href="/dashboard" style="color: #6b7280; text-decoration: none;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </a>
                <span style="color: #6b7280;">/</span>
                <a href="/club/team/{{ $training->team_id }}" style="color: #6b7280; text-decoration: none;">{{ $training->team->name }}</a>
                <span style="color: #6b7280;">/</span>
                <span style="color: #1f2937;">Тренировка</span>
            </div>
            <h2 class="page-title" style="font-size: 1.5rem; font-weight: 700; color: #1f2937;">
                Тренировка {{ $training->training_date->format('d.m.Y') }}
            </h2>
        </div>
    </div>

    <div class="row">
        <!-- Информация о тренировке -->
        <div class="col-md-4 mb-4">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 24px;">
                <h5 style="margin: 0 0 20px 0; color: #1f2937; font-weight: 600;">Информация</h5>
                
                <div style="margin-bottom: 16px;">
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 4px;">Дата и время</div>
                    <div style="color: #1f2937; font-weight: 600;">
                        {{ $training->training_date->format('d.m.Y') }} в {{ $training->start_time->format('H:i') }}
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 4px;">Длительность</div>
                    <div style="color: #1f2937; font-weight: 600;">{{ $training->duration_minutes }} минут</div>
                </div>

                <div style="margin-bottom: 16px;">
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 4px;">Место проведения</div>
                    <div style="color: #1f2937; font-weight: 600;">{{ $training->venue?->name ?? 'Не указано' }}</div>
                </div>

                <div style="margin-bottom: 16px;">
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 4px;">Тренер</div>
                    <div style="color: #1f2937; font-weight: 600;">{{ $training->coach->full_name }}</div>
                </div>

                <div style="margin-bottom: 16px;">
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 4px;">Тип тренировки</div>
                    <div style="color: #1f2937; font-weight: 600;">{{ $training->trainingType?->name ?? 'Обычная' }}</div>
                </div>

                <div>
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 4px;">Статус</div>
                    <div>
                        @if($training->status === 'scheduled')
                            <span style="padding: 4px 12px; background: #fef3c7; color: #92400e; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">Запланирована</span>
                        @elseif($training->status === 'completed')
                            <span style="padding: 4px 12px; background: #dcfce7; color: #16a34a; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">Проведена</span>
                        @else
                            <span style="padding: 4px 12px; background: #fef2f2; color: #ef4444; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">Отменена</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Посещаемость -->
        <div class="col-md-8 mb-4">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 24px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 style="margin: 0; color: #1f2937; font-weight: 600;">Посещаемость</h5>
                    <div style="display: flex; gap: 8px;">
                        <span style="padding: 4px 12px; background: #dcfce7; color: #16a34a; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">
                            Присутствовало: {{ collect($attendance)->where('status', 'present')->count() }}
                        </span>
                        <span style="padding: 4px 12px; background: #fef2f2; color: #ef4444; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">
                            Отсутствовало: {{ collect($attendance)->where('status', 'absent')->count() }}
                        </span>
                    </div>
                </div>

                @if(empty($attendance))
                    <div style="text-align: center; padding: 40px; color: #6b7280;">
                        <p>Список посещаемости пуст</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: #f0fdf4;">
                                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #8fbd56; font-weight: 600; color: #1f2937;">Игрок</th>
                                    <th style="padding: 12px; text-align: center; border-bottom: 2px solid #8fbd56; font-weight: 600; color: #1f2937; width: 120px;">Статус</th>
                                    @if($canEdit)
                                        <th style="padding: 12px; text-align: center; border-bottom: 2px solid #8fbd56; font-weight: 600; color: #1f2937; width: 200px;">Действия</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendance as $item)
                                    <tr style="border-bottom: 1px solid #e5e7eb;">
                                        <td style="padding: 12px;">
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <div style="width: 32px; height: 32px; border-radius: 50%; background: #8fbd56; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: 12px;">
                                                    {{ substr($item['name'], 0, 1) }}
                                                </div>
                                                <span style="color: #1f2937; font-weight: 500;">{{ $item['name'] }}</span>
                                            </div>
                                        </td>
                                        <td style="padding: 12px; text-align: center;">
                                            @if($item['status'] === 'present')
                                                <span style="padding: 4px 12px; background: #dcfce7; color: #16a34a; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">Присутствовал</span>
                                            @elseif($item['status'] === 'absent')
                                                <span style="padding: 4px 12px; background: #fef2f2; color: #ef4444; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">Отсутствовал</span>
                                            @else
                                                <span style="padding: 4px 12px; background: #fef3c7; color: #92400e; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">Ожидается</span>
                                            @endif
                                        </td>
                                        @if($canEdit)
                                            <td style="padding: 12px; text-align: center;">
                                                <div style="display: flex; gap: 8px; justify-content: center;">
                                                    <button wire:click="updateAttendance({{ $item['id'] }}, 'present')"
                                                        style="padding: 6px 12px; background: #dcfce7; border: none; border-radius: 6px; color: #16a34a; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                                                        Был
                                                    </button>
                                                    <button wire:click="updateAttendance({{ $item['id'] }}, 'absent')"
                                                        style="padding: 6px 12px; background: #fef2f2; border: none; border-radius: 6px; color: #ef4444; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                                                        Не был
                                                    </button>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
