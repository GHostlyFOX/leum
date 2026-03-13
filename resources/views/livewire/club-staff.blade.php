<div>
    <style>
        .staff-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 20px;
            transition: all 0.2s;
        }
        .staff-card:hover {
            border-color: #c3dba0;
            box-shadow: 0 4px 20px rgba(143, 189, 86, 0.1);
        }
        .staff-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 1.5rem;
        }
        .role-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .role-admin {
            background: #fef3c7;
            color: #d97706;
        }
        .role-coach {
            background: #dbeafe;
            color: #2563eb;
        }
        .role-assistant {
            background: #f3e8ff;
            color: #9333ea;
        }
    </style>

    <!-- Page Header -->
    <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('club.index') }}">Клуб</a></li>
                    <li class="breadcrumb-item active">Сотрудники</li>
                </ol>
            </nav>
            <h1 class="page-title fw-bold">Сотрудники клуба</h1>
        </div>
        <button class="btn btn-success" onclick="Livewire.dispatch('open-invite-modal')">
            <i class="fe fe-user-plus me-2"></i>Пригласить сотрудника
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="fw-bold mb-0" style="color: #92400e;">{{ $stats['admins'] }}</h3>
                        <small style="color: #b45309;">Администраторов</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 50px; height: 50px; background: rgba(255,255,255,0.5); color: #d97706;">
                        <i class="fe fe-shield fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="fw-bold mb-0" style="color: #1e40af;">{{ $stats['coaches'] }}</h3>
                        <small style="color: #1d4ed8;">Главных тренеров</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 50px; height: 50px; background: rgba(255,255,255,0.5); color: #3b82f6;">
                        <i class="fe fe-user-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px; background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="fw-bold mb-0" style="color: #6b21a8;">{{ $stats['assistants'] }}</h3>
                        <small style="color: #7e22ce;">Помощников тренера</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 50px; height: 50px; background: rgba(255,255,255,0.5); color: #a855f7;">
                        <i class="fe fe-users fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px;">
        <div class="card-body p-3">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fe fe-search text-muted"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0" placeholder="Поиск по имени или email...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="roleFilter" class="form-select">
                        <option value="all">Все роли</option>
                        <option value="7">Администраторы</option>
                        <option value="8">Тренеры</option>
                        <option value="11">Помощники</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Grid -->
    <div class="row g-4">
        @forelse($staff as $member)
            <div class="col-md-6 col-lg-4">
                <div class="staff-card h-100">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="staff-avatar" style="background: linear-gradient(135deg, {{ match($member->role_id) { 7 => '#f59e0b', 8 => '#3b82f6', default => '#8b5cf6' } }} 0%, {{ match($member->role_id) { 7 => '#d97706', 8 => '#2563eb', default => '#7c3aed' } }} 100%);">
                                {{ mb_strtoupper(mb_substr($member->user?->first_name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1" style="color: #1f2937;">{{ $member->user?->full_name ?? 'Неизвестно' }}</h6>
                                <span class="role-badge {{ match($member->role_id) { 7 => 'role-admin', 8 => 'role-coach', default => 'role-assistant' } }}">
                                    {{ match($member->role_id) {
                                        7 => 'Администратор',
                                        8 => 'Главный тренер',
                                        11 => 'Помощник тренера',
                                        default => 'Сотрудник'
                                    } }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-2 text-muted mb-1">
                            <i class="fe fe-mail fs-14"></i>
                            <small>{{ $member->user?->email ?? '-' }}</small>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-muted mb-1">
                            <i class="fe fe-phone fs-14"></i>
                            <small>{{ $member->user?->phone ?? 'Телефон не указан' }}</small>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-muted">
                            <i class="fe fe-calendar fs-14"></i>
                            <small>В клубе с {{ $member->joined_at?->format('d.m.Y') ?? '-' }}</small>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-sm btn-outline-secondary flex-fill">
                            <i class="fe fe-user me-1"></i>Профиль
                        </a>
                        @if($member->role_id !== 7)
                            <button class="btn btn-sm btn-outline-danger" wire:click="removeStaff({{ $member->id }})">
                                <i class="fe fe-user-x"></i>
                            </button>
                        @endif
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
                    <h5 class="text-muted">Нет сотрудников</h5>
                    <p class="text-muted mb-3">Пригласите администраторов и тренеров в клуб</p>
                    <button class="btn btn-success" onclick="Livewire.dispatch('open-invite-modal')">
                        <i class="fe fe-user-plus me-2"></i>Пригласить сотрудника
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $staff->links() }}
    </div>
</div>
