<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="page-title" style="font-size: 1.5rem; font-weight: 700; color: #1f2937;">
                Достижения тренера
            </h2>
            @if($coach)
                <p class="text-secondary" style="color: #6b7280;">
                    {{ $coach->full_name }}
                </p>
            @endif
        </div>
    </div>

    <!-- Список достижений -->
    <div class="row">
        <div class="col-12">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 style="margin: 0; color: #1f2937; font-weight: 600;">
                        Персональные награды и достижения
                    </h5>
                    @if($isOwnProfile)
                        <button wire:click="openCreateForm" class="btn-primary-custom" 
                            style="background: #8fbd56; border: none; color: #fff; font-weight: 600; padding: 10px 22px; border-radius: 10px; cursor: pointer;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Добавить достижение
                        </button>
                    @endif
                </div>

                @if($achievements->isEmpty())
                    <div style="text-align: center; padding: 40px; color: #6b7280;">
                        <div class="icon-circle-light" style="width: 60px; height: 60px; border-radius: 50%; background: #f0fdf4; display: flex; align-items: center; justify-content: center; color: #8fbd56; margin: 0 auto 16px;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </div>
                        <p>Пока нет добавленных достижений</p>
                        @if($isOwnProfile)
                            <p style="font-size: 0.875rem;">Нажмите кнопку выше, чтобы добавить первое достижение</p>
                        @endif
                    </div>
                @else
                    <div class="timeline" style="position: relative; padding-left: 30px;">
                        @php $currentYear = null; @endphp
                        @foreach($achievements as $achievement)
                            @if($currentYear !== $achievement->year)
                                @php $currentYear = $achievement->year; @endphp
                                <div style="position: relative; margin-bottom: 20px;">
                                    <div style="position: absolute; left: -30px; top: 0; width: 20px; height: 20px; background: #8fbd56; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 10px; font-weight: bold;">
                                        {{ substr($achievement->year, -2) }}
                                    </div>
                                    <div style="font-weight: 700; color: #8fbd56; font-size: 1.1rem;">{{ $achievement->year }}</div>
                                </div>
                            @endif
                            
                            <div style="position: relative; margin-bottom: 16px; padding: 16px; background: #f8f9fa; border-radius: 10px; border-left: 3px solid #8fbd56;">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: #1f2937; margin-bottom: 4px;">
                                            {{ $achievement->title }}
                                        </div>
                                        @if($achievement->description)
                                            <div style="color: #6b7280; font-size: 0.9rem; margin-bottom: 8px;">
                                                {{ $achievement->description }}
                                            </div>
                                        @endif
                                        @if($achievement->category)
                                            <span style="display: inline-block; padding: 2px 8px; background: #f0fdf4; color: #6d9e3a; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">
                                                {{ $categories[$achievement->category] ?? $achievement->category }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($isOwnProfile)
                                        <div style="display: flex; gap: 8px; margin-left: 12px;">
                                            <button wire:click="openEditForm({{ $achievement->id }})" 
                                                style="background: none; border: none; color: #6b7280; cursor: pointer; padding: 4px;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </button>
                                            <button wire:click="confirmDelete({{ $achievement->id }})" 
                                                style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 4px;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Модаль добавления/редактирования -->
    @if($showForm)
    <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1050; display: flex; align-items: center; justify-content: center;" wire:click.self="closeForm">
        <div style="background: #fff; border-radius: 14px; padding: 24px; width: 100%; max-width: 500px; max-height: 90vh; overflow-y: auto; margin: 20px;">
            <h5 style="margin: 0 0 20px 0; color: #1f2937; font-weight: 600;">
                {{ $editingId ? 'Редактировать достижение' : 'Добавить достижение' }}
            </h5>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 6px;">
                    Название <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" wire:model="title" 
                    style="width: 100%; padding: 10px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 0.9rem;"
                    placeholder="Например: Чемпион города">
                @error('title')
                    <div style="color: #ef4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 6px;">
                    Описание
                </label>
                <textarea wire:model="description" rows="3"
                    style="width: 100%; padding: 10px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 0.9rem; resize: vertical;"
                    placeholder="Дополнительная информация о достижении..."></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 6px;">
                        Год <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" wire:model="year" min="1950" max="{{ date('Y') + 1 }}"
                        style="width: 100%; padding: 10px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 0.9rem;">
                    @error('year')
                        <div style="color: #ef4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 6px;">
                        Категория
                    </label>
                    <select wire:model="category" 
                        style="width: 100%; padding: 10px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 0.9rem; background: #fff;">
                        <option value="">-- Выберите --</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button wire:click="closeForm" 
                    style="background: #fff; border: 1.5px solid #e5e7eb; color: #6b7280; font-weight: 600; padding: 10px 22px; border-radius: 10px; cursor: pointer;">
                    Отмена
                </button>
                <button wire:click="save" 
                    style="background: #8fbd56; border: none; color: #fff; font-weight: 600; padding: 10px 22px; border-radius: 10px; cursor: pointer;"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $editingId ? 'Сохранить' : 'Добавить' }}</span>
                    <span wire:loading>Сохранение...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Модаль подтверждения удаления -->
    @if($showDeleteConfirm)
    <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1050; display: flex; align-items: center; justify-content: center;" wire:click.self="cancelDelete">
        <div style="background: #fff; border-radius: 14px; padding: 24px; width: 100%; max-width: 400px; margin: 20px;">
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="width: 60px; height: 60px; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </div>
                <h5 style="margin: 0 0 8px 0; color: #1f2937; font-weight: 600;">Удалить достижение?</h5>
                <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">Это действие нельзя отменить.</p>
            </div>

            <div style="display: flex; gap: 12px;">
                <button wire:click="cancelDelete" 
                    style="flex: 1; background: #fff; border: 1.5px solid #e5e7eb; color: #6b7280; font-weight: 600; padding: 10px 22px; border-radius: 10px; cursor: pointer;">
                    Отмена
                </button>
                <button wire:click="delete" 
                    style="flex: 1; background: #ef4444; border: none; color: #fff; font-weight: 600; padding: 10px 22px; border-radius: 10px; cursor: pointer;"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Удалить</span>
                    <span wire:loading>Удаление...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
