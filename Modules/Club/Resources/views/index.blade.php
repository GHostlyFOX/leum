@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h1 class="page-title fw-bold">{{ $club->name }}</h1>
            <p class="text-muted mb-0">Главная страница клуба</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('club.seasons') }}" class="btn btn-outline-primary">
                <i class="fe fe-calendar me-1"></i> Сезоны
            </a>
            <a href="{{ route('club.add') }}" class="btn btn-primary">
                <i class="fe fe-plus me-1"></i> Добавить команду
            </a>
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
                        <h3 class="fw-bold mb-0">{{ $teams->count() }}</h3>
                        <small class="text-muted">Команд</small>
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
                        <h3 class="fw-bold mb-0">{{ $seasons->count() }}</h3>
                        <small class="text-muted">Активных сезонов</small>
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
                        <h3 class="fw-bold mb-0">{{ $coaches->count() }}</h3>
                        <small class="text-muted">Тренеров</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 50px; height: 50px; background: #fdf2f8; color: #ec4899;">
                        <i class="fe fe-map-pin fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $venues->count() }}</h3>
                        <small class="text-muted">Площадок</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ЗАЯВКИ НА ВСТУПЛЕНИЕ --}}
    @if(isset($club) && $club)
        @livewire('pending-requests', ['clubId' => $club->id, 'viewMode' => 'club'])
    @endif

    <div class="row g-4">
        <!-- Teams Section -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        <i class="fe fe-users text-success me-2"></i>Команды
                    </h5>
                    <a href="{{ route('club.add') }}" class="btn btn-sm btn-outline-success">Все команды</a>
                </div>
                <div class="card-body px-4">
                    @if($teams->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="fe fe-users fs-1 mb-2 d-block opacity-25"></i>
                            <p>Нет команд</p>
                            <a href="{{ route('club.add') }}" class="btn btn-sm btn-success">Создать команду</a>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($teams as $team)
                                <a href="{{ route('club.team.show', $team->id) }}" class="list-group-item px-0 py-3 d-flex align-items-center justify-content-between text-decoration-none" style="color: inherit;">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 44px; height: 44px; background: #8fbd56; color: #fff; font-weight: 600;">
                                            {{ substr($team->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0">{{ $team->name }}</h6>
                                            <small class="text-muted">{{ $team->birth_year }} г.р.</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-light text-dark">
                                            <i class="fe fe-user me-1"></i>{{ $team->members_count }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Active Seasons Section -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        <i class="fe fe-calendar text-warning me-2"></i>Активные сезоны
                    </h5>
                    <a href="{{ route('club.seasons') }}" class="btn btn-sm btn-outline-warning">Все сезоны</a>
                </div>
                <div class="card-body px-4">
                    @if($seasons->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="fe fe-calendar fs-1 mb-2 d-block opacity-25"></i>
                            <p>Нет активных сезонов</p>
                            <a href="{{ route('club.seasons') }}" class="btn btn-sm btn-warning">Создать сезон</a>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($seasons as $season)
                                <div class="list-group-item px-0 py-3">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="fw-bold mb-0">{{ $season->name }}</h6>
                                        <span class="badge bg-success">Активен</span>
                                    </div>
                                    <p class="text-muted mb-0 small">
                                        <i class="fe fe-clock me-1"></i>
                                        {{ $season->start_date?->format('d.m.Y') }} — {{ $season->end_date?->format('d.m.Y') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Coaches Section -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fe fe-user-check text-primary me-2"></i>Тренеры
                    </h5>
                </div>
                <div class="card-body px-4">
                    @if($coaches->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="fe fe-user-check fs-1 mb-2 d-block opacity-25"></i>
                            <p>Нет тренеров</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($coaches as $coach)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 rounded-3" style="background: #f8fafc;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 44px; height: 44px; background: #3b82f6; color: #fff;">
                                            <i class="fe fe-user"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0">{{ $coach->user?->first_name }} {{ $coach->user?->last_name }}</h6>
                                            <small class="text-muted">{{ $coach->team?->name }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Venues Section -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        <i class="fe fe-map-pin text-danger me-2"></i>Площадки
                    </h5>
                    <a href="{{ url('venues') }}" class="btn btn-sm btn-outline-danger">Все площадки</a>
                </div>
                <div class="card-body px-4">
                    @if($venues->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="fe fe-map-pin fs-1 mb-2 d-block opacity-25"></i>
                            <p>Нет площадок</p>
                            <a href="{{ url('venues/create') }}" class="btn btn-sm btn-danger">Добавить площадку</a>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($venues as $venue)
                                <div class="list-group-item px-0 py-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px; background: #fce7f3; color: #ec4899;">
                                            <i class="fe fe-map-pin"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0">{{ $venue->name }}</h6>
                                            <small class="text-muted">{{ $venue->address }}</small>
                                        </div>
                                    </div>
                                    @if($venue->is_indoor)
                                        <span class="badge bg-info">В помещении</span>
                                    @else
                                        <span class="badge bg-secondary">Открытая</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
