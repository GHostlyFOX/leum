<div class="container-fluid">
    <style>
        .form-section {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .info-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
        }
        .info-value {
            font-size: 1rem;
            color: #1f2937;
            font-weight: 500;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .status-active {
            background: #d1fae5;
            color: #065f46;
        }
        .status-completed {
            background: #e5e7eb;
            color: #374151;
        }
        .btn-primary-custom {
            background: #8fbd56;
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary-custom:hover {
            background: #6d9e3a;
            color: #fff;
            text-decoration: none;
        }
        .team-table {
            width: 100%;
            border-collapse: collapse;
        }
        .team-table thead {
            background: #f9fafb;
            border-bottom: 2px solid #e5e7eb;
        }
        .team-table th {
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 0.875rem;
            color: #6b7280;
            text-transform: uppercase;
        }
        .team-table td {
            padding: 16px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.95rem;
        }
        .team-table tbody tr:hover {
            background: #f9fafb;
        }
        .status-badge-table {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-participating {
            background: #d1fae5;
            color: #065f46;
        }
        .status-disqualified {
            background: #fee2e2;
            color: #991b1b;
        }
        .match-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .match-teams {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .match-team {
            flex: 1;
            text-align: right;
        }
        .match-team:last-child {
            text-align: left;
        }
        .team-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 0.95rem;
        }
        .match-score {
            font-size: 1.5rem;
            font-weight: 700;
            color: #8fbd56;
            padding: 0 12px;
        }
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }
        .empty-state-icon {
            font-size: 2.5rem;
            margin-bottom: 12px;
            opacity: 0.5;
        }
    </style>

    <!-- Page Header -->
    <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tournaments.index') }}">Турниры</a></li>
                    <li class="breadcrumb-item active">{{ $tournament?->name ?? 'Турнир' }}</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-3">
                <h1 class="page-title fw-bold mb-0">{{ $tournament?->name ?? 'Турнир' }}</h1>
                @php
                    $isActive = $tournament && $tournament->ends_at >= now()->date();
                @endphp
                @if($isActive)
                    <span class="status-badge status-active">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="12" r="3" fill="currentColor"></circle>
                        </svg>
                        Активный
                    </span>
                @else
                    <span class="status-badge status-completed">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Завершён
                    </span>
                @endif
            </div>
        </div>
        @if($canEdit)
            <a href="#" class="btn-primary-custom">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Редактировать
            </a>
        @endif
    </div>

    <!-- Info Card -->
    @if($tournament)
        <div class="info-card">
            <div class="info-row">
                <span class="info-label">Тип турнира</span>
                <span class="info-value">{{ $tournament->tournamentType?->name ?? 'Не указано' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Даты проведения</span>
                <span class="info-value">
                    {{ $tournament->starts_at?->format('d.m.Y') ?? 'Не указано' }} - {{ $tournament->ends_at?->format('d.m.Y') ?? 'Не указано' }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Организатор</span>
                <span class="info-value">{{ $tournament->organizer ?? 'Не указано' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Длительность тайма</span>
                <span class="info-value">{{ $tournament->half_duration_minutes ?? 0 }} минут</span>
            </div>
            <div class="info-row">
                <span class="info-label">Количество таймов</span>
                <span class="info-value">{{ $tournament->halves_count ?? 0 }}</span>
            </div>
        </div>
    @endif

    <!-- Teams Section -->
    <div class="form-section">
        <h5 class="fw-bold mb-4">
            <svg class="fe fe-users" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; margin-right: 8px; vertical-align: text-bottom;">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            Команды ({{ count($teams) }})
        </h5>

        @if(count($teams) > 0)
            <div style="overflow-x: auto;">
                <table class="team-table">
                    <thead>
                        <tr>
                            <th>Команда</th>
                            <th>Клуб</th>
                            <th>Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teams as $team)
                            <tr>
                                <td><strong>{{ $team['teamName'] }}</strong></td>
                                <td>{{ $team['clubName'] }}</td>
                                <td>
                                    @if($team['status'] === 'participating')
                                        <span class="status-badge-table status-participating">Участвует</span>
                                    @elseif($team['status'] === 'disqualified')
                                        <span class="status-badge-table status-disqualified">Дисквалифицирована</span>
                                    @else
                                        <span class="status-badge-table">{{ $team['status'] }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">👥</div>
                <p>Команды ещё не добавлены</p>
            </div>
        @endif
    </div>

    <!-- Matches Section -->
    <div class="form-section">
        <h5 class="fw-bold mb-4">
            <svg class="fe fe-activity" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; margin-right: 8px; vertical-align: text-bottom;">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
            </svg>
            Матчи ({{ count($matches) }})
        </h5>

        @if(count($matches) > 0)
            <div>
                @foreach($matches as $match)
                    <div class="match-card">
                        <div class="match-teams">
                            <div class="match-team">
                                <div class="team-name">{{ $match['homeTeam'] }}</div>
                            </div>
                            <div class="match-score">{{ $match['score'] }}</div>
                            <div class="match-team">
                                <div class="team-name">{{ $match['awayTeam'] }}</div>
                            </div>
                        </div>
                        <div style="font-size: 0.875rem; color: #6b7280;">
                            {{ $match['status'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">⚽</div>
                <p>Матчи ещё не запланированы</p>
            </div>
        @endif
    </div>
</div>
