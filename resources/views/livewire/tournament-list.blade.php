<div class="container-fluid">
    <style>
        .form-section {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            padding: 12px 16px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #8fbd56;
            box-shadow: 0 0 0 3px rgba(143, 189, 86, 0.1);
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
        .tournament-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            height: 100%;
        }
        .tournament-card:hover {
            border-color: #8fbd56;
            box-shadow: 0 4px 12px rgba(143, 189, 86, 0.15);
            transform: translateY(-2px);
        }
        .tournament-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            gap: 12px;
            margin-bottom: 12px;
        }
        .tournament-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }
        .tournament-type {
            font-size: 0.875rem;
            color: #6b7280;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
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
        .tournament-details {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
            margin: 16px 0;
            padding: 12px 0;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
        }
        .detail-label {
            color: #6b7280;
            min-width: 80px;
        }
        .detail-value {
            color: #1f2937;
            font-weight: 500;
        }
        .tournament-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding-top: 12px;
        }
        .stat-box {
            background: #f9fafb;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
        }
        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #8fbd56;
        }
        .stat-label {
            font-size: 0.75rem;
            color: #6b7280;
            text-transform: uppercase;
            margin-top: 4px;
        }
        .filter-bar {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }
        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }
        a.tournament-card {
            text-decoration: none;
            color: inherit;
        }
    </style>

    <!-- Page Header -->
    <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                    <li class="breadcrumb-item active">Турниры</li>
                </ol>
            </nav>
            <h1 class="page-title fw-bold">Турниры</h1>
        </div>
        <a href="{{ route('tournament.create') }}" class="btn-primary-custom">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Создать турнир
        </a>
    </div>

    <!-- Filter Bar -->
    @if(count($years) > 0)
        <div class="filter-bar">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Год</label>
                    <select wire:model.live="filterYear" class="form-select">
                        <option value="">Все годы</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endif

    <!-- Tournaments List -->
    @if($tournaments->count() > 0)
        <div class="row g-3">
            @foreach($tournaments as $tournament)
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('tournament.detail', $tournament->id) }}" class="tournament-card">
                        <div class="tournament-header">
                            <div>
                                <div class="tournament-title">{{ $tournament->name }}</div>
                                <div class="tournament-type">{{ $tournament->tournamentType?->name ?? 'Не указано' }}</div>
                            </div>
                            <div>
                                @php
                                    $isActive = $tournament->ends_at >= now()->date();
                                @endphp
                                @if($isActive)
                                    <span class="status-badge status-active">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <circle cx="12" cy="12" r="3" fill="currentColor"></circle>
                                        </svg>
                                        Активный
                                    </span>
                                @else
                                    <span class="status-badge status-completed">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        Завершён
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="tournament-details">
                            <div class="detail-item">
                                <span class="detail-label">Дата:</span>
                                <span class="detail-value">
                                    {{ $tournament->starts_at?->format('d.m.Y') ?? 'Не указано' }} - {{ $tournament->ends_at?->format('d.m.Y') ?? 'Не указано' }}
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Организатор:</span>
                                <span class="detail-value">{{ $tournament->organizer ?? 'Не указано' }}</span>
                            </div>
                        </div>

                        <div class="tournament-stats">
                            <div class="stat-box">
                                <div class="stat-number">{{ $tournament->tournamentTeams_count ?? $tournament->tournamentTeams->count() }}</div>
                                <div class="stat-label">Команд</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-number">{{ $tournament->matches_count ?? $tournament->matches->count() }}</div>
                                <div class="stat-label">Матчей</div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $tournaments->links() }}
        </div>
    @else
        <div class="form-section">
            <div class="empty-state">
                <div class="empty-state-icon">🏆</div>
                <h4 style="color: #1f2937; margin-bottom: 8px;">Нет турниров</h4>
                <p style="margin-bottom: 20px;">Турниры не найдены по выбранным фильтрам</p>
                <a href="{{ route('tournament.create') }}" class="btn-primary-custom">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Создать первый турнир
                </a>
            </div>
        </div>
    @endif
</div>
