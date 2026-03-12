<div class="bg-light min-vh-100">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="h2 mb-3">Найдите свой клуб</h1>
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
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="ti ti-clock me-2"></i>Ожидающие заявки</h6>
                        <ul class="mb-0 mt-2">
                            @foreach($pendingRequests as $req)
                                <li>{{ $req->club->name }} — отправлена {{ $req->created_at->diffForHumans() }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Search -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <label class="form-label fw-medium">Поиск клуба</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="ti ti-search text-muted"></i>
                            </span>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="searchQuery" 
                                   class="form-control border-start-0 ps-0" 
                                   placeholder="Начните вводить название клуба или город...">
                        </div>
                        <small class="form-text text-muted">Введите минимум 2 символа для поиска</small>
                    </div>
                </div>

                <!-- Results -->
                @if($searchQuery && strlen($searchQuery) >= 2)
                    @if($clubs->isEmpty())
                        <div class="text-center py-4">
                            <i class="ti ti-search-off text-muted fs-1 mb-3"></i>
                            <p class="text-muted">Клубы не найдены. Попробуйте изменить запрос.</p>
                        </div>
                    @else
                        <div class="row g-3 mb-4">
                            @foreach($clubs as $club)
                                <div class="col-md-6">
                                    <div class="card h-100 {{ $selectedClubId === $club->id ? 'border-success' : '' }}" 
                                         wire:click="selectClub({{ $club->id }})"
                                         style="cursor: pointer;">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center gap-3">
                                                @if($club->logo)
                                                    <img src="{{ Storage::url($club->logo) }}" 
                                                         class="rounded" width="48" height="48" alt="">
                                                @else
                                                    <span class="avatar bg-success-lt text-success">
                                                        <i class="ti ti-building"></i>
                                                    </span>
                                                @endif
                                                <div class="flex-fill">
                                                    <h6 class="mb-1">{{ $club->name }}</h6>
                                                    <small class="text-muted">
                                                        <i class="ti ti-map-pin me-1"></i>{{ $club->city?->name ?? 'Город не указан' }}
                                                    </small>
                                                </div>
                                                @if($selectedClubId === $club->id)
                                                    <i class="ti ti-check-circle text-success fs-4"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif

                <!-- Selected Club Details -->
                @if($selectedClub)
                    <div class="card border-success mb-4">
                        <div class="card-header bg-success-lt">
                            <h5 class="mb-0">{{ $selectedClub->name }}</h5>
                        </div>
                        <div class="card-body">
                            @if(!$joinAsCoach)
                                <label class="form-label fw-medium">Выберите команду</label>
                                @if($teams->isEmpty())
                                    <div class="alert alert-warning">
                                        <i class="ti ti-alert-circle me-2"></i>В этом клубе пока нет команд
                                    </div>
                                @else
                                    <div class="list-group mb-3">
                                        @foreach($teams as $team)
                                            <label class="list-group-item d-flex align-items-center gap-3" style="cursor: pointer;">
                                                <input type="radio" 
                                                       wire:model="selectedTeamId" 
                                                       value="{{ $team->id }}" 
                                                       class="form-check-input">
                                                <div>
                                                    <div class="fw-medium">{{ $team->name }}</div>
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
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle me-2"></i>Вы будете добавлены как тренер клуба
                                </div>
                            @endif

                            @if($joinAsCoach || $selectedTeamId)
                                <button wire:click="openRequestModal" class="btn btn-success w-100">
                                    <i class="ti ti-send me-2"></i>Отправить заявку
                                </button>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Skip Option -->
                <div class="text-center">
                    <button wire:click="skipOnboarding" class="btn btn-link text-muted">
                        Пропустить, заполню позже
                    </button>
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
                        <h5 class="modal-title">Отправить заявку</h5>
                        <button type="button" class="btn-close" wire:click="closeRequestModal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-3">
                            Заявка будет отправлена администратору клуба <strong>{{ $selectedClub->name }}</strong>.
                        </p>
                        @if($selectedTeamId)
                            @php $team = $teams->firstWhere('id', $selectedTeamId); @endphp
                            <p class="text-muted mb-3">
                                Команда: <strong>{{ $team?->name }}</strong>
                            </p>
                        @endif
                        <div class="mb-3">
                            <label class="form-label">Сообщение (опционально)</label>
                            <textarea wire:model="requestMessage" class="form-control" rows="3" 
                                      placeholder="Например: Я тренер с 10-летним опытом..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link text-muted" wire:click="closeRequestModal">
                            Отмена
                        </button>
                        <button type="button" class="btn btn-success" wire:click="sendRequest" wire:loading.attr="disabled">
                            <span wire:loading.remove>Отправить заявку</span>
                            <span wire:loading>Отправка...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
