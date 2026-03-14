<div>
    <style>
        .calendar-container {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .calendar-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .calendar-nav-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }
        .calendar-btn {
            background: #f3f4f6;
            border: none;
            color: #6b7280;
            font-weight: 600;
            padding: 10px 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .calendar-btn:hover {
            background: #e5e7eb;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }
        .calendar-header {
            background: #f9fafb;
            padding: 12px;
            text-align: center;
            font-weight: 600;
            color: #374151;
            font-size: 12px;
        }
        .calendar-cell {
            background: #fff;
            padding: 12px;
            min-height: 100px;
            position: relative;
        }
        .calendar-cell.other-month {
            background: #f9fafb;
        }
        .calendar-cell.today {
            background: #f0fdf4;
            border: 2px solid #8fbd56;
        }
        .calendar-day {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .calendar-cell.other-month .calendar-day {
            color: #d1d5db;
        }
        .calendar-trainings {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .training-item {
            background: #e0f2fe;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            color: #0369a1;
            cursor: pointer;
            text-decoration: none;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            transition: all 0.2s ease;
        }
        .training-item:hover {
            background: #7dd3fc;
            color: #00308f;
        }
        .training-time {
            font-weight: 600;
        }
        .training-team {
            font-size: 10px;
        }
        @media (max-width: 768px) {
            .calendar-grid {
                font-size: 12px;
            }
            .calendar-cell {
                min-height: 80px;
                padding: 8px;
            }
            .training-item {
                font-size: 10px;
            }
        }
    </style>

    <div class="calendar-container">
        <!-- Calendar Navigation -->
        <div class="calendar-nav">
            <button wire:click="previousMonth" class="calendar-btn">
                <i class="fe fe-chevron-left"></i> Назад
            </button>
            <h2 class="calendar-nav-title">{{ $monthName }} {{ $currentYear }}</h2>
            <button wire:click="nextMonth" class="calendar-btn">
                Вперёд <i class="fe fe-chevron-right"></i>
            </button>
        </div>

        <!-- Calendar Grid -->
        <div class="calendar-grid">
            <!-- Day Headers -->
            <div class="calendar-header">Пн</div>
            <div class="calendar-header">Вт</div>
            <div class="calendar-header">Ср</div>
            <div class="calendar-header">Чт</div>
            <div class="calendar-header">Пт</div>
            <div class="calendar-header">Сб</div>
            <div class="calendar-header">Вс</div>

            <!-- Calendar Days -->
            @foreach($calendarDays as $day)
                <div class="calendar-cell @if(!$day['isCurrentMonth']) other-month @endif @if($day['isToday']) today @endif">
                    @if($day['isCurrentMonth'])
                        <div class="calendar-day">{{ $day['day'] }}</div>
                        @if(!empty($day['trainings']))
                            <div class="calendar-trainings">
                                @foreach($day['trainings'] as $training)
                                    <a href="{{ url('training/' . $training['id']) }}" class="training-item" title="{{ $training['team_name'] }}">
                                        <span class="training-time">{{ $training['start_time'] }}</span>
                                        <span class="training-team">{{ substr($training['team_name'], 0, 15) }}@if(strlen($training['team_name']) > 15)...@endif</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="calendar-day">{{ $day['day'] }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
