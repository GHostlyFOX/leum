<div>
    <style>
        .invite-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.2s;
        }
        .invite-card:hover {
            border-color: #c3dba0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-active {
            background: #dcfce7;
            color: #166534;
        }
        .status-expired {
            background: #f3f4f6;
            color: #6b7280;
        }
        .status-limited {
            background: #fef3c7;
            color: #92400e;
        }
        .invite-link-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            font-family: monospace;
            font-size: 0.875rem;
            word-break: break-all;
        }
    </style>

    <!-- Page Header -->
    <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('club.index') }}">Клуб</a></li>
                    <li class="breadcrumb-item active">Приглашения</li>
                </ol>
            </nav>
            <h1 class="page-title fw-bold">Приглашения</h1>
        </div>
        <button class="btn btn-success" onclick="Livewire.dispatch('open-invite-modal')">
            <i class="fe fe-plus me-2"></i>Создать приглашение
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 50px; height: 50px; background: #f0fdf4; color: #8fbd56;">
                        <i class="fe fe-link fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                        <small class="text-muted">Всего приглашений</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 50px; height: 50px; background: #dcfce7; color: #16a34a;">
                        <i class="fe fe-check-circle fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $stats['active'] }}</h3>
                        <small class="text-muted">Активных</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 50px; height: 50px; background: #dbeafe; color: #3b82f6;">
                        <i class="fe fe-user-check fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $stats['used'] }}</h3>
                        <small class="text-muted">Использовано</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px;">
        <div class="card-body p-3">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Статус</label>
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="all">Все статусы</option>
                        <option value="active">Активные</option>
                        <option value="expired">Истёкшие</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">Тип</label>
                    <select wire:model.live="typeFilter" class="form-select">
                        <option value="all">Все типы</option>
                        <option value="coach">Тренеры</option>
                        <option value="player">Игроки</option>
                        <option value="parent">Родители</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Invites List -->
    <div class="row g-4">
        @forelse($invites as $invite)
            @php
                $isExpired = $invite->expires_at && $invite->expires_at <= now();
                $isLimited = $invite->max_uses && $invite->used_count >= $invite->max_uses;
                $isActive = !$isExpired && !$isLimited;
            @endphp
            <div class="col-12">
                <div class="invite-card">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 48px; height: 48px; background: {{ match($invite->role) { 'coach' => '#dbeafe', 'parent' => '#fef3c7', default => '#f0fdf4' } }}; color: {{ match($invite->role) { 'coach' => '#3b82f6', 'parent' => '#f59e0b', default => '#8fbd56' } }};">
                                    <i class="fe fe-{{ match($invite->role) { 'coach' => 'user-check', 'parent' => 'heart', default => 'users' } }} fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $invite->team?->name ?? 'Команда' }}</h6>
                                    <span class="badge bg-light text-dark">
                                        {{ match($invite->role) {
                                            'coach' => 'Тренер',
                                            'player' => 'Игрок',
                                            'parent' => 'Родитель',
                                            default => $invite->role
                                        } }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="invite-link-box mb-2">
                                {{ route('join.team', $invite->token) }}
                            </div>
                            <small class="text-muted">
                                <i class="fe fe-calendar me-1"></i>
                                Создано: {{ $invite->created_at->format('d.m.Y') }}
                                @if($invite->expires_at)
                                    • Истекает: {{ $invite->expires_at->format('d.m.Y') }}
                                @endif
                            </small>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="text-center">
                                    <div class="fw-bold fs-4" style="color: {{ $isActive ? '#16a34a' : '#6b7280' }};">{{ $invite->used_count }}</div>
                                    <small class="text-muted">использовано</small>
                                </div>
                                @if($invite->max_uses)
                                    <div class="text-muted">/</div>
                                    <div class="text-center">
                                        <div class="fw-bold fs-4">{{ $invite->max_uses }}</div>
                                        <small class="text-muted">лимит</small>
                                    </div>
                                @else
                                    <div class="text-muted">/</div>
                                    <div class="text-center">
                                        <div class="fw-bold fs-4 text-success">∞</div>
                                        <small class="text-muted">лимит</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex align-items-center justify-content-end gap-2">
                                @if($isActive)
                                    <span class="status-badge status-active">
                                        <i class="fe fe-check-circle me-1"></i>Активно
                                    </span>
                                @elseif($isLimited)
                                    <span class="status-badge status-limited">
                                        <i class="fe fe-layers me-1"></i>Лимит
                                    </span>
                                @else
                                    <span class="status-badge status-expired">
                                        <i class="fe fe-clock me-1"></i>Истекло
                                    </span>
                                @endif
                            </div>
                            <div class="text-end mt-2">
                                <small class="text-muted">
                                    {{ $invite->creator?->full_name ?? 'Система' }}
                                </small>
                            </div>
                        </div>
                        <div class="col-12 mt-3 pt-3 border-top">
                            <div class="d-flex gap-2 justify-content-end">
                                <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('{{ route('join.team', $invite->token) }}')">
                                    <i class="fe fe-copy me-1"></i>Копировать ссылку
                                </button>
                                @if($isActive)
                                    <button class="btn btn-sm btn-outline-danger" wire:click="confirmRevoke({{ $invite->id }})">
                                        <i class="fe fe-trash-2 me-1"></i>Отозвать
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" class="mb-3">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <h5 class="text-muted">Нет приглашений</h5>
                    <p class="text-muted mb-3">Создайте первое приглашение, чтобы пригласить участников</p>
                    <button class="btn btn-success" onclick="Livewire.dispatch('open-invite-modal')">
                        <i class="fe fe-plus me-2"></i>Создать приглашение
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $invites->links() }}
    </div>

    <!-- Revoke Confirmation Modal -->
    @if($showRevokeConfirm)
        <div class="modal show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 16px; border: none;">
                    <div class="modal-header border-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold text-danger">
                            <i class="fe fe-alert-triangle me-2"></i>Отозвать приглашение?
                        </h5>
                        <button type="button" class="btn-close" wire:click="cancelRevoke"></button>
                    </div>
                    <div class="modal-body px-4">
                        <p class="text-muted mb-0">
                            Ссылка станет недействительной и больше не будет работать для новых регистраций.
                            <br><br>
                            <span class="text-danger">Это действие нельзя отменить.</span>
                        </p>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4">
                        <button type="button" class="btn btn-secondary" wire:click="cancelRevoke">
                            Отмена
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="revokeInvite">
                            <span wire:loading.remove wire:target="revokeInvite">Отозвать</span>
                            <span wire:loading wire:target="revokeInvite">Отзыв...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Можно добавить toast уведомление
                alert('Ссылка скопирована в буфер обмена');
            }, function(err) {
                console.error('Ошибка копирования:', err);
            });
        }
    </script>
    @endpush
</div>
