@extends('club::layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('club.index') }}">Клуб</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('club.teams') }}">Команды</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $team->name }}</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" 
                     style="width: 56px; height: 56px; background: {{ $team->team_color ?? '#8fbd56' }};">
                    <span class="text-white fw-bold fs-4">{{ mb_strtoupper(mb_substr($team->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h1 class="page-title fw-bold mb-1">{{ $team->name }}</h1>
                    <p class="text-muted mb-0">{{ $team->birth_year }} г.р. • {{ $team->gender === 'male' ? 'Мужская' : 'Женская' }}</p>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('club.teams') }}" class="btn btn-outline-secondary">
                <i class="fe fe-arrow-left me-1"></i> Назад
            </a>
            <button wire:click="$dispatch('open-invite-modal', { teamId: {{ $team->id }} })" class="btn btn-success">
                <i class="fe fe-user-plus me-1"></i> Пригласить игрока
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 50px; height: 50px; background: #f0fdf4; color: #8fbd56;">
                        <i class="fe fe-users fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $players->count() ?? 0 }}</h3>
                        <small class="text-muted">Игроков</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 50px; height: 50px; background: #eff6ff; color: #3b82f6;">
                        <i class="fe fe-user-check fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $coaches->count() ?? 0 }}</h3>
                        <small class="text-muted">Тренеров</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 50px; height: 50px; background: #fef3c7; color: #f59e0b;">
                        <i class="fe fe-calendar fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $trainingsThisWeek ?? 0 }}</h3>
                        <small class="text-muted">Тренировок на неделе</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 50px; height: 50px; background: #fdf2f8; color: #ec4899;">
                        <i class="fe fe-trophy fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $upcomingMatches ?? 0 }}</h3>
                        <small class="text-muted">Предстоящих игр</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="teamTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="players-tab" data-bs-toggle="tab" data-bs-target="#players" type="button" role="tab">
                <i class="fe fe-users me-2"></i>Состав
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="coaches-tab" data-bs-toggle="tab" data-bs-target="#coaches" type="button" role="tab">
                <i class="fe fe-user-check me-2"></i>Тренеры
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule" type="button" role="tab">
                <i class="fe fe-calendar me-2"></i>Расписание
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="announcements-tab" data-bs-toggle="tab" data-bs-target="#announcements" type="button" role="tab">
                <i class="fe fe-bell me-2"></i>Объявления
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tournaments-tab" data-bs-toggle="tab" data-bs-target="#tournaments" type="button" role="tab">
                <i class="fe fe-trophy me-2"></i>Турниры
            </button>
        </li>
    </ul>

    <div class="tab-content" id="teamTabsContent">
        <!-- Players Tab -->
        <div class="tab-pane fade show active" id="players" role="tabpanel">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Состав команды</h5>
                    @if($players->count() > 0)
                        <span class="badge bg-success">{{ $players->count() }} игроков</span>
                    @endif
                </div>
                <div class="card-body px-4">
                    @if($players->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="fe fe-users fs-1 mb-3 d-block opacity-25"></i>
                            <h5>В команде пока нет игроков</h5>
                            <p class="mb-3">Пригласите игроков, чтобы начать работу с командой</p>
                            <button class="btn btn-success" wire:click="$dispatch('open-invite-modal', { teamId: {{ $team->id }} })">
                                <i class="fe fe-user-plus me-2"></i>Пригласить игроков
                            </button>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Игрок</th>
                                        <th>Роль</th>
                                        <th>В команде с</th>
                                        <th>Статус</th>
                                        <th class="text-end">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($players as $member)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px; background: linear-gradient(135deg, #8fbd56 0%, #6d9e3a 100%); color: #fff; font-weight: 600;">
                                                        {{ mb_strtoupper(mb_substr($member->user?->first_name ?? '?', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $member->user?->full_name ?? 'Неизвестно' }}</div>
                                                        <small class="text-muted">{{ $member->user?->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ match($member->role_id) {
                                                        6 => 'Игрок',
                                                        9 => 'Родитель',
                                                        10 => 'Ассистент',
                                                        default => 'Участник'
                                                    } }}
                                                </span>
                                            </td>
                                            <td>{{ $member->joined_at?->format('d.m.Y') ?? '-' }}</td>
                                            <td>
                                                @if($member->is_active)
                                                    <span class="badge bg-success">Активен</span>
                                                @else
                                                    <span class="badge bg-secondary">Неактивен</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        Действия
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="#"><i class="fe fe-user me-2"></i>Профиль</a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('team.member.remove', [$team->id, $member->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Исключить игрока из команды?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fe fe-user-x me-2"></i>Исключить
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Coaches Tab -->
        <div class="tab-pane fade" id="coaches" role="tabpanel">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">Тренерский штаб</h5>
                </div>
                <div class="card-body px-4">
                    @if($coaches->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="fe fe-user-check fs-1 mb-3 d-block opacity-25"></i>
                            <h5>Нет назначенных тренеров</h5>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($coaches as $coach)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100" style="border-radius: 12px; border: 1px solid #e5e7eb;">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 56px; height: 56px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #fff; font-weight: 600; font-size: 1.2rem;">
                                                    {{ mb_strtoupper(mb_substr($coach->user?->first_name ?? '?', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-1">{{ $coach->user?->full_name ?? 'Неизвестно' }}</h6>
                                                    <span class="badge {{ $coach->role_id === 8 ? 'bg-primary' : 'bg-info' }}">
                                                        {{ $coach->role_id === 8 ? 'Главный тренер' : 'Помощник' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-muted small">
                                                <div class="mb-1"><i class="fe fe-mail me-2"></i>{{ $coach->user?->email ?? '-' }}</div>
                                                <div><i class="fe fe-calendar me-2"></i>В команде с {{ $coach->joined_at?->format('d.m.Y') ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Schedule Tab -->
        <div class="tab-pane fade" id="schedule" role="tabpanel">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Расписание на неделю</h5>
                    <a href="{{ url('trainings') }}" class="btn btn-sm btn-outline-success">Все тренировки</a>
                </div>
                <div class="card-body px-4">
                    @if(empty($weekTrainings) || $weekTrainings->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="fe fe-calendar fs-1 mb-3 d-block opacity-25"></i>
                            <h5>Нет тренировок на этой неделе</h5>
                            <a href="{{ url('trainings/create') }}" class="btn btn-success mt-2">Запланировать тренировку</a>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($weekTrainings as $training)
                                <div class="list-group-item px-0 py-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center" 
                                             style="width: 48px; height: 48px; background: #f0fdf4; color: #8fbd56;">
                                            <i class="fe fe-clock fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">{{ $training->title ?? 'Тренировка' }}</h6>
                                            <small class="text-muted">
                                                <i class="fe fe-calendar me-1"></i>{{ $training->start_time?->format('d.m.Y H:i') ?? '-' }}
                                                <span class="mx-2">|</span>
                                                <i class="fe fe-map-pin me-1"></i>{{ $training->venue?->name ?? 'Место не указано' }}
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge bg-success">Запланировано</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Announcements Tab -->
        <div class="tab-pane fade" id="announcements" role="tabpanel">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Объявления</h5>
                    <a href="{{ url('announcements/create') }}" class="btn btn-sm btn-success">
                        <i class="fe fe-plus me-1"></i>Новое объявление
                    </a>
                </div>
                <div class="card-body px-4">
                    @if(empty($announcements) || $announcements->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="fe fe-bell fs-1 mb-3 d-block opacity-25"></i>
                            <h5>Нет объявлений</h5>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($announcements as $announcement)
                                <div class="list-group-item px-0 py-3">
                                    <h6 class="fw-bold mb-1">{{ $announcement->title }}</h6>
                                    <p class="text-muted mb-1">{{ Str::limit($announcement->content, 150) }}</p>
                                    <small class="text-muted">{{ $announcement->created_at?->diffForHumans() }}</small>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tournaments Tab -->
        <div class="tab-pane fade" id="tournaments" role="tabpanel">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Предстоящие события</h5>
                    <a href="{{ url('tournaments') }}" class="btn btn-sm btn-outline-success">Все турниры</a>
                </div>
                <div class="card-body px-4">
                    @if(empty($upcomingEvents) || $upcomingEvents->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="fe fe-trophy fs-1 mb-3 d-block opacity-25"></i>
                            <h5>Нет предстоящих событий</h5>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($upcomingEvents as $event)
                                <div class="list-group-item px-0 py-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center" 
                                             style="width: 48px; height: 48px; background: #fef3c7; color: #f59e0b;">
                                            <i class="fe fe-trophy fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">{{ $event->name ?? $event->title ?? 'Событие' }}</h6>
                                            <small class="text-muted">
                                                <i class="fe fe-calendar me-1"></i>{{ $event->start_date?->format('d.m.Y') ?? '-' }}
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge bg-warning">Предстоит</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize tabs
    const triggerTabList = document.querySelectorAll('#teamTabs button');
    triggerTabList.forEach(triggerEl => {
        const tabTrigger = new bootstrap.Tab(triggerEl);
        triggerEl.addEventListener('click', event => {
            event.preventDefault();
            tabTrigger.show();
        });
    });
</script>
@endpush
@endsection
