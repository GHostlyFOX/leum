<div>
    <style>
        .form-section {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
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
        .btn-create {
            background: #8fbd56;
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
        }
        .btn-create:hover {
            background: #6d9e3a;
            color: #fff;
        }
        .btn-cancel {
            background: #f3f4f6;
            border: none;
            color: #6b7280;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
        }
        .btn-cancel:hover {
            background: #e5e7eb;
        }
        .match-type-btn {
            padding: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            cursor: pointer;
            transition: all 0.2s;
            text-align: left;
        }
        .match-type-btn:hover {
            border-color: #c3dba0;
        }
        .match-type-btn.active {
            border-color: #8fbd56;
            background: #f0fdf4;
        }
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: #f9fafb;
            border-radius: 10px;
            cursor: pointer;
        }
        .checkbox-wrapper input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: #8fbd56;
        }
        .opponent-suggestions {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-top: 4px;
        }
        .opponent-suggestion {
            padding: 10px 16px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .opponent-suggestion:hover {
            background: #f0fdf4;
        }
    </style>

    <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                    <li class="breadcrumb-item active">Новый матч</li>
                </ol>
            </nav>
            <h1 class="page-title fw-bold">Создать матч</h1>
        </div>
    </div>

    <form wire:submit="save">
        <div class="row">
            <div class="col-lg-8">
                {{-- Тип матча --}}
                <div class="form-section shadow-sm">
                    <h5 class="fw-bold mb-4">Тип матча</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <button type="button" 
                                    wire:click="$set('matchType', 'friendly')" 
                                    class="match-type-btn w-100 {{ $matchType === 'friendly' ? 'active' : '' }}">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 48px; height: 48px; background: #dbeafe; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Товарищеский</div>
                                        <small class="text-muted">Обычная игра</small>
                                    </div>
                                </div>
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" 
                                    wire:click="$set('matchType', 'tournament')" 
                                    class="match-type-btn w-100 {{ $matchType === 'tournament' ? 'active' : '' }}">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 48px; height: 48px; background: #fef3c7; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2">
                                            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                                            <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                                            <path d="M4 22h16"></path>
                                            <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                                            <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                                            <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Турнирный</div>
                                        <small class="text-muted">Часть турнира</small>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>

                    @if($matchType === 'tournament')
                        <div class="mt-3">
                            <label class="form-label">Турнир <span class="text-danger">*</span></label>
                            <select wire:model="selectedTournamentId" class="form-select @error('selectedTournamentId') is-invalid @enderror">
                                <option value="">Выберите турнир</option>
                                @foreach($tournaments as $tournament)
                                    <option value="{{ $tournament['id'] }}">{{ $tournament['name'] }}</option>
                                @endforeach
                            </select>
                            @error('selectedTournamentId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    @endif
                </div>

                {{-- Основная информация --}}
                <div class="form-section shadow-sm">
                    <h5 class="fw-bold mb-4">Основная информация</h5>
                    
                    <div class="mb-3">
                        <label class="form-label">Команда <span class="text-danger">*</span></label>
                        <select wire:model="selectedTeamId" class="form-select @error('selectedTeamId') is-invalid @enderror">
                            <option value="">Выберите команду</option>
                            @foreach($teams as $team)
                                <option value="{{ $team['id'] }}">{{ $team['name'] }}</option>
                            @endforeach
                        </select>
                        @error('selectedTeamId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3 position-relative">
                        <label class="form-label">Соперник <span class="text-danger">*</span></label>
                        <input type="text" 
                               wire:model.live="opponentName" 
                               class="form-control @error('opponentName') is-invalid @enderror" 
                               placeholder="Название команды соперника">
                        @error('opponentName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        
                        @if(!empty($opponentName) && !$selectedOpponentId)
                            @php
                                $filteredOpponents = array_filter($opponents, fn($o) => stripos($o['name'], $opponentName) !== false);
                            @endphp
                            @if(count($filteredOpponents) > 0)
                                <div class="opponent-suggestions bg-white shadow-sm">
                                    @foreach($filteredOpponents as $opponent)
                                        <div class="opponent-suggestion" wire:click="selectOpponent({{ $opponent['id'] }}, '{{ $opponent['name'] }}')">
                                            {{ $opponent['name'] }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Дата <span class="text-danger">*</span></label>
                            <input type="date" wire:model="matchDate" class="form-control @error('matchDate') is-invalid @enderror">
                            @error('matchDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Время <span class="text-danger">*</span></label>
                            <input type="time" wire:model="matchTime" class="form-control @error('matchTime') is-invalid @enderror">
                            @error('matchTime') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Длительность тайма</label>
                            <select wire:model="halfDuration" class="form-select">
                                <option value="30">30 минут</option>
                                <option value="35">35 минут</option>
                                <option value="40">40 минут</option>
                                <option value="45">45 минут</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Количество таймов</label>
                            <select wire:model="halvesCount" class="form-select">
                                <option value="1">1 тайм</option>
                                <option value="2">2 тайма</option>
                                <option value="3">3 тайма</option>
                                <option value="4">4 тайма</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Место проведения</label>
                        <select wire:model="selectedVenueId" class="form-select">
                            <option value="">Выберите место</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue['id'] }}">{{ $venue['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="checkbox-wrapper mb-0">
                            <input type="checkbox" wire:model="isAway">
                            <div>
                                <div class="fw-semibold">Выездной матч</div>
                                <small class="text-muted">Игра проходит на поле соперника</small>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Дополнительно --}}
                <div class="form-section shadow-sm">
                    <h5 class="fw-bold mb-4">Дополнительно</h5>
                    
                    <div class="mb-3">
                        <label class="form-label">Описание / Примечания</label>
                        <textarea wire:model="description" rows="3" class="form-control" placeholder="Дополнительная информация о матче..."></textarea>
                    </div>

                    <label class="checkbox-wrapper mb-0">
                        <input type="checkbox" wire:model="notifyParents">
                        <div>
                            <div class="fw-semibold">Уведомить родителей</div>
                            <small class="text-muted">Отправить уведомление о новом матче</small>
                        </div>
                    </label>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-section shadow-sm">
                    <h5 class="fw-bold mb-4">Действия</h5>
                    
                    <button type="submit" class="btn btn-create w-100 mb-3" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">Создать матч</span>
                        <span wire:loading wire:target="save">Создание...</span>
                    </button>
                    
                    <a href="{{ url()->previous() }}" class="btn btn-cancel w-100">Отмена</a>
                </div>

                <div class="form-section shadow-sm bg-light">
                    <h6 class="fw-bold mb-2">Подсказка</h6>
                    <p class="text-muted small mb-0">
                        После создания матча родители получат уведомление. 
                        Вы сможете отслеживать состав на игру и фиксировать результаты.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
