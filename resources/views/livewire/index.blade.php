<div>
<style>
    .onboarding-header {
        background: linear-gradient(135deg, #8fbd56 0%, #6d9e3a 100%);
        border-radius: 16px 16px 0 0;
        padding: 24px;
        color: #fff;
        position: relative;
    }
    .onboarding-header h5 { font-weight: 700; margin-bottom: 4px; }
    .onboarding-header .progress {
        height: 6px;
        border-radius: 3px;
        background: rgba(255,255,255,0.3);
        margin-top: 12px;
    }
    .onboarding-header .progress-bar {
        background: #fff;
        border-radius: 3px;
    }
    .onboarding-header .btn-close-onboarding {
        position: absolute;
        top: 16px;
        right: 16px;
    }
    .onboarding-header .btn-close-onboarding button {
        background: rgba(255,255,255,0.2);
        border: none;
        color: #fff;
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: background 0.2s;
    }
    .onboarding-header .btn-close-onboarding button:hover {
        background: rgba(255,255,255,0.35);
    }
    .onboarding-step {
        padding: 20px 24px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }
    .onboarding-step:last-child { border-bottom: none; }
    .onboarding-step .step-icon {
        width: 40px; height: 40px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .step-icon.done { background: #8fbd56; color: #fff; }
    .step-icon.pending { background: #f3f4f6; color: #8fbd56; }
    .onboarding-step .step-body { flex: 1; }
    .onboarding-step .step-body h6 {
        font-weight: 600; font-size: 0.95rem; margin-bottom: 2px; color: #1f2937;
    }
    .onboarding-step .step-body h6 .badge-done {
        background: #f0fdf4; color: #6d9e3a;
        font-size: 0.75rem; font-weight: 600;
        padding: 2px 8px; border-radius: 6px; margin-left: 8px;
    }
    .onboarding-step .step-body p {
        font-size: 0.85rem; color: #6b7280; margin: 0;
    }
    .onboarding-step .step-body h6.done-title { color: #6d9e3a; }
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #9ca3af;
    }
    .empty-state h6 { color: #374151; font-weight: 600; margin-bottom: 4px; }
    .empty-state p { font-size: 0.85rem; margin: 0; }
    .team-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 18px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        transition: all 0.2s;
        text-decoration: none;
        color: inherit;
    }
    .team-card:hover { border-color: #8fbd56; background: #fafdf5; }
    .team-card .team-avatar {
        width: 44px; height: 44px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1.1rem; color: #fff;
    }
    .team-card .team-info { flex: 1; }
    .team-card .team-info h6 { font-weight: 600; margin: 0; font-size: 0.95rem; }
    .team-card .team-info small { color: #6b7280; font-size: 0.8rem; }
    .btn-invite {
        background: #8fbd56; border: none; color: #fff;
        font-weight: 600; padding: 8px 20px; border-radius: 10px; font-size: 0.85rem;
    }
    .btn-invite:hover { background: #6d9e3a; color: #fff; }
    .btn-create-event {
        background: #8fbd56; border: none; color: #fff;
        font-weight: 600; padding: 8px 20px; border-radius: 10px; font-size: 0.85rem;
    }
    .btn-create-event:hover { background: #6d9e3a; color: #fff; }
    .btn-import {
        background: #fff; border: 1.5px solid #8fbd56; color: #6d9e3a;
        font-weight: 600; padding: 8px 20px; border-radius: 10px; font-size: 0.85rem;
    }
    .btn-import:hover { background: #f0fdf4; }
    
    /* Quick action cards */
    .quick-action-card {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .quick-action-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1) !important;
    }
</style>

<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <p class="text-muted mb-0">{{ $greeting }} 👋</p>
        <h1 class="page-title fw-bold">{{ $user->first_name }}</h1>
    </div>
</div>

@if ($role === 'admin')

    {{-- БЫСТРЫЕ ДЕЙСТВИЯ --}}
    <div class="mb-4">
        <h5 class="fw-bold mb-3">Быстрые действия</h5>
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <a href="{{ url('trainings/create') }}" class="text-decoration-none quick-action-card">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                        <div class="card-body text-center p-3">
                            <div style="width: 48px; height: 48px; background: #e8f5d6; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4a7a25" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <h6 class="fw-semibold mb-1" style="font-size: 0.9rem; color: #1f2937;">Тренировка</h6>
                            <small class="text-muted">Запланировать</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ url('matches/create') }}" class="text-decoration-none quick-action-card">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                        <div class="card-body text-center p-3">
                            <div style="width: 48px; height: 48px; background: #dbeafe; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                </svg>
                            </div>
                            <h6 class="fw-semibold mb-1" style="font-size: 0.9rem; color: #1f2937;">Матч</h6>
                            <small class="text-muted">Создать матч</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ url('club/teams') }}" class="text-decoration-none quick-action-card">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                        <div class="card-body text-center p-3">
                            <div style="width: 48px; height: 48px; background: #fef3c7; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <h6 class="fw-semibold mb-1" style="font-size: 0.9rem; color: #1f2937;">Игрок</h6>
                            <small class="text-muted">Добавить в команду</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <button wire:click="openInviteModal" class="btn p-0 w-100 h-100 text-start quick-action-card" style="background: none; border: none;">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                        <div class="card-body text-center p-3">
                            <div style="width: 48px; height: 48px; background: #fce7f3; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#db2777" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </div>
                            <h6 class="fw-semibold mb-1" style="font-size: 0.9rem; color: #1f2937;">Приглашение</h6>
                            <small class="text-muted">Отправить ссылку</small>
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </div>

    {{-- ОНБОРДИНГ-ЧЕКЛИСТ --}}
    @if ($showOnboarding && $completedSteps < $totalSteps)
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">
        <div class="onboarding-header">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                </div>
                <div>
                    <h5 class="mb-0">Добро пожаловать! Давайте настроим</h5>
                    <span style="opacity: 0.8; font-size: 0.9rem;">{{ $completedSteps }} из {{ $totalSteps }} шагов выполнено</span>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar" style="width: {{ ($completedSteps / $totalSteps) * 100 }}%"></div>
            </div>
            <div class="btn-close-onboarding">
                <button wire:click="dismissOnboarding" title="Скрыть">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
        </div>

        {{-- Шаг 1: Создать сезон --}}
        <div class="onboarding-step" style="{{ $hasSeason ? 'background: #f0fdf4;' : '' }}">
            <div class="step-icon {{ $hasSeason ? 'done' : 'pending' }}">
                @if($hasSeason)
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                @else
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                @endif
            </div>
            <div class="step-body">
                <h6 class="{{ $hasSeason ? 'done-title' : '' }}">Создайте первый сезон @if($hasSeason)<span class="badge-done">Готово!</span>@endif</h6>
                <p>Настройте сезон с датами начала и окончания для организации расписания.</p>
                @unless($hasSeason)<button wire:click="openSeasonModal" class="btn btn-sm btn-create-event mt-2">Создать сезон</button>@endunless
            </div>
        </div>

        {{-- Шаг 2: Добавить команду --}}
        <div class="onboarding-step" style="{{ $hasTeams ? 'background: #f0fdf4;' : '' }}">
            <div class="step-icon {{ $hasTeams ? 'done' : 'pending' }}">
                @if($hasTeams)
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                @else
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                @endif
            </div>
            <div class="step-body">
                <h6 class="{{ $hasTeams ? 'done-title' : '' }}">Добавьте команду @if($hasTeams)<span class="badge-done">Готово!</span>@endif</h6>
                <p>Создайте команды внутри клуба для управления составами и расписанием.</p>
                @unless($hasTeams)<a href="{{ url('club/teams') }}" class="btn btn-sm btn-create-event mt-2">Добавить команду</a>@endunless
            </div>
        </div>

        {{-- Шаг 3: Пригласить участников --}}
        <div class="onboarding-step" style="{{ $hasMembers ? 'background: #f0fdf4;' : '' }}">
            <div class="step-icon {{ $hasMembers ? 'done' : 'pending' }}">
                @if($hasMembers)
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                @else
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                @endif
            </div>
            <div class="step-body d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <h6 class="{{ $hasMembers ? 'done-title' : '' }}">Пригласите участников @if($hasMembers)<span class="badge-done">Готово!</span>@endif</h6>
                    <p>Добавьте тренеров и игроков в команды по email или поделитесь ссылкой.</p>
                </div>
                @unless($hasMembers)
                    @if($teams->isNotEmpty())
                        <button wire:click="openInviteModal({{ $teams->first()->id }})" class="btn btn-invite mt-2 mt-md-0">Пригласить</button>
                    @endif
                @endunless
            </div>
        </div>

        {{-- Шаг 4: Создать событие --}}
        <div class="onboarding-step" style="{{ $hasEvents ? 'background: #f0fdf4;' : '' }}">
            <div class="step-icon {{ $hasEvents ? 'done' : 'pending' }}">
                @if($hasEvents)
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                @else
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                @endif
            </div>
            <div class="step-body">
                <h6 class="{{ $hasEvents ? 'done-title' : '' }}">Запланируйте первое событие @if($hasEvents)<span class="badge-done">Готово!</span>@endif</h6>
                <p>Создайте тренировку или матч вручную, или импортируйте расписание.</p>
                @unless($hasEvents)
                    <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
                        <button wire:click="openCreateEventModal" class="btn btn-sm btn-create-event">Создать событие</button>
                        <span class="text-muted">или</span>
                        <a href="{{ url('trainings/recurring') }}" class="btn btn-sm btn-import">Импорт расписания</a>
                    </div>
                @endunless
            </div>
        </div>
    </div>
    @endif

    {{-- ЗАЯВКИ НА ВСТУПЛЕНИЕ --}}
    @livewire('pending-requests', ['viewMode' => 'dashboard'])

    {{-- БЛИЖАЙШИЕ МАТЧИ + ОБЪЯВЛЕНИЯ --}}
    <div class="row mb-4">
        <div class="col-lg-8 col-md-12 mb-4 mb-lg-0">
            <h5 class="fw-bold mb-3">Ближайшие матчи</h5>
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body">
                    <div class="empty-state">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" class="mb-3"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <h6>Нет предстоящих матчей</h6>
                        <p>Запланируйте первый матч, чтобы начать.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <h5 class="fw-bold mb-3">Объявления</h5>
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body">
                    <div class="empty-state">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" class="mb-3"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        <h6>Нет объявлений</h6>
                        <p>Новости команды появятся здесь.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ТРЕНИРОВКИ НА ЭТОЙ НЕДЕЛЕ --}}
    <div class="mb-4">
        <h5 class="fw-bold mb-3">Тренировки на этой неделе</h5>
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body">
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" class="mb-3"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <h6>Нет тренировок на этой неделе</h6>
                    <p>Расписание тренировок появится здесь.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ВАШИ КОМАНДЫ --}}
    @if($teams->isNotEmpty())
    <div class="mb-4">
        <h5 class="fw-bold mb-3">Ваши команды</h5>
        <div class="row g-3">
            @foreach($teams as $team)
                <div class="col-lg-4 col-md-6 col-12">
                    <a href="{{ route('club.team.show', $team->id) }}" class="team-card" style="text-decoration: none; color: inherit;">
                        <div class="team-avatar" style="background: {{ $team->team_color ?? '#8fbd56' }};">
                            {{ mb_strtoupper(mb_substr($team->name, 0, 1)) }}
                        </div>
                        <div class="team-info">
                            <h6>{{ $team->name }}</h6>
                            <small>{{ $club->name ?? '' }}</small>
                            <div class="mt-1">
                                <span class="badge bg-light text-dark">
                                    <i class="fe fe-user me-1"></i>{{ $team->members_count ?? 0 }} игр.
                                </span>
                            </div>
                        </div>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Модальное окно приглашений --}}
    @if($showInviteModal)
        @livewire('invite-modal', ['teamId' => $inviteTeamId, 'role' => $inviteRole], key('invite-modal-' . ($inviteTeamId ?? '0') . '-' . ($inviteRole ?? 'null')))
    @endif

    {{-- Модальное окно создания сезона (из онбординга) --}}
    @if($showSeasonModal)
    <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 1050; display: flex; align-items: center; justify-content: center;" wire:click.self="closeSeasonModal">
        <div style="background: #fff; border-radius: 16px; width: 100%; max-width: 480px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); overflow: hidden; animation: inviteSlideUp 0.25s ease-out;" @click.stop>
            <div style="padding: 20px 24px 0; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="font-weight: 700; font-size: 1.15rem; margin: 0;">Новый сезон</h5>
                <button wire:click="closeSeasonModal" style="background: none; border: none; color: #9ca3af; cursor: pointer;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div style="padding: 20px 24px;">
                <div class="mb-3">
                    <label style="font-weight: 600; font-size: 0.88rem; color: #374151; display: block; margin-bottom: 6px;">Название сезона</label>
                    <input type="text" class="form-control" wire:model="seasonName" placeholder="Сезон 2025/2026" style="border-radius: 10px; border: 1.5px solid #e5e7eb; padding: 10px 14px;">
                    @error('seasonName') <span class="text-danger" style="font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label style="font-weight: 600; font-size: 0.88rem; color: #374151; display: block; margin-bottom: 6px;">Дата начала</label>
                        <input type="date" class="form-control" wire:model="seasonStartDate" style="border-radius: 10px; border: 1.5px solid #e5e7eb; padding: 10px 14px;">
                        @error('seasonStartDate') <span class="text-danger" style="font-size: 0.8rem;">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-6">
                        <label style="font-weight: 600; font-size: 0.88rem; color: #374151; display: block; margin-bottom: 6px;">Дата окончания</label>
                        <input type="date" class="form-control" wire:model="seasonEndDate" style="border-radius: 10px; border: 1.5px solid #e5e7eb; padding: 10px 14px;">
                        @error('seasonEndDate') <span class="text-danger" style="font-size: 0.8rem;">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mb-0">
                    <label style="font-weight: 600; font-size: 0.88rem; color: #374151; display: block; margin-bottom: 6px;">Статус</label>
                    <select class="form-select" wire:model="seasonStatus" style="border-radius: 10px; border: 1.5px solid #e5e7eb; padding: 10px 14px;">
                        <option value="planned">Запланирован</option>
                        <option value="active">Активный</option>
                    </select>
                </div>
            </div>
            <div style="padding: 16px 24px 20px; display: flex; gap: 10px;">
                <button wire:click="closeSeasonModal" style="flex: 1; background: #fff; border: 1.5px solid #e5e7eb; color: #374151; font-weight: 600; padding: 10px; border-radius: 10px; cursor: pointer; font-size: 0.9rem;">Отмена</button>
                <button wire:click="createSeason" wire:loading.attr="disabled" style="flex: 1; background: #6366f1; border: none; color: #fff; font-weight: 600; padding: 10px; border-radius: 10px; cursor: pointer; font-size: 0.9rem;">
                    <span wire:loading.remove wire:target="createSeason">Создать</span>
                    <span wire:loading wire:target="createSeason">Создание...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Модальное окно создания события --}}
    @if($showCreateEventModal)
    <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 1050; display: flex; align-items: center; justify-content: center;" wire:click.self="closeCreateEventModal">
        <div style="background: #fff; border-radius: 16px; width: 100%; max-width: 480px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); overflow: hidden; animation: inviteSlideUp 0.25s ease-out;" @click.stop>
            <div style="padding: 20px 24px 0; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="font-weight: 700; font-size: 1.15rem; margin: 0;">Создать событие</h5>
                <button wire:click="closeCreateEventModal" style="background: none; border: none; color: #9ca3af; cursor: pointer;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div style="padding: 20px 24px;">
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 16px;">Выберите тип события:</p>
                
                <div class="d-grid gap-3">
                    {{-- Объявление --}}
                    <button wire:click="selectEventType('announcement')" 
                            class="btn text-start p-3 {{ $selectedEventType === 'announcement' ? 'border-2 border-success' : 'border' }}" 
                            style="border-radius: 12px; background: {{ $selectedEventType === 'announcement' ? '#f0fdf4' : '#fff' }}; border-color: {{ $selectedEventType === 'announcement' ? '#8fbd56' : '#e5e7eb' }};">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 44px; height: 44px; background: #fef3c7; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                </svg>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1" style="color: #1f2937;">Объявление</h6>
                                <small style="color: #6b7280;">Опубликовать новость для команды</small>
                            </div>
                        </div>
                    </button>

                    {{-- Тренировка --}}
                    <button wire:click="selectEventType('training')" 
                            class="btn text-start p-3 {{ $selectedEventType === 'training' ? 'border-2 border-success' : 'border' }}" 
                            style="border-radius: 12px; background: {{ $selectedEventType === 'training' ? '#f0fdf4' : '#fff' }}; border-color: {{ $selectedEventType === 'training' ? '#8fbd56' : '#e5e7eb' }};">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 44px; height: 44px; background: #e8f5d6; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#4a7a25" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1" style="color: #1f2937;">Тренировка</h6>
                                <small style="color: #6b7280;">Запланировать тренировку</small>
                            </div>
                        </div>
                    </button>

                    {{-- Матч --}}
                    <button wire:click="selectEventType('match')" 
                            class="btn text-start p-3 {{ $selectedEventType === 'match' ? 'border-2 border-success' : 'border' }}" 
                            style="border-radius: 12px; background: {{ $selectedEventType === 'match' ? '#f0fdf4' : '#fff' }}; border-color: {{ $selectedEventType === 'match' ? '#8fbd56' : '#e5e7eb' }};">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 44px; height: 44px; background: #dbeafe; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                </svg>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1" style="color: #1f2937;">Матч</h6>
                                <small style="color: #6b7280;">Создать игру или турнир</small>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
            <div style="padding: 16px 24px 20px; display: flex; gap: 10px;">
                <button wire:click="closeCreateEventModal" style="flex: 1; background: #fff; border: 1.5px solid #e5e7eb; color: #374151; font-weight: 600; padding: 10px; border-radius: 10px; cursor: pointer; font-size: 0.9rem;">Отмена</button>
                <button wire:click="createEvent" wire:loading.attr="disabled" disabled="{{ empty($selectedEventType) ? 'disabled' : '' }}" style="flex: 1; background: #8fbd56; border: none; color: #fff; font-weight: 600; padding: 10px; border-radius: 10px; cursor: {{ empty($selectedEventType) ? 'not-allowed' : 'pointer' }}; font-size: 0.9rem; opacity: {{ empty($selectedEventType) ? '0.6' : '1' }};">
                    <span wire:loading.remove wire:target="createEvent">Продолжить</span>
                    <span wire:loading wire:target="createEvent">Создание...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

@elseif ($role === 'coach')
    {{-- ═══════════════════════════════════════════════════════════════
         ДАШБОРД ТРЕНЕРА
    ═══════════════════════════════════════════════════════════════ --}}

    {{-- Быстрые действия --}}
    <div class="mb-4">
        <h5 class="fw-bold mb-3">Быстрые действия</h5>
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <a href="{{ url('trainings/create') }}" class="text-decoration-none quick-action-card">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                        <div class="card-body text-center p-3">
                            <div style="width: 48px; height: 48px; background: #e8f5d6; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4a7a25" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <h6 class="fw-semibold mb-1" style="font-size: 0.9rem; color: #1f2937;">Тренировка</h6>
                            <small class="text-muted">Запланировать</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ url('matches/create') }}" class="text-decoration-none quick-action-card">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                        <div class="card-body text-center p-3">
                            <div style="width: 48px; height: 48px; background: #dbeafe; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                </svg>
                            </div>
                            <h6 class="fw-semibold mb-1" style="font-size: 0.9rem; color: #1f2937;">Матч</h6>
                            <small class="text-muted">Создать игру</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ url('announcements/create') }}" class="text-decoration-none quick-action-card">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                        <div class="card-body text-center p-3">
                            <div style="width: 48px; height: 48px; background: #fef3c7; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                </svg>
                            </div>
                            <h6 class="fw-semibold mb-1" style="font-size: 0.9rem; color: #1f2937;">Объявление</h6>
                            <small class="text-muted">Опубликовать</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <button wire:click="openInviteModal" class="btn p-0 w-100 h-100 text-start quick-action-card" style="background: none; border: none;">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                        <div class="card-body text-center p-3">
                            <div style="width: 48px; height: 48px; background: #fce7f3; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#db2777" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <line x1="20" y1="8" x2="20" y2="14"></line>
                                    <line x1="23" y1="11" x2="17" y2="11"></line>
                                </svg>
                            </div>
                            <h6 class="fw-semibold mb-1" style="font-size: 0.9rem; color: #1f2937;">Приглашение</h6>
                            <small class="text-muted">Игрок/Родитель</small>
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </div>

    {{-- Заявки на вступление --}}
    @if($pendingRequests->isNotEmpty())
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; border: 1px solid #e8f5d6;">
        <div class="card-header bg-white border-0 pt-4 pb-3 px-4" style="border-radius: 16px 16px 0 0;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold" style="color: #2d4a14;">
                            Заявки на вступление
                            <span class="badge bg-warning text-dark ms-2" style="font-size: 0.75rem;">{{ $pendingRequests->count() }}</span>
                        </h5>
                        <small class="text-muted">Ожидают рассмотрения</small>
                    </div>
                </div>
                <a href="{{ route('join.requests') }}" class="btn btn-sm btn-outline-success">Все заявки</a>
            </div>
        </div>
        <div class="card-body px-4 pb-4 pt-0">
            <div class="list-group list-group-flush">
                @foreach($pendingRequests->take(3) as $request)
                    <div class="list-group-item px-0 py-3 border-bottom" style="border-color: #f3f4f6;">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div style="width: 44px; height: 44px; background: linear-gradient(135deg, #8fbd56 0%, #6d9e3a 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600;">
                                    {{ mb_strtoupper(mb_substr($request->user?->first_name ?? '?', 0, 1)) }}
                                </div>
                            </div>
                            <div class="col">
                                <h6 class="mb-0 fw-semibold">{{ $request->user?->full_name ?? 'Неизвестный' }}</h6>
                                <p class="text-muted mb-0 small">{{ $request->team?->name }}</p>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-sm btn-success" wire:click="approveRequest({{ $request->id }})" style="background: #8fbd56; border: none;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    Принять
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4">
        {{-- Левая колонка --}}
        <div class="col-lg-8">
            {{-- Тренировки на неделю --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        <i class="fe fe-calendar text-success me-2"></i>Тренировки на неделю
                    </h5>
                    <a href="{{ url('trainings') }}" class="btn btn-sm btn-outline-success">Все тренировки</a>
                </div>
                <div class="card-body px-4">
                    @if($weekTrainings->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="fe fe-calendar fs-1 mb-2 d-block opacity-25"></i>
                            <p>Нет тренировок на этой неделе</p>
                            <a href="{{ url('trainings/create') }}" class="btn btn-sm btn-success">Запланировать</a>
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
                                    <span class="badge bg-success">{{ $training->team?->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Матчи и соревнования --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        <i class="fe fe-trophy text-warning me-2"></i>Игры и соревнования
                    </h5>
                    <a href="{{ url('matches') }}" class="btn btn-sm btn-outline-warning">Все матчи</a>
                </div>
                <div class="card-body px-4">
                    @if($weekMatches->isEmpty() && $upcomingTournaments->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="fe fe-trophy fs-1 mb-2 d-block opacity-25"></i>
                            <p>Нет предстоящих игр</p>
                            <a href="{{ url('matches/create') }}" class="btn btn-sm btn-warning">Создать матч</a>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($weekMatches as $match)
                                <div class="list-group-item px-0 py-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center" 
                                             style="width: 48px; height: 48px; background: #dbeafe; color: #3b82f6;">
                                            <i class="fe fe-shield fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Матч</h6>
                                            <small class="text-muted">
                                                <i class="fe fe-calendar me-1"></i>{{ $match->match_date?->format('d.m.Y H:i') ?? '-' }}
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary">{{ $match->team?->name }}</span>
                                </div>
                            @endforeach
                            @foreach($upcomingTournaments as $tournament)
                                <div class="list-group-item px-0 py-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center" 
                                             style="width: 48px; height: 48px; background: #fef3c7; color: #f59e0b;">
                                            <i class="fe fe-award fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">{{ $tournament->name }}</h6>
                                            <small class="text-muted">
                                                <i class="fe fe-calendar me-1"></i>{{ $tournament->start_date?->format('d.m.Y') ?? '-' }}
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge bg-warning text-dark">Турнир</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Правая колонка --}}
        <div class="col-lg-4">
            {{-- Мои команды --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fe fe-users text-success me-2"></i>Мои команды
                        <span class="badge bg-light text-dark ms-2">{{ $totalPlayers }} игроков</span>
                    </h5>
                </div>
                <div class="card-body px-4">
                    @if($teams->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <p>У вас пока нет команд</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($teams as $team)
                                <div class="col-6">
                                    <a href="{{ route('club.team.show', $team->id) }}" class="text-decoration-none">
                                        <div class="card h-100" style="border-radius: 12px; border: 1px solid #e5e7eb; transition: all 0.2s;">
                                            <div class="card-body text-center p-3">
                                                <div class="rounded-3 mx-auto mb-2" 
                                                     style="width: 48px; height: 48px; background: {{ $team->team_color ?? '#8fbd56' }}; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 1.2rem;">
                                                    {{ mb_strtoupper(mb_substr($team->name, 0, 1)) }}
                                                </div>
                                                <h6 class="fw-bold mb-1" style="font-size: 0.9rem; color: #1f2937;">{{ $team->name }}</h6>
                                                <small class="text-muted">{{ $team->members_count }} игр.</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Объявления --}}
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        <i class="fe fe-bell text-info me-2"></i>Объявления
                    </h5>
                    <a href="{{ url('announcements') }}" class="btn btn-sm btn-outline-info">Все</a>
                </div>
                <div class="card-body px-4">
                    @if($announcements->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="fe fe-bell fs-1 mb-2 d-block opacity-25"></i>
                            <p>Нет объявлений</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($announcements as $announcement)
                                <div class="list-group-item px-0 py-3">
                                    <h6 class="fw-bold mb-1">{{ $announcement->title }}</h6>
                                    <p class="text-muted mb-1 small">{{ \Illuminate\Support\Str::limit($announcement->content, 80) }}</p>
                                    <small class="text-muted">{{ $announcement->created_at->diffForHumans() }}</small>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@else
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-5 text-center">
            <h4 class="fw-bold mb-2">Добро пожаловать в Сбор!</h4>
            <p class="text-muted">Ваш дашборд скоро будет настроен.</p>
        </div>
    </div>
@endif

</div>
