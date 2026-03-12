<div>
<style>
    .season-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 20px;
        transition: all 0.2s;
    }
    .season-card:hover { border-color: #c7d2fe; box-shadow: 0 4px 16px rgba(99,102,241,0.06); }
    .season-status {
        display: inline-flex;
        align-items: center;
        padding: 3px 10px;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        gap: 4px;
    }
    .season-status.planned { background: #fef3c7; color: #92400e; }
    .season-status.active  { background: #dcfce7; color: #16a34a; }
    .season-status.archived { background: #f3f4f6; color: #6b7280; }
    .season-dates {
        font-size: 0.85rem; color: #6b7280; margin-top: 4px;
    }
    .season-teams-list {
        display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px;
    }
    .season-teams-list .team-badge {
        background: #eef2ff; color: #6366f1;
        padding: 2px 10px; border-radius: 6px;
        font-size: 0.78rem; font-weight: 600;
    }
    .season-actions {
        display: flex; gap: 8px; margin-top: 14px;
    }
    .btn-edit-season {
        background: #fff; border: 1.5px solid #e5e7eb;
        color: #374151; font-weight: 600;
        padding: 6px 16px; border-radius: 8px;
        font-size: 0.82rem; cursor: pointer;
        transition: all 0.2s;
    }
    .btn-edit-season:hover { border-color: #6366f1; color: #6366f1; }
    .btn-delete-season {
        background: #fff; border: 1.5px solid #fecaca;
        color: #dc2626; font-weight: 600;
        padding: 6px 16px; border-radius: 8px;
        font-size: 0.82rem; cursor: pointer;
        transition: all 0.2s;
    }
    .btn-delete-season:hover { background: #fef2f2; border-color: #dc2626; }
    .btn-new-season {
        background: #6366f1; border: none; color: #fff;
        font-weight: 600; padding: 10px 22px; border-radius: 10px;
        font-size: 0.9rem; cursor: pointer; transition: all 0.2s;
    }
    .btn-new-season:hover { background: #4f46e5; }
    .filter-btn {
        background: #fff; border: 1.5px solid #e5e7eb;
        color: #6b7280; font-weight: 600;
        padding: 6px 14px; border-radius: 8px;
        font-size: 0.82rem; cursor: pointer;
        transition: all 0.2s;
    }
    .filter-btn:hover { border-color: #6366f1; color: #6366f1; }
    .filter-btn.active { background: #6366f1; color: #fff; border-color: #6366f1; }
    .modal-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 1050;
        display: flex; align-items: center; justify-content: center;
    }
    .modal-dialog-custom {
        background: #fff; border-radius: 16px;
        width: 100%; max-width: 520px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        overflow: hidden;
        animation: slideUp 0.25s ease-out;
    }
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .modal-dialog-custom .modal-header-custom {
        padding: 20px 24px 0;
        display: flex; justify-content: space-between; align-items: center;
    }
    .modal-dialog-custom .modal-header-custom h5 {
        font-weight: 700; font-size: 1.15rem; margin: 0;
    }
    .modal-dialog-custom .modal-body-custom { padding: 20px 24px; }
    .modal-dialog-custom .modal-footer-custom {
        padding: 16px 24px 20px;
        display: flex; gap: 10px;
    }
    .modal-dialog-custom label {
        font-weight: 600; font-size: 0.88rem;
        color: #374151; display: block; margin-bottom: 6px;
    }
    .modal-dialog-custom .form-control,
    .modal-dialog-custom .form-select {
        border-radius: 10px; border: 1.5px solid #e5e7eb;
        padding: 10px 14px; font-size: 0.9rem;
    }
    .modal-dialog-custom .form-control:focus,
    .modal-dialog-custom .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }
    .btn-modal-cancel {
        flex: 1; background: #fff; border: 1.5px solid #e5e7eb;
        color: #374151; font-weight: 600;
        padding: 10px; border-radius: 10px; cursor: pointer; font-size: 0.9rem;
    }
    .btn-modal-cancel:hover { background: #f9fafb; }
    .btn-modal-save {
        flex: 1; background: #6366f1; border: none;
        color: #fff; font-weight: 600;
        padding: 10px; border-radius: 10px; cursor: pointer; font-size: 0.9rem;
    }
    .btn-modal-save:hover { background: #4f46e5; }
    .btn-close-modal {
        background: none; border: none; color: #9ca3af; cursor: pointer;
    }
    .btn-close-modal:hover { color: #374151; }
    .delete-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1060;
        display: flex; align-items: center; justify-content: center;
    }
    .delete-dialog {
        background: #fff; border-radius: 16px;
        max-width: 440px; width: 100%;
        padding: 32px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        text-align: center;
        animation: slideUp 0.2s ease-out;
    }
    .delete-dialog .delete-icon {
        width: 56px; height: 56px;
        background: #fef2f2; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px; color: #dc2626;
    }
    .delete-dialog h5 { font-weight: 700; margin-bottom: 8px; }
    .delete-dialog p { color: #6b7280; font-size: 0.9rem; line-height: 1.5; }
    .delete-dialog .warning-box {
        background: #fef3c7; border: 1px solid #fde68a;
        border-radius: 10px; padding: 12px 16px;
        font-size: 0.85rem; color: #92400e;
        margin: 16px 0; text-align: left;
    }
    .btn-delete-confirm {
        background: #dc2626; border: none; color: #fff;
        font-weight: 600; padding: 10px 24px; border-radius: 10px;
        cursor: pointer; font-size: 0.9rem; transition: all 0.2s;
    }
    .btn-delete-confirm:hover { background: #b91c1c; }
    .btn-delete-cancel {
        background: #fff; border: 1.5px solid #e5e7eb;
        color: #374151; font-weight: 600;
        padding: 10px 24px; border-radius: 10px;
        cursor: pointer; font-size: 0.9rem; transition: all 0.2s;
    }
    .btn-delete-cancel:hover { background: #f9fafb; }
    .empty-seasons {
        text-align: center; padding: 60px 20px; color: #9ca3af;
    }
    .empty-seasons h5 { color: #374151; font-weight: 600; margin-bottom: 8px; }
    .empty-seasons p { font-size: 0.9rem; margin-bottom: 20px; }
    .team-checkbox-list {
        display: flex; flex-wrap: wrap; gap: 8px;
    }
    .team-checkbox-item {
        display: flex; align-items: center; gap: 6px;
        background: #f9fafb; border: 1px solid #e5e7eb;
        padding: 6px 12px; border-radius: 8px;
        cursor: pointer; font-size: 0.85rem;
        transition: all 0.2s;
    }
    .team-checkbox-item:hover { border-color: #6366f1; }
    .team-checkbox-item input[type=checkbox] { accent-color: #6366f1; }
</style>

<!-- PAGE HEADER -->
<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title fw-bold">Сезоны</h1>
        <p class="text-muted mb-0">Управление сезонами клуба</p>
    </div>
    <button wire:click="openCreateModal" class="btn-new-season">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1" style="vertical-align: -2px;">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Новый сезон
    </button>
</div>

<!-- FILTERS -->
<div class="d-flex gap-2 mb-4 flex-wrap">
    <button wire:click="$set('filterStatus', '')" class="filter-btn {{ $filterStatus === '' ? 'active' : '' }}">Все</button>
    <button wire:click="$set('filterStatus', 'planned')" class="filter-btn {{ $filterStatus === 'planned' ? 'active' : '' }}">Запланированные</button>
    <button wire:click="$set('filterStatus', 'active')" class="filter-btn {{ $filterStatus === 'active' ? 'active' : '' }}">Активные</button>
    <button wire:click="$set('filterStatus', 'archived')" class="filter-btn {{ $filterStatus === 'archived' ? 'active' : '' }}">Архивные</button>
</div>

<!-- SEASONS LIST -->
@if($seasons->isEmpty())
    <div class="card border-0 shadow-sm" style="border-radius: 14px;">
        <div class="card-body">
            <div class="empty-seasons">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" class="mb-3">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <h5>Нет сезонов</h5>
                <p>Создайте первый сезон для организации расписания вашего клуба.</p>
                <button wire:click="openCreateModal" class="btn-new-season">Создать сезон</button>
            </div>
        </div>
    </div>
@else
    <div class="row g-3">
        @foreach($seasons as $season)
            <div class="col-lg-6 col-12">
                <div class="season-card">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <h5 class="fw-bold mb-1" style="font-size: 1.05rem;">{{ $season->name }}</h5>
                            <span class="season-status {{ $season->status }}">
                                @if($season->status === 'active')
                                    <svg width="10" height="10" viewBox="0 0 10 10"><circle cx="5" cy="5" r="5" fill="currentColor"/></svg>
                                @endif
                                {{ match($season->status) { 'planned' => 'Запланирован', 'active' => 'Активный', 'archived' => 'Архив', default => $season->status } }}
                            </span>
                        </div>
                    </div>

                    <div class="season-dates">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: -2px;">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                        </svg>
                        {{ $season->start_date?->format('d.m.Y') }} — {{ $season->end_date?->format('d.m.Y') }}
                    </div>

                    @if($season->teams->isNotEmpty())
                        <div class="season-teams-list">
                            @foreach($season->teams as $team)
                                <span class="team-badge">{{ $team->name }}</span>
                            @endforeach
                        </div>
                    @else
                        <div style="margin-top: 10px; font-size: 0.82rem; color: #9ca3af;">
                            Нет привязанных команд
                        </div>
                    @endif

                    <div class="season-actions">
                        <button wire:click="openEditModal({{ $season->id }})" class="btn-edit-season">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1" style="vertical-align: -2px;">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            Редактировать
                        </button>
                        <button wire:click="confirmDelete({{ $season->id }})" class="btn-delete-season">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1" style="vertical-align: -2px;">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            Удалить
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- ═══════════════ CREATE / EDIT MODAL ═══════════════ --}}
@if($showModal)
<div class="modal-overlay" wire:click.self="closeModal">
    <div class="modal-dialog-custom" @click.stop>
        <div class="modal-header-custom">
            <h5>{{ $isEditing ? 'Редактировать сезон' : 'Новый сезон' }}</h5>
            <button class="btn-close-modal" wire:click="closeModal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="modal-body-custom">
            <div class="mb-3">
                <label for="season-name">Название сезона</label>
                <input type="text" id="season-name" class="form-control" wire:model="name" placeholder="Сезон 2025/2026">
                @error('name') <span class="text-danger" style="font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label for="season-start">Дата начала</label>
                    <input type="date" id="season-start" class="form-control" wire:model="startDate">
                    @error('startDate') <span class="text-danger" style="font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>
                <div class="col-6">
                    <label for="season-end">Дата окончания</label>
                    <input type="date" id="season-end" class="form-control" wire:model="endDate">
                    @error('endDate') <span class="text-danger" style="font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="season-status">Статус</label>
                <select id="season-status" class="form-select" wire:model="status">
                    <option value="planned">Запланирован</option>
                    <option value="active">Активный</option>
                    <option value="archived">Архив</option>
                </select>
            </div>

            @if($teams->isNotEmpty())
            <div class="mb-0">
                <label>Команды сезона</label>
                <div class="team-checkbox-list">
                    @foreach($teams as $team)
                        <label class="team-checkbox-item">
                            <input type="checkbox" value="{{ $team->id }}" wire:model="selectedTeamIds">
                            {{ $team->name }}
                        </label>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="modal-footer-custom">
            <button class="btn-modal-cancel" wire:click="closeModal">Отмена</button>
            <button class="btn-modal-save" wire:click="save" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">{{ $isEditing ? 'Сохранить' : 'Создать' }}</span>
                <span wire:loading wire:target="save">Сохранение...</span>
            </button>
        </div>
    </div>
</div>
@endif

{{-- ═══════════════ DELETE CONFIRMATION ═══════════════ --}}
@if($showDeleteConfirm)
<div class="delete-overlay" wire:click.self="cancelDelete">
    <div class="delete-dialog" @click.stop>
        <div class="delete-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        </div>
        <h5>Удалить сезон?</h5>
        <p>Вы собираетесь удалить сезон «{{ $deletingSeasonName }}».</p>

        <div class="warning-box">
            <strong>Внимание!</strong> При удалении сезона будут также удалены все связанные с ним тренировки и турниры. Это действие необратимо.
        </div>

        <div class="d-flex gap-3 justify-content-center mt-3">
            <button class="btn-delete-cancel" wire:click="cancelDelete">Отмена</button>
            <button class="btn-delete-confirm" wire:click="deleteSeason" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="deleteSeason">Удалить сезон</span>
                <span wire:loading wire:target="deleteSeason">Удаление...</span>
            </button>
        </div>
    </div>
</div>
@endif

</div>
