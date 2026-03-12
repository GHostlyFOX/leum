<div>
    <div class="container py-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0">Заявки на вступление</h1>
            @if($pendingCount > 0)
                <span class="badge bg-warning text-dark rounded-pill">{{ $pendingCount }} ожидают</span>
            @endif
        </div>

        @if($requests->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="ti ti-inbox text-muted fs-1 mb-3"></i>
                    <h5 class="text-muted">Нет заявок</h5>
                    <p class="text-muted mb-0">Заявки от тренеров, игроков и родителей будут отображаться здесь</p>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm">
                <div class="list-group list-group-flush">
                    @foreach($requests as $request)
                        <div class="list-group-item p-4">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    @if($request->status === 'pending')
                                        <span class="avatar bg-warning text-dark">
                                            <i class="ti ti-clock"></i>
                                        </span>
                                    @elseif($request->status === 'approved')
                                        <span class="avatar bg-success text-white">
                                            <i class="ti ti-check"></i>
                                        </span>
                                    @else
                                        <span class="avatar bg-danger text-white">
                                            <i class="ti ti-x"></i>
                                        </span>
                                    @endif
                                </div>
                                <div class="col">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h6 class="mb-0">{{ $request->user->full_name }}</h6>
                                        <span class="badge bg-{{ $request->type === 'club' ? 'info' : 'primary' }} text-white">
                                            {{ $request->type === 'club' ? 'Тренер' : 'Игрок/Родитель' }}
                                        </span>
                                    </div>
                                    <p class="text-muted mb-1">
                                        {{ $request->club->name }}
                                        @if($request->team)
                                            → {{ $request->team->name }}
                                        @endif
                                    </p>
                                    @if($request->message)
                                        <p class="text-muted mb-0 small">
                                            <i class="ti ti-message-circle me-1"></i>{{ $request->message }}
                                        </p>
                                    @endif
                                    <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="col-auto">
                                    @if($request->status === 'pending')
                                        <div class="d-flex gap-2">
                                            <button wire:click="approve({{ $request->id }})" 
                                                    wire:loading.attr="disabled"
                                                    class="btn btn-success btn-sm">
                                                <i class="ti ti-check me-1"></i>Одобрить
                                            </button>
                                            <button wire:click="openRejectModal({{ $request->id }})" 
                                                    class="btn btn-outline-danger btn-sm">
                                                <i class="ti ti-x me-1"></i>Отклонить
                                            </button>
                                        </div>
                                    @else
                                        <span class="badge bg-{{ $request->status === 'approved' ? 'success' : 'secondary' }}">
                                            {{ $request->status === 'approved' ? 'Одобрено' : 'Отклонено' }}
                                        </span>
                                        @if($request->admin_notes)
                                            <p class="text-muted mb-0 small mt-1">
                                                <i class="ti ti-notes me-1"></i>{{ $request->admin_notes }}
                                            </p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Reject Modal -->
    @if($showRejectModal)
        <div class="modal show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Отклонить заявку</h5>
                        <button type="button" class="btn-close" wire:click="closeRejectModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Причина отклонения (опционально)</label>
                            <textarea wire:model="adminNotes" class="form-control" rows="3" 
                                      placeholder="Например: Команда заполнена, уточните данные и т.д."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link text-muted" wire:click="closeRejectModal">
                            Отмена
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="reject" wire:loading.attr="disabled">
                            <span wire:loading.remove>Отклонить</span>
                            <span wire:loading>Отклонение...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
