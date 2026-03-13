<div>
    @if($requests->isNotEmpty())
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; border: 1px solid #e8f5d6;">
            <div class="card-header bg-white border-0 pt-4 pb-3 px-4" style="border-radius: 16px 16px 0 0;">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <line x1="16" y1="3.13" x2="16" y2="3.13"></line>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold" style="color: #2d4a14;">
                                Заявки на вступление
                                <span class="badge bg-warning text-dark ms-2" style="font-size: 0.75rem;">{{ $requests->count() }}</span>
                            </h5>
                            <small class="text-muted">Ожидают рассмотрения</small>
                        </div>
                    </div>
                    <a href="{{ route('join.requests') }}" class="btn btn-sm btn-outline-success">
                        Все заявки
                    </a>
                </div>
            </div>
            <div class="card-body px-4 pb-4 pt-0">
                <div class="list-group list-group-flush">
                    @foreach($requests->take(5) as $request)
                        <div class="list-group-item px-0 py-3 border-bottom" style="border-color: #f3f4f6;">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    @if($request->user?->photo_file_id)
                                        <img src="{{ Storage::url($request->user->photo_file_id) }}" 
                                             class="rounded-circle" width="44" height="44" alt="">
                                    @else
                                        <div style="width: 44px; height: 44px; background: linear-gradient(135deg, #8fbd56 0%, #6d9e3a 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: 1rem;">
                                            {{ mb_strtoupper(mb_substr($request->user?->first_name ?? '?', 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h6 class="mb-0 fw-semibold" style="color: #1f2937;">{{ $request->user?->full_name ?? 'Неизвестный пользователь' }}</h6>
                                        <span class="badge bg-{{ $request->type === 'club' ? 'info' : 'primary' }} text-white" style="font-size: 0.7rem;">
                                            {{ $request->type === 'club' ? 'Тренер' : 'Игрок/Родитель' }}
                                        </span>
                                    </div>
                                    <p class="text-muted mb-0 small">
                                        {{ $request->club?->name }}
                                        @if($request->team)
                                            <span class="mx-1">→</span> {{ $request->team->name }}
                                        @endif
                                    </p>
                                    <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="col-auto">
                                    <div class="d-flex gap-2">
                                        <button wire:click="approve({{ $request->id }})" 
                                                wire:loading.attr="disabled"
                                                class="btn btn-sm btn-success d-flex align-items-center gap-1"
                                                style="background: #8fbd56; border: none;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                            <span wire:loading.remove wire:target="approve({{ $request->id }})">Принять</span>
                                            <span wire:loading wire:target="approve({{ $request->id }})">...</span>
                                        </button>
                                        <button wire:click="reject({{ $request->id }})" 
                                                wire:loading.attr="disabled"
                                                class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg>
                                            <span wire:loading.remove wire:target="reject({{ $request->id }})">Отклонить</span>
                                            <span wire:loading wire:target="reject({{ $request->id }})">...</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($requests->count() > 5)
                    <div class="text-center mt-3">
                        <a href="{{ route('join.requests') }}" class="text-decoration-none" style="color: #8fbd56; font-weight: 500;">
                            +{{ $requests->count() - 5 }} ещё заявок
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
