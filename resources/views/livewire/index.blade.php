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
</style>

<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <p class="text-muted mb-0">{{ $greeting }} 👋</p>
        <h1 class="page-title fw-bold">{{ $user->first_name }}</h1>
    </div>
</div>

@if ($role === 'admin')

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
                        <a href="{{ url('trainings') }}" class="btn btn-sm btn-create-event">Создать событие</a>
                        <span class="text-muted">или</span>
                        <a href="{{ url('trainings/recurring') }}" class="btn btn-sm btn-import">Импорт расписания</a>
                    </div>
                @endunless
            </div>
        </div>
    </div>
    @endif

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
                    <a href="{{ url('club/teams') }}" class="team-card">
                        <div class="team-avatar" style="background: {{ $team->team_color ?? '#6366f1' }};">
                            {{ mb_strtoupper(mb_substr($team->name, 0, 1)) }}
                        </div>
                        <div class="team-info">
                            <h6>{{ $team->name }}</h6>
                            <small>{{ $club->name ?? '' }}</small>
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
        @livewire('invite-modal', ['teamId' => $inviteTeamId], key('invite-modal'))
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

@else
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-5 text-center">
            <h4 class="fw-bold mb-2">Добро пожаловать в Сбор!</h4>
            <p class="text-muted">Ваш дашборд скоро будет настроен.</p>
        </div>
    </div>
@endif

</div>
