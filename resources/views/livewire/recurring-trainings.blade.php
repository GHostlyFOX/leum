<div>
    <style>
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
        .btn-disabled {
            background: #d1d5db;
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
            cursor: not-allowed;
            opacity: 0.6;
        }
        .template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }
        .template-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid #8fbd56;
        }
        .template-header {
            margin-bottom: 16px;
        }
        .template-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 8px 0;
        }
        .template-schedule {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 12px 0;
        }
        .schedule-badge {
            background: #e0f2fe;
            color: #0369a1;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
        }
        .template-flags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 12px 0;
        }
        .flag-badge {
            background: #f3f4f6;
            color: #6b7280;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .status-active {
            background: #d1fae5;
            color: #065f46;
        }
        .status-inactive {
            background: #fee2e2;
            color: #7f1d1d;
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
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">Шаблоны тренировок</h1>
        </div>
        <div>
            <button disabled class="btn btn-disabled" title="Функция доступна в будущем">
                <i class="fe fe-plus"></i> Создать шаблон
            </button>
        </div>
    </div>

    @if(count($templates) > 0)
        <div class="template-grid">
            @foreach($templates as $template)
                <div class="template-card">
                    <div class="template-header">
                        <h3 class="template-title">{{ $template['team_name'] }}</h3>
                    </div>

                    <!-- Schedule -->
                    <div>
                        <label style="display: block; color: #9ca3af; font-size: 12px; margin-bottom: 8px; font-weight: 500;">Расписание</label>
                        <div class="template-schedule">
                            @if(is_array($template['schedule']))
                                @foreach($template['schedule'] as $day)
                                    <span class="schedule-badge">{{ $day }}</span>
                                @endforeach
                            @else
                                <span class="schedule-badge">{{ $template['schedule'] }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Status and Flags -->
                    <div class="template-flags">
                        @if($template['is_active'])
                            <span class="flag-badge status-active">
                                <i class="fe fe-check-circle"></i> Активен
                            </span>
                        @else
                            <span class="flag-badge status-inactive">
                                <i class="fe fe-x-circle"></i> Неактивен
                            </span>
                        @endif

                        @if($template['notify_parents'])
                            <span class="flag-badge">
                                <i class="fe fe-bell"></i> Уведомления
                            </span>
                        @endif

                        @if($template['require_rsvp'])
                            <span class="flag-badge">
                                <i class="fe fe-check-square"></i> RSVP
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fe fe-inbox"></i>
            </div>
            <p class="empty-text">Шаблоны тренировок пока не созданы</p>
        </div>
    @endif
</div>
