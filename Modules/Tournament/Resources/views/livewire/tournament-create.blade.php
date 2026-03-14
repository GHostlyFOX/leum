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
    </style>

    <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tournaments.index') }}">Турниры</a></li>
                    <li class="breadcrumb-item active">Создание турнира</li>
                </ol>
            </nav>
            <h1 class="page-title fw-bold">Создание турнира</h1>
        </div>
    </div>

    <form wire:submit="save">
        <div class="row">
            <div class="col-lg-8">
                {{-- Основная информация --}}
                <div class="form-section shadow-sm">
                    <h5 class="fw-bold mb-4">Основная информация</h5>

                    <div class="mb-3">
                        <label class="form-label">Название турнира <span class="text-danger">*</span></label>
                        <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Например: Чемпионат города 2024">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Тип турнира <span class="text-danger">*</span></label>
                        <select wire:model="tournamentTypeId" class="form-select @error('tournamentTypeId') is-invalid @enderror">
                            <option value="">Выберите тип турнира</option>
                            @foreach($tournamentTypes as $type)
                                <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                            @endforeach
                        </select>
                        @error('tournamentTypeId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Дата начала <span class="text-danger">*</span></label>
                            <input type="date" wire:model="startsAt" class="form-control @error('startsAt') is-invalid @enderror">
                            @error('startsAt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Дата завершения <span class="text-danger">*</span></label>
                            <input type="date" wire:model="endsAt" class="form-control @error('endsAt') is-invalid @enderror">
                            @error('endsAt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Организатор <span class="text-danger">*</span></label>
                        <input type="text" wire:model="organizer" class="form-control @error('organizer') is-invalid @enderror" placeholder="Имя организатора или организации">
                        @error('organizer') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Настройки матчей --}}
                <div class="form-section shadow-sm">
                    <h5 class="fw-bold mb-4">Настройки матчей</h5>

                    <div class="row g-3 mb-0">
                        <div class="col-md-6">
                            <label class="form-label">Длительность тайма (минут) <span class="text-danger">*</span></label>
                            <select wire:model="halfDurationMinutes" class="form-select @error('halfDurationMinutes') is-invalid @enderror">
                                <option value="15">15 минут</option>
                                <option value="30">30 минут</option>
                                <option value="45">45 минут</option>
                                <option value="60">60 минут</option>
                            </select>
                            @error('halfDurationMinutes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Количество таймов <span class="text-danger">*</span></label>
                            <select wire:model="halvesCount" class="form-select @error('halvesCount') is-invalid @enderror">
                                <option value="1">1 тайм</option>
                                <option value="2">2 тайма</option>
                                <option value="3">3 тайма</option>
                                <option value="4">4 тайма</option>
                            </select>
                            @error('halvesCount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-section shadow-sm">
                    <h5 class="fw-bold mb-4">Действия</h5>

                    <button type="submit" class="btn btn-create w-100 mb-3" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">Создать турнир</span>
                        <span wire:loading wire:target="save">Создание...</span>
                    </button>

                    <a href="{{ route('tournaments.index') }}" class="btn btn-cancel w-100">Отмена</a>
                </div>

                <div class="form-section shadow-sm bg-light">
                    <h6 class="fw-bold mb-2">Подсказка</h6>
                    <p class="text-muted small mb-0">
                        После создания турнира вы сможете добавить команды и запланировать матчи.
                        Все параметры матчей (длительность и количество таймов) будут применены ко всем играм турнира.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
