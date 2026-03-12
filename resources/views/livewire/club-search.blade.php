<div>
    <style>
        .club-search-container {
            min-height: 100vh;
            background: #f8faf5;
            padding: 40px 0;
        }
        .club-search-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.06);
            border: 1px solid #e8f5d6;
        }
        .club-search-input {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .club-search-input:focus {
            border-color: #8fbd56;
            box-shadow: 0 0 0 4px rgba(143, 189, 86, 0.15);
            outline: none;
        }
        .club-result-card {
            background: #fff;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .club-result-card:hover {
            border-color: #c3dba0;
            background: #fafdf5;
        }
        .club-result-card.selected {
            border-color: #8fbd56;
            background: #f3f9ea;
            box-shadow: 0 0 0 3px rgba(143, 189, 86, 0.15);
        }
        .club-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #8fbd56 0%, #6d9e3a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 1.2rem;
        }
        .btn-primary-green {
            background: #8fbd56;
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .btn-primary-green:hover {
            background: #6d9e3a;
            color: #fff;
        }
        .btn-outline-green {
            background: #fff;
            border: 2px solid #8fbd56;
            color: #6d9e3a;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .btn-outline-green:hover {
            background: #f3f9ea;
        }
        .alert-green {
            background: #f3f9ea;
            border: 1px solid #c3dba0;
            border-radius: 12px;
            color: #4a7a25;
        }
        .alert-green .alert-heading {
            color: #2d4a14;
            font-weight: 600;
        }
        .selected-club-card {
            background: linear-gradient(135deg, #f3f9ea 0%, #e8f5d6 100%);
            border: 2px solid #8fbd56;
            border-radius: 16px;
        }
        .team-select-item {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .team-select-item:hover {
            border-color: #c3dba0;
            background: #fafdf5;
        }
        .team-select-item.selected {
            border-color: #8fbd56;
            background: #f3f9ea;
        }
        .form-check-input:checked {
            background-color: #8fbd56;
            border-color: #8fbd56;
        }
        .modal-content {
            border-radius: 16px;
            border: none;
        }
        .modal-header {
            border-bottom: 1px solid #e8f5d6;
        }
        .btn-close-custom {
            background: #f3f4f6;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-close-custom:hover {
            background: #e5e7eb;
        }
    </style>

    <div class="club-search-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Header -->
                    <div class="text-center mb-5">
                        <h1 class="h2 mb-3 fw-bold" style="color: #2d4a14;">Найдите свой клуб</h1>
                        <p class="text-muted">
                            @if($joinAsCoach)
                                Вы зарегистрированы как тренер. Найдите клуб, к которому хотите присоединиться.
                            @else
                                Найдите команду, в которой играете вы или ваш ребёнок.
                            @endif
                        </p>
                    </div>

                    <!-- Pending Requests -->
                    @if($pendingRequests->isNotEmpty())
                        <div class="alert alert-green mb-4">
                            <h6 class="alert-heading d-flex align-items-center gap-2">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                Ожидающие заявки
                            </h6>
                            <ul class="mb-0 mt-2" style="padding-left: 20px;">
                                @foreach($pendingRequests as $req)
                                    <li>{{ $req->club->name }} — отправлена {{ $req->created_at->diffForHumans() }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Search -->
                    <div class="club-search-card p-4 mb-4">
                        <label class="form-label fw-semibold" style="color: #374151;">Поиск клуба</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-end-0" style="border-color: #e5e7eb; border-radius: 12px 0 0 12px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                            </span>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="searchQuery" 
                                   class="form-control club-search-input border-start-0 ps-0" 
                                   style="border-radius: 0 12px 12px 0;"
                                   placeholder="Начните вводить название клуба или город...">
                        </div>
                        <small class="form-text text-muted">Введите минимум 2 символа для поиска</small>
                    </div>

                    <!-- Results -->
                    @if($searchQuery && strlen($searchQuery) >= 2)
                        @if($clubs->isEmpty())
                            <div class="text-center py-5">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" class="mb-3">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                                <p class="text-muted">Клубы не найдены. Попробуйте изменить запрос.</p>
                            </div>
                        @else
                            <div class="row g-3 mb-4">
                                @foreach($clubs as $club)
                                    <div class="col-md-6">
                                        <div class="club-result-card {{ $selectedClubId === $club->id ? 'selected' : '' }}" 
                                             wire:click="selectClub({{ $club->id }})"
                                             style="height: 100%;">
                                            <div class="d-flex align-items-center gap-3">
                                                @if($club->logo)
                                                    <img src="{{ Storage::url($club->logo) }}" 
                                                         class="rounded" width="48" height="48" alt="">
                                                @else
                                                    <div class="club-avatar">
                                                        {{ mb_strtoupper(mb_substr($club->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div class="flex-fill">
                                                    <h6 class="mb-1 fw-semibold" style="color: #1f2937;">{{ $club->name }}</h6>
                                                    <small class="text-muted">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: text-bottom; margin-right: 4px;">
                                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                            <circle cx="12" cy="10" r="3"></circle>
                                                        </svg>
                                                        {{ $club->city?->name ?? 'Город не указан' }}
                                                    </small>
                                                </div>
                                                @if($selectedClubId === $club->id)
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8fbd56" stroke-width="3">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif

                    <!-- Selected Club Details -->
                    @if($selectedClub)
                        <div class="selected-club-card p-4 mb-4">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                @if($selectedClub->logo)
                                                    <img src="{{ Storage::url($selectedClub->logo) }}" 
                                                         class="rounded" width="48" height="48" alt="">
                                                @else
                                                    <div class="club-avatar">
                                                        {{ mb_strtoupper(mb_substr($selectedClub->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                <div>
                                    <h5 class="mb-0 fw-bold" style="color: #2d4a14;">{{ $selectedClub->name }}</h5>
                                    @if($selectedClub->sportType)
                                        <small class="text-muted">{{ $selectedClub->sportType->name }}</small>
                                    @endif
                                </div>
                            </div>

                            @if(!$joinAsCoach)
                                <label class="form-label fw-semibold" style="color: #374151;">Выберите команду</label>
                                @if($teams->isEmpty())
                                    <div class="alert alert-green">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: text-bottom; margin-right: 6px;">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        В этом клубе пока нет команд
                                    </div>
                                @else
                                    <div class="list-group mb-4">
                                        @foreach($teams as $team)
                                            <label class="team-select-item d-flex align-items-center gap-3 mb-2 {{ $selectedTeamId === $team->id ? 'selected' : '' }}" style="cursor: pointer;">
                                                <input type="radio" 
                                                       wire:model="selectedTeamId" 
                                                       value="{{ $team->id }}" 
                                                       class="form-check-input"
                                                       style="width: 20px; height: 20px; margin: 0;">
                                                <div>
                                                    <div class="fw-semibold" style="color: #1f2937;">{{ $team->name }}</div>
                                                    <small class="text-muted">
                                                        {{ $team->gender === 'male' ? 'Мужская' : 'Женская' }}, 
                                                        {{ $team->birth_year }} г.р.
                                                    </small>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-green">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: text-bottom; margin-right: 6px;">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg>
                                    Вы будете добавлены как тренер клуба
                                </div>
                            @endif

                            @if($joinAsCoach || $selectedTeamId)
                                <button wire:click="openRequestModal" class="btn btn-primary-green w-100">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: text-bottom; margin-right: 6px;">
                                        <line x1="22" y1="2" x2="11" y2="13"></line>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                    </svg>
                                    Отправить заявку
                                </button>
                            @endif
                        </div>
                    @endif

                    <!-- Skip Option -->
                    <div class="text-center">
                        <button wire:click="skipOnboarding" class="btn btn-link text-muted text-decoration-underline">
                            Пропустить, заполню позже
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Modal -->
    @if($showRequestModal)
        <div class="modal show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" style="color: #2d4a14;">Отправить заявку</h5>
                        <button type="button" class="btn-close-custom" wire:click="closeRequestModal">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-3">
                            Заявка будет отправлена администратору клуба <strong style="color: #1f2937;">{{ $selectedClub->name }}</strong>.
                        </p>
                        @if($selectedTeamId)
                            @php $team = $teams->firstWhere('id', $selectedTeamId); @endphp
                            <p class="text-muted mb-3">
                                Команда: <strong style="color: #1f2937;">{{ $team?->name }}</strong>
                            </p>
                        @endif
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #374151;">Сообщение (опционально)</label>
                            <textarea wire:model="requestMessage" class="form-control club-search-input" rows="3" 
                                      placeholder="Например: Я тренер с 10-летним опытом..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #e8f5d6;">
                        <button type="button" class="btn btn-outline-green" wire:click="closeRequestModal">
                            Отмена
                        </button>
                        <button type="button" class="btn btn-primary-green" wire:click="sendRequest" wire:loading.attr="disabled">
                            <span wire:loading.remove>Отправить заявку</span>
                            <span wire:loading>Отправка...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
