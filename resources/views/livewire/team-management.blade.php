<div>
    <style>
        .team-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
            transition: all 0.2s;
        }
        .team-card:hover {
            border-color: #c3dba0;
            box-shadow: 0 4px 20px rgba(143, 189, 86, 0.1);
        }
        .team-avatar {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 1.4rem;
        }
        .btn-create {
            background: #8fbd56;
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .btn-create:hover {
            background: #6d9e3a;
            color: #fff;
        }
        .btn-edit {
            background: #f3f4f6;
            border: none;
            color: #6b7280;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
        }
        .btn-edit:hover {
            background: #e5e7eb;
            color: #374151;
        }
        .btn-delete {
            background: #fef2f2;
            border: none;
            color: #dc2626;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
        }
        .btn-delete:hover {
            background: #fee2e2;
        }
        .color-picker-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.2s;
        }
        .color-picker-circle:hover {
            transform: scale(1.1);
        }
        .color-picker-circle.selected {
            border-color: #1f2937;
        }
    </style>

    <!-- Page Header -->
    <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('club.index') }}">Клуб</a></li>
                    <li class="breadcrumb-item active">Команды</li>
                </ol>
            </nav>
            <h1 class="page-title fw-bold">Команды</h1>
        </div>
        <button wire:click="openCreateForm" class="btn btn-create">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: text-bottom; margin-right: 6px;">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Добавить команду
        </button>
    </div>

    <!-- Teams Grid -->
    <div class="row g-4">
        @forelse($teams as $team)
            <div class="col-md-6 col-lg-4">
                <div class="team-card h-100">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="team-avatar" style="background: {{ $team->team_color ?? '#8fbd56' }};">
                                {{ mb_strtoupper(mb_substr($team->name, 0, 1)) }}
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1" style="color: #1f2937;">{{ $team->name }}</h5>
                                <small class="text-muted">{{ $team->birth_year }} г.р. • {{ match($team->gender) { 'boys' => 'Мальчики', 'girls' => 'Девочки', 'mixed' => 'Смешанная', default => 'Не указан' } }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-4 mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8fbd56" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                            </svg>
                            <span class="fw-semibold" style="color: #4a7a25;">{{ $team->members_count }}</span>
                            <span class="text-muted">
                                @php
                                    $count = $team->members_count;
                                    $lastDigit = $count % 10;
                                    $lastTwoDigits = $count % 100;
                                    if ($lastTwoDigits >= 11 && $lastTwoDigits <= 19) {
                                        echo 'игроков';
                                    } elseif ($lastDigit === 1) {
                                        echo 'игрок';
                                    } elseif ($lastDigit >= 2 && $lastDigit <= 4) {
                                        echo 'игрока';
                                    } else {
                                        echo 'игроков';
                                    }
                                @endphp
                            </span>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('club.team.show', $team->id) }}" class="btn btn-edit flex-fill">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: text-bottom; margin-right: 4px;">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            Подробнее
                        </a>
                        <button wire:click="openEditForm({{ $team->id }})" class="btn btn-edit">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                        <button wire:click="confirmDelete({{ $team->id }})" class="btn btn-delete">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" class="mb-3">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <h5 class="text-muted">Нет команд</h5>
                    <p class="text-muted mb-3">Создайте первую команду, чтобы начать</p>
                    <button wire:click="openCreateForm" class="btn btn-create">Создать команду</button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $teams->links() }}
    </div>

    <!-- Create/Edit Modal -->
    @if($showForm)
        <div class="modal show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 16px; border: none;">
                    <div class="modal-header border-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold" style="color: #2d4a14;">
                            {{ $editingTeamId ? 'Редактировать команду' : 'Новая команда' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeForm"></button>
                    </div>
                    <div class="modal-body px-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Название команды <span class="text-danger">*</span></label>
                            <input type="text" wire:model="teamName" class="form-control @error('teamName') is-invalid @enderror" 
                                   style="border-radius: 10px; border: 2px solid #e5e7eb; padding: 12px 16px;"
                                   placeholder="Например: U-12">
                            @error('teamName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Год рождения <span class="text-danger">*</span></label>
                                <input type="number" wire:model="teamBirthYear" class="form-control @error('teamBirthYear') is-invalid @enderror"
                                       style="border-radius: 10px; border: 2px solid #e5e7eb; padding: 12px 16px;"
                                       placeholder="2012">
                                @error('teamBirthYear') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Пол <span class="text-danger">*</span></label>
                                <select wire:model="teamGender" class="form-select @error('teamGender') is-invalid @enderror"
                                        style="border-radius: 10px; border: 2px solid #e5e7eb; padding: 12px 16px;">
                                    <option value="boys">Мальчики</option>
                                    <option value="girls">Девочки</option>
                                    <option value="mixed">Смешанная</option>
                                </select>
                                @error('teamGender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Цвет команды</label>
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach(['#8fbd56', '#3b82f6', '#ef4444', '#f59e0b', '#8b5cf6', '#ec4899', '#6b7280'] as $color)
                                    <div class="color-picker-circle {{ $teamColor === $color ? 'selected' : '' }}"
                                         style="background: {{ $color }};"
                                         wire:click="$set('teamColor', '{{ $color }}')">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4">
                        <button type="button" class="btn btn-secondary" wire:click="closeForm" style="background: #f3f4f6; border: none; color: #6b7280;">
                            Отмена
                        </button>
                        <button type="button" class="btn btn-success" wire:click="saveTeam" 
                                style="background: #8fbd56; border: none; color: #fff; font-weight: 600;">
                            <span wire:loading.remove wire:target="saveTeam">{{ $editingTeamId ? 'Сохранить' : 'Создать' }}</span>
                            <span wire:loading wire:target="saveTeam">Сохранение...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteConfirm)
        <div class="modal show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 16px; border: none;">
                    <div class="modal-header border-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold text-danger">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: text-bottom; margin-right: 6px;">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            Подтвердите удаление
                        </h5>
                        <button type="button" class="btn-close" wire:click="cancelDelete"></button>
                    </div>
                    <div class="modal-body px-4">
                        <p class="text-muted mb-0">
                            Вы действительно хотите удалить команду <strong>«{{ $teams->firstWhere('id', $deletingTeamId)?->name }}»</strong>?
                            <br><br>
                            <span class="text-danger">Это действие нельзя отменить.</span>
                        </p>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4">
                        <button type="button" class="btn btn-secondary" wire:click="cancelDelete" style="background: #f3f4f6; border: none; color: #6b7280;">
                            Отмена
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="deleteTeam" style="background: #dc2626; border: none; color: #fff; font-weight: 600;">
                            <span wire:loading.remove wire:target="deleteTeam">Удалить</span>
                            <span wire:loading wire:target="deleteTeam">Удаление...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
