<div>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Новое объявление</li>
                    </ol>
                </nav>
                <h1 class="page-title fw-bold mb-0">Создать объявление</h1>
            </div>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="fe fe-arrow-left me-1"></i> Назад
            </a>
        </div>

        <div class="row">
            <!-- Form -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-radius: 14px;">
                    <div class="card-body p-4">
                        <form wire:submit="save">
                            <!-- Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label fw-semibold">Заголовок <span class="text-danger">*</span></label>
                                <input type="text" id="title" wire:model="title"
                                       class="form-control @error('title') is-invalid @enderror"
                                       style="border-radius: 10px; border: 1.5px solid #e5e7eb; padding: 10px 14px;"
                                       placeholder="Например: Изменение расписания тренировок">
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Message -->
                            <div class="mb-3">
                                <label for="message" class="form-label fw-semibold">Текст объявления <span class="text-danger">*</span></label>
                                <textarea id="message" wire:model="message" rows="6"
                                          class="form-control @error('message') is-invalid @enderror"
                                          style="border-radius: 10px; border: 1.5px solid #e5e7eb; padding: 10px 14px;"
                                          placeholder="Подробное описание объявления..."></textarea>
                                @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Team (optional) -->
                            <div class="mb-3">
                                <label for="selectedTeamId" class="form-label fw-semibold">Команда</label>
                                <select id="selectedTeamId" wire:model="selectedTeamId"
                                        class="form-select" style="border-radius: 10px; border: 1.5px solid #e5e7eb; padding: 10px 14px;">
                                    <option value="">Для всего клуба</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team['id'] }}">{{ $team['name'] }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted mt-1 d-block">Оставьте пустым, чтобы отправить объявление всему клубу</small>
                            </div>

                            <!-- Priority -->
                            <div class="mb-3">
                                <label for="priority" class="form-label fw-semibold">Приоритет</label>
                                <select id="priority" wire:model="priority"
                                        class="form-select" style="border-radius: 10px; border: 1.5px solid #e5e7eb; padding: 10px 14px;">
                                    <option value="low">Низкий</option>
                                    <option value="normal">Обычный</option>
                                    <option value="high">Высокий</option>
                                    <option value="urgent">Срочный</option>
                                </select>
                            </div>

                            <!-- Expires At -->
                            <div class="mb-3">
                                <label for="expiresAt" class="form-label fw-semibold">Срок действия</label>
                                <input type="date" id="expiresAt" wire:model="expiresAt"
                                       class="form-control @error('expiresAt') is-invalid @enderror"
                                       style="border-radius: 10px; border: 1.5px solid #e5e7eb; padding: 10px 14px;"
                                       min="{{ now()->addDay()->format('Y-m-d') }}">
                                @error('expiresAt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <small class="text-muted mt-1 d-block">Оставьте пустым для бессрочного объявления</small>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn fw-semibold"
                                        style="background: #8fbd56; border: none; color: #fff; padding: 10px 22px; border-radius: 10px;"
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="save">
                                        <i class="fe fe-send me-1"></i> Опубликовать
                                    </span>
                                    <span wire:loading wire:target="save">
                                        <i class="fe fe-loader me-1"></i> Сохранение...
                                    </span>
                                </button>
                                <button type="button" wire:click="$set('isDraft', true)" wire:click.then="save"
                                        class="btn btn-outline-secondary fw-semibold"
                                        style="padding: 10px 22px; border-radius: 10px;">
                                    <i class="fe fe-save me-1"></i> Сохранить как черновик
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary fw-semibold"
                                   style="padding: 10px 22px; border-radius: 10px;">
                                    Отмена
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="border-radius: 14px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="fe fe-info me-2 text-muted"></i>Подсказка</h6>
                        <div class="text-muted small">
                            <p class="mb-2">Объявления видны всем участникам выбранной команды или всего клуба.</p>
                            <p class="mb-2"><strong>Приоритет «Срочный»</strong> — объявление выделяется красным и отображается первым.</p>
                            <p class="mb-0"><strong>Черновик</strong> — сохраняется, но не видно участникам до публикации.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
