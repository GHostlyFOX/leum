<div>
    <style>
        .match-card {
            background: #fff;
            border: 0;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 16px;
            transition: all 0.2s;
            cursor: pointer;
        }
        .match-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        .match-teams {
            font-weight: 600;
            font-size: 16px;
            color: #1f2937;
            margin-bottom: 8px;
        }
        .match-datetime {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 12px;
        }
        .match-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }
        .match-venue {
            font-size: 13px;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .badge-match-type {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
        }
        .badge-friendly {
            background: #dbeafe;
            color: #1e40af;
        }
        .badge-tournament {
            background: #fed7aa;
            color: #92400e;
        }
        .badge-status {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            margin-left: auto;
        }
        .badge-upcoming {
            background: #cffafe;
            color: #0c4a6e;
        }
        .badge-live {
            background: #fee2e2;
            color: #7f1d1d;
            animation: blink 1s infinite;
        }
        @keyframes blink {
            0%, 50%, 100% { opacity: 1; }
            25%, 75% { opacity: 0.7; }
        }
        .badge-finished {
            background: #e5e7eb;
            color: #374151;
        }
        .badge-missed {
            background: #fef3c7;
            color: #78350f;
        }
        .filter-bar {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .filter-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        .filter-label {
            display: block;
            font-weight: 600;
            font-size: 13px;
            color: #374151;
            margin-bottom: 6px;
        }
        .filter-control {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            color: #374151;
        }
        .filter-control:focus {
            outline: none;
            border-color: #8fbd56;
            box-shadow: 0 0 0 3px rgba(143, 189, 86, 0.1);
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }
        .btn-create {
            background: #8fbd56;
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }
        .btn-create:hover {
            background: #6d9e3a;
            text-decoration: none;
            color: #fff;
        }
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: #6b7280;
        }
        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 16px;
            color: #d1d5db;
        }
        .empty-state-text {
            font-size: 16px;
            color: #374151;
            margin-bottom: 8px;
        }
        .match-score {
            font-weight: 700;
            font-size: 18px;
            color: #1f2937;
            margin-left: auto;
            padding-left: 16px;
        }
        .pagination {
            margin-top: 24px;
            justify-content: center;
        }
        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
            }
            .filter-group {
                min-width: 100%;
            }
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Матчи</h1>
        <a href="{{ route('match.create') }}" class="btn-create">
            <i class="fe fe-plus"></i>
            Создать матч
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-row">
            <div class="filter-group">
                <label class="filter-label">Команда</label>
                <select wire:model.live="filterTeamId" class="filter-control">
                    <option value="">Все команды</option>
                    @foreach($teams as $team)
                        <option value="{{ $team['id'] }}">{{ $team['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Тип матча</label>
                <select wire:model.live="filterType" class="filter-control">
                    <option value="all">Все типы</option>
                    <option value="friendly">Дружеский</option>
                    <option value="tournament">Турнир</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">От даты</label>
                <input
                    type="date"
                    wire:model.live="filterDateFrom"
                    class="filter-control"
                />
            </div>

            <div class="filter-group">
                <label class="filter-label">До даты</label>
                <input
                    type="date"
                    wire:model.live="filterDateTo"
                    class="filter-control"
                />
            </div>
        </div>
    </div>

    <!-- Matches List -->
    @if($matches->count() > 0)
        <div>
            @foreach($matches as $match)
                <a href="{{ route('match.detail', $match->id) }}" style="text-decoration: none; color: inherit;">
                    <div class="match-card">
                        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 16px;">
                            <div style="flex: 1;">
                                <div class="match-teams">
                                    {{ $match->team->name ?? 'Команда' }}
                                    <span style="color: #9ca3af; font-weight: 400;">vs</span>
                                    {{ $match->opponentTeam?->name ?? $match->opponent?->name ?? 'Соперник' }}
                                </div>

                                <div class="match-datetime">
                                    <i class="fe fe-calendar" style="margin-right: 4px;"></i>
                                    {{ $match->scheduled_at->format('d.m.Y H:i') }}
                                </div>

                                <div class="match-meta">
                                    @if($match->venue)
                                        <span class="match-venue">
                                            <i class="fe fe-map-pin"></i>
                                            {{ $match->venue->name }}
                                        </span>
                                    @endif

                                    <span class="badge-match-type @if($match->match_type === 'tournament') badge-tournament @else badge-friendly @endif">
                                        @if($match->match_type === 'tournament')
                                            Турнир
                                            @if($match->tournament)
                                                - {{ $match->tournament->name }}
                                            @endif
                                        @else
                                            Дружеский
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div style="text-align: right;">
                                @php
                                    $now = now();
                                    $isUpcoming = $match->scheduled_at > $now && !$match->actual_start_at;
                                    $isLive = $match->actual_start_at && !$match->actual_end_at;
                                    $isFinished = $match->actual_end_at;
                                    $isMissed = $match->scheduled_at < $now && !$match->actual_start_at;
                                @endphp

                                @if($isLive)
                                    <span class="badge-status badge-live">LIVE</span>
                                @elseif($isFinished)
                                    <span class="badge-status badge-finished">Завершён</span>
                                @elseif($isUpcoming)
                                    <span class="badge-status badge-upcoming">Предстоит</span>
                                @elseif($isMissed)
                                    <span class="badge-status badge-missed">Пропущен</span>
                                @endif

                                @if($isFinished && $match->events()->count() > 0)
                                    <div class="match-score">
                                        @php
                                            $homeGoals = $match->events()
                                                ->where('event_type', 'goal')
                                                ->where('team_id', $match->team_id)
                                                ->count();
                                            $awayGoals = $match->events()
                                                ->where('event_type', 'goal')
                                                ->where('team_id', '!=', $match->team_id)
                                                ->count();
                                        @endphp
                                        {{ $homeGoals }} - {{ $awayGoals }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination">
            {{ $matches->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fe fe-inbox"></i>
            </div>
            <div class="empty-state-text">Матчи не найдены</div>
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 20px;">
                Попробуйте изменить фильтры или создайте новый матч
            </p>
            <a href="{{ route('match.create') }}" class="btn-create">
                <i class="fe fe-plus"></i>
                Создать матч
            </a>
        </div>
    @endif
</div>
