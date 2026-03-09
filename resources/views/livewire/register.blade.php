@section('body')
<body class="ltr login-img">
@endsection

@section('content')
<div>
<style>
    body.ltr { background: #f5f7fb; }
    .register-container {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 24px 16px;
    }
    .register-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 24px rgba(0,0,0,0.08);
        padding: 40px 36px;
        width: 100%;
        max-width: 520px;
    }
    .register-logo {
        text-align: center;
        margin-bottom: 24px;
    }
    .register-logo h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d4a14;
        margin: 0;
    }

    /* Progress bar */
    .progress-bar-wrap {
        display: flex;
        gap: 6px;
        margin-bottom: 32px;
    }
    .progress-segment {
        flex: 1;
        height: 4px;
        border-radius: 2px;
        background: #e5e7eb;
        transition: background 0.3s;
    }
    .progress-segment.active,
    .progress-segment.completed { background: #8fbd56; }

    /* Step title */
    .step-counter {
        font-size: 0.8rem;
        color: #9ca3af;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .step-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 24px;
    }

    /* Form fields */
    .register-card .form-label {
        font-weight: 600;
        font-size: 0.875rem;
        color: #374151;
        margin-bottom: 6px;
    }
    .register-card .form-control,
    .register-card .form-select {
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }
    .register-card .form-control:focus,
    .register-card .form-select:focus {
        border-color: #8fbd56;
        box-shadow: 0 0 0 3px rgba(143, 189, 86, 0.15);
    }

    /* Role selection cards */
    .role-card {
        border: 2px solid #e5e7eb;
        border-radius: 14px;
        padding: 20px 18px;
        cursor: pointer;
        transition: all 0.25s ease;
        background: #fff;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .role-card:hover {
        border-color: #c3dba0;
        background: #fafdf5;
    }
    .role-card.selected {
        border-color: #8fbd56;
        background: #f3f9ea;
        box-shadow: 0 0 0 3px rgba(143, 189, 86, 0.15);
    }
    .role-card + .role-card { margin-top: 12px; }
    .role-card-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .role-card-icon.admin { background: #e8f5d6; color: #4a7a25; }
    .role-card-icon.coach { background: #dbeafe; color: #2563eb; }
    .role-card-icon.parent { background: #fef3c7; color: #d97706; }
    .role-card-body h6 {
        font-weight: 700;
        font-size: 0.95rem;
        color: #1f2937;
        margin-bottom: 2px;
    }
    .role-card-body p {
        font-size: 0.8rem;
        color: #6b7280;
        margin: 0;
        line-height: 1.4;
    }

    /* Color picker */
    .color-picker {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .color-circle {
        width: 40px; height: 40px;
        border-radius: 50%;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.2s;
        position: relative;
    }
    .color-circle:hover { transform: scale(1.15); }
    .color-circle.selected {
        border-color: #1f2937;
        box-shadow: 0 0 0 2px #fff, 0 0 0 4px currentColor;
    }
    .color-circle.selected::after {
        content: '\2713';
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }

    /* Buttons */
    .btn-register {
        background: #8fbd56;
        border: none;
        color: #fff;
        font-weight: 600;
        padding: 12px 32px;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    .btn-register:hover { background: #7dab48; color: #fff; }
    .btn-register:disabled { background: #c3dba0; cursor: not-allowed; color: #fff; }
    .btn-back {
        background: #f3f4f6;
        border: none;
        color: #6b7280;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    .btn-back:hover { background: #e5e7eb; color: #374151; }

    /* Password hint */
    .password-hint {
        font-size: 0.78rem;
        color: #9ca3af;
        margin-top: 4px;
    }

    /* Team logo upload */
    .logo-upload-area {
        border: 2px dashed #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        overflow: hidden;
    }
    .logo-upload-area:hover {
        border-color: #8fbd56;
        background: #fafdf5;
    }
    .logo-upload-area input[type="file"] {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .logo-upload-area .upload-icon {
        font-size: 1.5rem;
        color: #9ca3af;
        margin-bottom: 8px;
    }
    .logo-upload-area .upload-text {
        font-size: 0.85rem;
        color: #6b7280;
    }
    .logo-preview {
        width: 64px; height: 64px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #e5e7eb;
    }

    /* Footer link */
    .register-footer {
        text-align: center;
        margin-top: 20px;
        font-size: 0.9rem;
        color: #6b7280;
    }
    .register-footer a {
        color: #8fbd56;
        font-weight: 600;
        text-decoration: none;
    }
    .register-footer a:hover { color: #6d9e3a; text-decoration: underline; }
</style>

<div class="register-container">
    <div class="register-card">

        {{-- Logo --}}
        <div class="register-logo">
            <h1>Сбор</h1>
        </div>

        {{-- Progress bar --}}
        <div class="progress-bar-wrap">
            @for ($i = 1; $i <= $this->totalSteps; $i++)
                <div class="progress-segment {{ $i < $step ? 'completed' : ($i === $step ? 'active' : '') }}"></div>
            @endfor
        </div>

        {{-- ═══════════════════════════════════════════════════════
             ШАГ 1: СОЗДАНИЕ АККАУНТА
        ═══════════════════════════════════════════════════════ --}}
        @if ($step === 1)
            <div class="step-counter">Шаг 1 из {{ $this->totalSteps }}</div>
            <div class="step-title">Создайте аккаунт</div>

            <div class="mb-3">
                <label class="form-label">Имя</label>
                <input type="text" wire:model.defer="firstName"
                       class="form-control @error('firstName') is-invalid @enderror"
                       placeholder="Введите имя">
                @error('firstName') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Фамилия</label>
                <input type="text" wire:model.defer="lastName"
                       class="form-control @error('lastName') is-invalid @enderror"
                       placeholder="Введите фамилию">
                @error('lastName') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" wire:model.defer="email"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="you@example.com">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Пароль</label>
                <input type="password" wire:model.defer="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Минимум 8 символов">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="password-hint">Используйте буквы, цифры и специальные символы</div>
            </div>

            <button wire:click="nextToRole" class="btn btn-register w-100">
                <span wire:loading.remove wire:target="nextToRole">Продолжить</span>
                <span wire:loading wire:target="nextToRole">Проверка...</span>
            </button>

            <div class="register-footer">
                Уже есть аккаунт? <a href="{{ route('auth.loginForm') }}">Войти</a>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════════════
             ШАГ 2: ВЫБОР РОЛИ
        ═══════════════════════════════════════════════════════ --}}
        @if ($step === 2)
            <div class="step-counter">Шаг 2 из {{ $this->totalSteps }}</div>
            <div class="step-title">Как вы будете использовать Сбор?</div>

            <div class="mb-4">
                <div class="role-card {{ $selectedRole === 'admin' ? 'selected' : '' }}"
                     wire:click="selectRole('admin')">
                    <div class="role-card-icon admin">
                        <i class="fe fe-shield"></i>
                    </div>
                    <div class="role-card-body">
                        <h6>Создаю клуб или лигу</h6>
                        <p>Буду управлять командами, расписанием и составом</p>
                    </div>
                </div>

                <div class="role-card {{ $selectedRole === 'coach' ? 'selected' : '' }}"
                     wire:click="selectRole('coach')">
                    <div class="role-card-icon coach">
                        <i class="fe fe-clipboard"></i>
                    </div>
                    <div class="role-card-body">
                        <h6>Я тренер</h6>
                        <p>Буду вести тренировки и отслеживать прогресс игроков</p>
                    </div>
                </div>

                <div class="role-card {{ $selectedRole === 'parent' ? 'selected' : '' }}"
                     wire:click="selectRole('parent')">
                    <div class="role-card-icon parent">
                        <i class="fe fe-heart"></i>
                    </div>
                    <div class="role-card-body">
                        <h6>Родитель или игрок</h6>
                        <p>Хочу следить за расписанием и результатами</p>
                    </div>
                </div>
            </div>

            @error('selectedRole') <div class="text-danger small mb-3">{{ $message }}</div> @enderror

            <div class="d-flex justify-content-between">
                <button wire:click="prevStep" class="btn btn-back">Назад</button>
                <button wire:click="submitRole" class="btn btn-register"
                        {{ empty($selectedRole) ? 'disabled' : '' }}>
                    <span wire:loading.remove wire:target="submitRole">
                        {{ $selectedRole === 'admin' ? 'Продолжить' : 'Создать аккаунт' }}
                    </span>
                    <span wire:loading wire:target="submitRole">
                        {{ $selectedRole === 'admin' ? 'Продолжить...' : 'Регистрация...' }}
                    </span>
                </button>
            </div>

            <div class="register-footer">
                Уже есть аккаунт? <a href="{{ route('auth.loginForm') }}">Войти</a>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════════════
             ШАГ 3 (admin): СОЗДАНИЕ КЛУБА / ЛИГИ
        ═══════════════════════════════════════════════════════ --}}
        @if ($step === 3)
            <div class="step-counter">Шаг 3 из 4</div>
            <div class="step-title">Создайте ваш клуб</div>

            <div class="mb-3">
                <label class="form-label">Название клуба <span class="text-danger">*</span></label>
                <input type="text" wire:model.defer="clubName"
                       class="form-control @error('clubName') is-invalid @enderror"
                       placeholder="Например: ФК «Юные Чемпионы»">
                @error('clubName') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Вид спорта <span class="text-danger">*</span></label>
                <select wire:model.defer="sportTypeId"
                        class="form-select @error('sportTypeId') is-invalid @enderror">
                    <option value="">— Выберите вид спорта —</option>
                    @foreach ($sportTypes as $st)
                        <option value="{{ $st->id }}">{{ $st->name }}</option>
                    @endforeach
                </select>
                @error('sportTypeId') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Описание <span class="text-muted fw-normal">(необязательно)</span></label>
                <textarea wire:model.defer="clubDescription"
                          class="form-control" rows="3"
                          placeholder="Расскажите о клубе в нескольких предложениях"></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <button wire:click="prevStep" class="btn btn-back">Назад</button>
                <button wire:click="nextToTeam" class="btn btn-register">
                    <span wire:loading.remove wire:target="nextToTeam">Продолжить</span>
                    <span wire:loading wire:target="nextToTeam">Проверка...</span>
                </button>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════════════
             ШАГ 4 (admin): СОЗДАНИЕ КОМАНДЫ
        ═══════════════════════════════════════════════════════ --}}
        @if ($step === 4)
            <div class="step-counter">Шаг 4 из 4</div>
            <div class="step-title">Добавьте первую команду</div>

            <div class="mb-3">
                <label class="form-label">Название команды <span class="text-muted fw-normal">(необязательно)</span></label>
                <input type="text" wire:model.defer="teamName"
                       class="form-control @error('teamName') is-invalid @enderror"
                       placeholder="Например: U-12 Основной состав">
                @error('teamName') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Цвет команды</label>
                <div class="color-picker">
                    @foreach ($presetColors as $color)
                        <div class="color-circle {{ $teamColor === $color ? 'selected' : '' }}"
                             style="background: {{ $color }}; color: {{ $color }};"
                             wire:click="selectColor('{{ $color }}')">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Логотип команды <span class="text-muted fw-normal">(необязательно)</span></label>
                @if ($teamLogo)
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img src="{{ $teamLogo->temporaryUrl() }}" class="logo-preview" alt="Preview">
                        <button wire:click="$set('teamLogo', null)" class="btn btn-sm btn-outline-secondary">
                            Удалить
                        </button>
                    </div>
                @else
                    <div class="logo-upload-area">
                        <input type="file" wire:model="teamLogo" accept="image/*">
                        <div class="upload-icon"><i class="fe fe-upload"></i></div>
                        <div class="upload-text">Нажмите для загрузки логотипа</div>
                    </div>
                    <div wire:loading wire:target="teamLogo" class="text-muted small mt-2">Загрузка...</div>
                @endif
                @error('teamLogo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-between">
                <button wire:click="prevStep" class="btn btn-back">Назад</button>
                <button wire:click="finishAdminRegistration" class="btn btn-register">
                    <span wire:loading.remove wire:target="finishAdminRegistration">Создать аккаунт</span>
                    <span wire:loading wire:target="finishAdminRegistration">Создание...</span>
                </button>
            </div>
        @endif

    </div>
</div>
</div>
@endsection
