<div>
<style>
    .join-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }
    .join-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.08);
        max-width: 460px;
        width: 100%;
        overflow: hidden;
    }
    .join-card-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        padding: 32px;
        text-align: center;
        color: #fff;
    }
    .join-card-header .team-icon {
        width: 64px; height: 64px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
    }
    .join-card-header h3 {
        font-weight: 700; margin: 0 0 4px; font-size: 1.4rem;
    }
    .join-card-header .club-name {
        opacity: 0.8; font-size: 0.9rem;
    }
    .join-card-body {
        padding: 32px;
    }
    .join-info-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .join-info-row:last-child { border-bottom: none; }
    .join-info-row .info-icon {
        width: 40px; height: 40px;
        background: #f3f4f6;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        color: #6366f1;
    }
    .join-info-row .info-label {
        font-size: 0.8rem; color: #9ca3af;
    }
    .join-info-row .info-value {
        font-weight: 600; color: #1f2937; font-size: 0.95rem;
    }
    .btn-join {
        width: 100%;
        background: #6366f1; border: none;
        color: #fff; font-weight: 700;
        padding: 14px; border-radius: 12px;
        cursor: pointer; font-size: 1rem;
        transition: all 0.2s;
        margin-top: 20px;
    }
    .btn-join:hover { background: #4f46e5; }
    .btn-join:disabled { background: #c7d2fe; cursor: not-allowed; }
    .btn-dashboard {
        width: 100%;
        background: #22c55e; border: none;
        color: #fff; font-weight: 700;
        padding: 14px; border-radius: 12px;
        cursor: pointer; font-size: 1rem;
        transition: all 0.2s;
        margin-top: 12px;
    }
    .btn-dashboard:hover { background: #16a34a; }
    .join-error {
        text-align: center;
        padding: 40px 32px;
    }
    .join-error .error-icon {
        width: 64px; height: 64px;
        background: #fef2f2;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
        color: #dc2626;
    }
    .join-error h4 { color: #1f2937; font-weight: 700; margin-bottom: 8px; }
    .join-error p { color: #6b7280; font-size: 0.9rem; margin: 0; }
    .join-success {
        text-align: center;
        padding: 20px 0 0;
    }
    .join-success .success-icon {
        width: 64px; height: 64px;
        background: #f0fdf4;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
        color: #22c55e;
    }
    .join-success h4 { color: #1f2937; font-weight: 700; margin-bottom: 4px; }
    .join-success p { color: #6b7280; font-size: 0.9rem; }
    .already-member-badge {
        background: #fef3c7; color: #92400e;
        padding: 10px 16px; border-radius: 10px;
        font-size: 0.85rem; text-align: center;
        margin-top: 16px;
    }
</style>

<div class="join-container">
    <div class="join-card">
        @if($errorMessage && !$teamName)
            {{-- Invalid / expired token --}}
            <div class="join-card-body">
                <div class="join-error">
                    <div class="error-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                    </div>
                    <h4>Ссылка недействительна</h4>
                    <p>{{ $errorMessage }}</p>
                    <a href="{{ route('home') }}" class="btn-join" style="display: inline-block; text-align: center; text-decoration: none; margin-top: 24px;">
                        На главную
                    </a>
                </div>
            </div>
        @else
            {{-- Valid invite --}}
            <div class="join-card-header">
                <div class="team-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <h3>{{ $teamName }}</h3>
                @if($clubName)
                    <div class="club-name">{{ $clubName }}</div>
                @endif
            </div>

            <div class="join-card-body">
                @if($joined)
                    {{-- Success state --}}
                    <div class="join-success">
                        <div class="success-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <h4>{{ $successMessage }}</h4>
                        <p>Вы вступили в команду «{{ $teamName }}» как {{ $roleName }}.</p>
                        <button wire:click="goToDashboard" class="btn-dashboard">Перейти в дашборд</button>
                    </div>
                @else
                    {{-- Join info --}}
                    <div class="join-info-row">
                        <div class="info-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <div>
                            <div class="info-label">Команда</div>
                            <div class="info-value">{{ $teamName }}</div>
                        </div>
                    </div>

                    @if($clubName)
                    <div class="join-info-row">
                        <div class="info-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </div>
                        <div>
                            <div class="info-label">Клуб</div>
                            <div class="info-value">{{ $clubName }}</div>
                        </div>
                    </div>
                    @endif

                    <div class="join-info-row">
                        <div class="info-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <div>
                            <div class="info-label">Ваша роль</div>
                            <div class="info-value">{{ $roleName }}</div>
                        </div>
                    </div>

                    @if($alreadyMember)
                        <div class="already-member-badge">
                            Вы уже состоите в этой команде.
                        </div>
                        <button wire:click="goToDashboard" class="btn-dashboard">Перейти в дашборд</button>
                    @elseif($errorMessage)
                        <div class="already-member-badge" style="background: #fef2f2; color: #dc2626;">
                            {{ $errorMessage }}
                        </div>
                    @else
                        @auth
                            <button wire:click="acceptInvite" wire:loading.attr="disabled" class="btn-join">
                                <span wire:loading.remove wire:target="acceptInvite">Вступить в команду</span>
                                <span wire:loading wire:target="acceptInvite">Вступление...</span>
                            </button>
                        @else
                            <a href="{{ route('auth.register', ['invite' => $token]) }}" class="btn-join" style="display: block; text-align: center; text-decoration: none;">
                                Зарегистрироваться и вступить
                            </a>
                            <p style="text-align: center; margin-top: 12px; font-size: 0.85rem; color: #6b7280;">
                                Уже есть аккаунт? <a href="{{ route('auth.loginForm', ['invite' => $token]) }}" style="color: #6366f1; font-weight: 600;">Войти</a>
                            </p>
                        @endauth
                    @endif
                @endif
            </div>
        @endif
    </div>
</div>
</div>
