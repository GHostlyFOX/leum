@extends('layouts.app')

@section('content')
<style>
    .wizard-stepper { display: flex; justify-content: center; margin-bottom: 2rem; }
    .wizard-step { display: flex; align-items: center; }
    .wizard-step + .wizard-step::before {
        content: '';
        width: 60px;
        height: 2px;
        background: #dee2e6;
        margin: 0 8px;
    }
    .wizard-step.completed + .wizard-step::before,
    .wizard-step.active + .wizard-step::before { background: #8fbd56; }
    .step-circle {
        width: 44px; height: 44px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 600; font-size: 1rem;
        border: 2px solid #dee2e6;
        color: #adb5bd;
        background: #fff;
        transition: all 0.3s;
    }
    .wizard-step.active .step-circle {
        border-color: #8fbd56;
        background: #8fbd56;
        color: #fff;
    }
    .wizard-step.completed .step-circle {
        border-color: #8fbd56;
        background: #e8f5d6;
        color: #8fbd56;
    }
    .step-label { font-size: 0.8rem; color: #adb5bd; margin-top: 6px; text-align: center; }
    .wizard-step.active .step-label { color: #2d4a14; font-weight: 600; }
    .wizard-step.completed .step-label { color: #6d9e3a; }
    .onboarding-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.06);
        padding: 32px;
    }
    .team-badge {
        display: inline-flex; align-items: center;
        background: #e8f5d6; color: #2d4a14;
        padding: 6px 14px; border-radius: 20px;
        font-size: 0.85rem; font-weight: 500;
        margin-right: 8px; margin-bottom: 8px;
    }
    .team-badge .remove-btn { margin-left: 8px; cursor: pointer; color: #dc3545; font-weight: 700; }
    .member-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 14px; background: #f8f9fa; border-radius: 8px;
        margin-bottom: 6px; font-size: 0.9rem;
    }
    .member-row .badge-new { background: #fff3cd; color: #856404; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; }
    .tab-team { cursor: pointer; padding: 8px 16px; border-radius: 8px; font-size: 0.9rem; transition: all 0.2s; }
    .tab-team.active { background: #8fbd56; color: #fff; }
    .tab-team:not(.active) { background: #f0f0f0; }
    .tab-team:not(.active):hover { background: #e0e0e0; }
</style>

<div class="container py-4" style="max-width: 800px;">
    {{-- Page header --}}
    <div class="text-center mb-4">
        <h2 class="fw-bold">Создание клуба</h2>
        <p class="text-muted">Заполните информацию шаг за шагом</p>
    </div>

    {{-- Stepper --}}
    <div class="wizard-stepper">
        @php
            $steps = [
                1 => ['label' => 'Клуб',    'icon' => '1'],
                2 => ['label' => 'Команды',  'icon' => '2'],
                3 => ['label' => 'Тренеры',  'icon' => '3'],
                4 => ['label' => 'Игроки',   'icon' => '4'],
            ];
        @endphp
        @foreach ($steps as $num => $s)
            <div class="wizard-step {{ $step > $num ? 'completed' : ($step === $num ? 'active' : '') }}">
                <div class="d-flex flex-column align-items-center">
                    <div class="step-circle">
                        @if ($step > $num)
                            <i class="fe fe-check"></i>
                        @else
                            {{ $s['icon'] }}
                        @endif
                    </div>
                    <div class="step-label">{{ $s['label'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="onboarding-card">

        {{-- ═══════════════════════════════════════════════════
             ШАГ 1: КЛУБ
        ═══════════════════════════════════════════════════ --}}
        @if ($step === 1)
            <h4 class="fw-bold mb-4">Информация о клубе</h4>

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Название клуба <span class="text-danger">*</span></label>
                    <input type="text" wire:model.defer="clubName" class="form-control @error('clubName') is-invalid @enderror"
                           placeholder="Например: ФК «Юные Чемпионы»">
                    @error('clubName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Вид спорта <span class="text-danger">*</span></label>
                    <select wire:model.defer="sportTypeId" class="form-select @error('sportTypeId') is-invalid @enderror">
                        <option value="">— Выберите —</option>
                        @foreach ($sportTypes as $st)
                            <option value="{{ $st->id }}">{{ $st->name }}</option>
                        @endforeach
                    </select>
                    @error('sportTypeId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Тип клуба</label>
                    <select wire:model.defer="clubTypeId" class="form-select">
                        <option value="">— Не указан —</option>
                        @foreach ($clubTypes as $ct)
                            <option value="{{ $ct->id }}">{{ $ct->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Страна <span class="text-danger">*</span></label>
                    <select wire:model="countryId" class="form-select @error('countryId') is-invalid @enderror">
                        <option value="">— Выберите —</option>
                        @foreach ($countries as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('countryId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Город <span class="text-danger">*</span></label>
                    <select wire:model.defer="cityId" class="form-select @error('cityId') is-invalid @enderror"
                            {{ $cities->isEmpty() ? 'disabled' : '' }}>
                        <option value="">— Выберите —</option>
                        @foreach ($cities as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('cityId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Описание</label>
                    <textarea wire:model.defer="clubDescription" class="form-control" rows="3"
                              placeholder="Краткое описание клуба (необязательно)"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email клуба</label>
                    <input type="email" wire:model.defer="clubEmail" class="form-control" placeholder="club@example.com">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Телефон</label>
                    <input type="text" wire:model.defer="clubPhone" class="form-control" placeholder="+7 (___) ___-__-__">
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Логотип</label>
                    <input type="file" wire:model="clubLogo" class="form-control" accept="image/*">
                    @error('clubLogo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="button" wire:click="saveClub" class="btn btn-primary btn-lg px-5">
                    <span wire:loading.remove wire:target="saveClub">Далее</span>
                    <span wire:loading wire:target="saveClub">Сохранение...</span>
                </button>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════════
             ШАГ 2: КОМАНДЫ
        ═══════════════════════════════════════════════════ --}}
        @if ($step === 2)
            <h4 class="fw-bold mb-4">Добавьте команды</h4>
            <p class="text-muted mb-4">Создайте одну или несколько команд для вашего клуба.</p>

            {{-- Existing teams --}}
            @if (count($teams) > 0)
                <div class="mb-4">
                    <label class="form-label fw-semibold text-muted small text-uppercase">Добавленные команды</label>
                    <div class="d-flex flex-wrap">
                        @foreach ($teams as $i => $t)
                            <span class="team-badge">
                                {{ $t['name'] }}
                                <span class="text-muted ms-1">({{ $t['gender'] === 'male' ? 'М' : 'Ж' }}, {{ $t['birth_year'] }} г.р.)</span>
                                <span class="remove-btn" wire:click="removeTeam({{ $i }})" title="Удалить">&times;</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            @error('teams') <div class="alert alert-danger">{{ $message }}</div> @enderror

            {{-- Add team form --}}
            <div class="p-3 bg-light rounded-3">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Название команды</label>
                        <input type="text" wire:model.defer="teamName" class="form-control @error('teamName') is-invalid @enderror"
                               placeholder="Например: U-12">
                        @error('teamName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Пол</label>
                        <select wire:model.defer="teamGender" class="form-select">
                            <option value="male">Мужской</option>
                            <option value="female">Женский</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Год рожд.</label>
                        <input type="number" wire:model.defer="teamBirthYear" class="form-control" min="2000" max="{{ date('Y') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button wire:click="addTeam" class="btn btn-outline-primary w-100">
                            <i class="fe fe-plus"></i> Добавить
                        </button>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" wire:click="prevStep" class="btn btn-light btn-lg px-4">Назад</button>
                <button type="button" wire:click="goToCoaches" class="btn btn-primary btn-lg px-5">Далее</button>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════════
             ШАГ 3: ТРЕНЕРЫ
        ═══════════════════════════════════════════════════ --}}
        @if ($step === 3)
            <h4 class="fw-bold mb-4">Назначьте тренеров</h4>
            <p class="text-muted mb-4">Добавьте тренера к каждой команде. Если тренера ещё нет в системе — он будет создан автоматически.</p>

            {{-- Team tabs --}}
            <div class="d-flex flex-wrap gap-2 mb-4">
                @foreach ($teams as $t)
                    <span class="tab-team {{ $coachTeamId === $t['id'] ? 'active' : '' }}"
                          wire:click="$set('coachTeamId', {{ $t['id'] }})">
                        {{ $t['name'] }}
                        @if (isset($coaches[$t['id']]) && count($coaches[$t['id']]) > 0)
                            <span class="badge bg-white text-success ms-1">{{ count($coaches[$t['id']]) }}</span>
                        @endif
                    </span>
                @endforeach
            </div>

            {{-- Existing coaches for selected team --}}
            @if (isset($coaches[$coachTeamId]) && count($coaches[$coachTeamId]) > 0)
                <div class="mb-3">
                    @foreach ($coaches[$coachTeamId] as $c)
                        <div class="member-row">
                            <span>{{ $c['name'] }} <span class="text-muted">({{ $c['email'] }})</span></span>
                            @if ($c['is_new']) <span class="badge-new">Новый</span> @endif
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Add coach form --}}
            <div class="p-3 bg-light rounded-3">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Фамилия <span class="text-danger">*</span></label>
                        <input type="text" wire:model.defer="coachLastName" class="form-control @error('coachLastName') is-invalid @enderror" placeholder="Иванов">
                        @error('coachLastName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Имя <span class="text-danger">*</span></label>
                        <input type="text" wire:model.defer="coachFirstName" class="form-control @error('coachFirstName') is-invalid @enderror" placeholder="Пётр">
                        @error('coachFirstName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" wire:model.defer="coachEmail" class="form-control @error('coachEmail') is-invalid @enderror" placeholder="coach@example.com">
                        @error('coachEmail') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Телефон</label>
                        <input type="text" wire:model.defer="coachPhone" class="form-control" placeholder="+7...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button wire:click="addCoach" class="btn btn-outline-primary w-100">
                            <i class="fe fe-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" wire:click="prevStep" class="btn btn-light btn-lg px-4">Назад</button>
                <button type="button" wire:click="goToPlayers" class="btn btn-primary btn-lg px-5">Далее</button>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════════
             ШАГ 4: ИГРОКИ
        ═══════════════════════════════════════════════════ --}}
        @if ($step === 4)
            <h4 class="fw-bold mb-4">Добавьте игроков</h4>
            <p class="text-muted mb-4">Добавьте игроков к каждой команде. Можно добавить сейчас или позже из панели управления.</p>

            {{-- Team tabs --}}
            <div class="d-flex flex-wrap gap-2 mb-4">
                @foreach ($teams as $t)
                    <span class="tab-team {{ $playerTeamId === $t['id'] ? 'active' : '' }}"
                          wire:click="$set('playerTeamId', {{ $t['id'] }})">
                        {{ $t['name'] }}
                        @if (isset($players[$t['id']]) && count($players[$t['id']]) > 0)
                            <span class="badge bg-white text-success ms-1">{{ count($players[$t['id']]) }}</span>
                        @endif
                    </span>
                @endforeach
            </div>

            {{-- Existing players for selected team --}}
            @if (isset($players[$playerTeamId]) && count($players[$playerTeamId]) > 0)
                <div class="mb-3">
                    @foreach ($players[$playerTeamId] as $p)
                        <div class="member-row">
                            <span>{{ $p['name'] }} <span class="text-muted">({{ $p['email'] }})</span></span>
                            @if ($p['is_new']) <span class="badge-new">Новый</span> @endif
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Add player form --}}
            <div class="p-3 bg-light rounded-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Фамилия <span class="text-danger">*</span></label>
                        <input type="text" wire:model.defer="playerLastName" class="form-control @error('playerLastName') is-invalid @enderror" placeholder="Петров">
                        @error('playerLastName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Имя <span class="text-danger">*</span></label>
                        <input type="text" wire:model.defer="playerFirstName" class="form-control @error('playerFirstName') is-invalid @enderror" placeholder="Алексей">
                        @error('playerFirstName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" wire:model.defer="playerEmail" class="form-control" placeholder="Необязательно">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Дата рождения <span class="text-danger">*</span></label>
                        <input type="date" wire:model.defer="playerBirthDate" class="form-control @error('playerBirthDate') is-invalid @enderror">
                        @error('playerBirthDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Пол <span class="text-danger">*</span></label>
                        <select wire:model.defer="playerGender" class="form-select">
                            <option value="male">Мужской</option>
                            <option value="female">Женский</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button wire:click="addPlayer" class="btn btn-outline-primary w-100">
                            <i class="fe fe-plus"></i> Добавить
                        </button>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" wire:click="prevStep" class="btn btn-light btn-lg px-4">Назад</button>
                <button type="button" wire:click="finish" class="btn btn-success btn-lg px-5">
                    <i class="fe fe-check me-1"></i> Завершить
                </button>
            </div>
        @endif

    </div>
</div>
@endsection
