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
        .btn-add-inline {
            background: #f0fdf4;
            border: 1.5px solid #8fbd56;
            color: #6d9e3a;
            font-weight: 600;
            border-radius: 10px;
            padding: 4px 10px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-add-inline:hover {
            background: #8fbd56;
            color: #fff;
        }
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-dialog-custom {
            background: #fff;
            border-radius: 16px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            overflow: hidden;
        }
        .modal-header-custom {
            padding: 20px 24px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-body-custom {
            padding: 20px 24px;
        }
        .modal-footer-custom {
            padding: 16px 24px 20px;
            display: flex;
            gap: 10px;
        }
    </style>

    <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                    <li class="breadcrumb-item active">Новая тренировка</li>
                </ol>
            </nav>
            <h1 class="page-title fw-bold">Запланировать тренировку</h1>
        </div>
    </div>

    <form wire:submit="save">
        <div class="row">
            <div class="col-lg-8">
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

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Дата <span class="text-danger">*</span></label>
                            <input type="date" wire:model="trainingDate" class="form-control @error('trainingDate') is-invalid @enderror">
                            @error('trainingDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Время начала <span class="text-danger">*</span></label>
                            <input type="time" wire:model="startTime" class="form-control @error('startTime') is-invalid @enderror">
                            @error('startTime') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Длительность (минут)</label>
                            <select wire:model="durationMinutes" class="form-select">
                                <option value="45">45 минут</option>
                                <option value="60">1 час</option>
                                <option value="90">1.5 часа</option>
                                <option value="120">2 часа</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label d-flex align-items-center justify-content-between">
                                <span>Место проведения</span>
                                <button type="button" wire:click="openVenueModal" class="btn-add-inline">
                                    <i class="fe fe-plus me-1"></i>Новое место
                                </button>
                            </label>
                            <select wire:model="selectedVenueId" class="form-select">
                                <option value="">Выберите место</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue['id'] }}">{{ $venue['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Тип тренировки</label>
                        <select wire:model="selectedTrainingTypeId" class="form-select">
                            <option value="">Выберите тип</option>
                            @foreach($trainingTypes as $type)
                                <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Комментарий</label>
                        <textarea wire:model="comment" rows="3" class="form-control" placeholder="Дополнительная информация..."></textarea>
                    </div>
                </div>

                {{-- Настройки уведомлений --}}
                <div class="form-section shadow-sm">
                    <h5 class="fw-bold mb-4">Настройки уведомлений</h5>

                    <label class="checkbox-wrapper mb-3">
                        <input type="checkbox" wire:model="notifyParents">
                        <div>
                            <div class="fw-semibold">Уведомить родителей</div>
                            <small class="text-muted">Отправить уведомление о новой тренировке</small>
                        </div>
                    </label>

                    <label class="checkbox-wrapper mb-0">
                        <input type="checkbox" wire:model="requireRsvp">
                        <div>
                            <div class="fw-semibold">Требовать подтверждение</div>
                            <small class="text-muted">Родители должны подтвердить присутствие</small>
                        </div>
                    </label>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-section shadow-sm">
                    <h5 class="fw-bold mb-4">Действия</h5>

                    <button type="submit" class="btn btn-create w-100 mb-3" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">Создать тренировку</span>
                        <span wire:loading wire:target="save">Создание...</span>
                    </button>

                    <a href="{{ url()->previous() }}" class="btn btn-cancel w-100">Отмена</a>
                </div>

                <div class="form-section shadow-sm bg-light">
                    <h6 class="fw-bold mb-2">Подсказка</h6>
                    <p class="text-muted small mb-0">
                        После создания тренировки родители получат уведомление (если включено).
                        Они смогут подтвердить или отклонить участие своих детей.
                    </p>
                </div>
            </div>
        </div>
    </form>

    {{-- ═══════════════════════════════════════════════════════════════
         Модальное окно: Быстрое добавление места проведения
    ═══════════════════════════════════════════════════════════════ --}}
    @if($showVenueModal)
    <div class="modal-overlay" wire:click.self="closeVenueModal">
        <div class="modal-dialog-custom">
            <div class="modal-header-custom">
                <h5 class="fw-bold mb-0">Новое место проведения</h5>
                <button type="button" wire:click="closeVenueModal"
                        style="background: none; border: none; font-size: 1.4rem; color: #9ca3af; cursor: pointer;">&times;</button>
            </div>
            <div class="modal-body-custom">
                <div class="mb-3">
                    <label class="form-label">Название <span class="text-danger">*</span></label>
                    <input type="text" wire:model="venueName"
                           class="form-control @error('venueName') is-invalid @enderror"
                           placeholder='Например: Стадион "Юность", Зал №3'>
                    @error('venueName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-0">
                    <label class="form-label">Адрес <span class="text-danger">*</span></label>
                    <input type="text" wire:model="venueAddress"
                           class="form-control @error('venueAddress') is-invalid @enderror"
                           placeholder="Улица, дом, корпус">
                    @error('venueAddress') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" wire:click="closeVenueModal"
                        style="flex: 1; background: #fff; border: 1.5px solid #e5e7eb; color: #374151; font-weight: 600; padding: 10px; border-radius: 10px; cursor: pointer;">
                    Отмена
                </button>
                <button type="button" wire:click="createVenue" wire:loading.attr="disabled"
                        style="flex: 1; background: #8fbd56; border: none; color: #fff; font-weight: 600; padding: 10px; border-radius: 10px; cursor: pointer;">
                    <span wire:loading.remove wire:target="createVenue">Добавить</span>
                    <span wire:loading wire:target="createVenue">Сохранение...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
