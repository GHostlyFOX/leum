<div>
<style>
    .invite-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 1050;
        display: flex; align-items: center; justify-content: center;
    }
    .invite-dialog {
        background: #fff;
        border-radius: 16px;
        width: 100%;
        max-width: 440px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        overflow: hidden;
        animation: inviteSlideUp 0.25s ease-out;
    }
    @keyframes inviteSlideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .invite-header {
        padding: 20px 24px 0;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }
    .invite-header h5 {
        font-weight: 700; font-size: 1.15rem; margin: 0;
    }
    .invite-header .team-name {
        color: #6b7280; font-size: 0.85rem; margin-top: 2px;
    }
    .invite-header .btn-close-invite {
        background: none; border: none; color: #9ca3af;
        cursor: pointer; padding: 4px; line-height: 1;
    }
    .invite-header .btn-close-invite:hover { color: #374151; }
    .invite-tabs {
        display: flex;
        border-bottom: 2px solid #f3f4f6;
        margin: 16px 24px 0;
    }
    .invite-tabs button {
        flex: 1;
        background: none; border: none;
        padding: 10px 0;
        font-weight: 600; font-size: 0.9rem;
        color: #9ca3af;
        cursor: pointer;
        position: relative;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        transition: color 0.2s;
    }
    .invite-tabs button:hover { color: #6366f1; }
    .invite-tabs button.active {
        color: #6366f1;
    }
    .invite-tabs button.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0; right: 0;
        height: 2px;
        background: #6366f1;
        border-radius: 2px 2px 0 0;
    }
    .invite-body { padding: 20px 24px; }
    .invite-body label {
        font-weight: 600; font-size: 0.88rem;
        color: #374151; display: block; margin-bottom: 6px;
    }
    .invite-body .form-control,
    .invite-body .form-select {
        border-radius: 10px;
        border: 1.5px solid #e5e7eb;
        padding: 10px 14px;
        font-size: 0.9rem;
        transition: border-color 0.2s;
    }
    .invite-body .form-control:focus,
    .invite-body .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }
    .invite-body .role-hint {
        font-size: 0.8rem; color: #9ca3af; margin-top: 4px;
    }
    .invite-info-box {
        background: #f9fafb;
        border-radius: 10px;
        padding: 14px 16px;
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 16px;
        line-height: 1.5;
    }
    .invite-footer {
        padding: 16px 24px 20px;
        display: flex; gap: 10px;
    }
    .invite-footer .btn-cancel {
        flex: 1;
        background: #fff; border: 1.5px solid #e5e7eb;
        color: #374151; font-weight: 600;
        padding: 10px; border-radius: 10px;
        cursor: pointer; font-size: 0.9rem;
        transition: all 0.2s;
    }
    .invite-footer .btn-cancel:hover { background: #f9fafb; }
    .invite-footer .btn-send {
        flex: 1;
        background: #6366f1; border: none;
        color: #fff; font-weight: 600;
        padding: 10px; border-radius: 10px;
        cursor: pointer; font-size: 0.9rem;
        transition: all 0.2s;
    }
    .invite-footer .btn-send:hover { background: #4f46e5; }
    .invite-footer .btn-send:disabled {
        background: #c7d2fe; cursor: not-allowed;
    }
    .btn-generate {
        width: 100%;
        background: #6366f1; border: none;
        color: #fff; font-weight: 600;
        padding: 12px; border-radius: 10px;
        cursor: pointer; font-size: 0.9rem;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: all 0.2s;
        margin-top: 16px;
    }
    .btn-generate:hover { background: #4f46e5; }
    .link-result {
        margin-top: 16px;
    }
    .link-result .link-field {
        display: flex; gap: 8px; align-items: center;
    }
    .link-result .link-field input {
        flex: 1;
        background: #f9fafb;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.82rem;
        color: #374151;
    }
    .link-result .btn-copy {
        background: #6366f1; border: none;
        color: #fff; font-weight: 600;
        padding: 10px 16px; border-radius: 10px;
        cursor: pointer; font-size: 0.82rem;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .link-result .btn-copy:hover { background: #4f46e5; }
    .alert-invite {
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.85rem;
        margin-bottom: 12px;
    }
    .alert-invite.success {
        background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;
    }
    .alert-invite.error {
        background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;
    }
</style>

<div class="invite-overlay" wire:click.self="closeModal">
    <div class="invite-dialog" @click.stop>
        {{-- Header --}}
        <div class="invite-header">
            <div>
                <h5>Пригласить участника</h5>
                @if($team)
                    <div class="team-name">{{ $team->name }}</div>
                @endif
            </div>
            <button class="btn-close-invite" wire:click="closeModal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        {{-- Tabs --}}
        <div class="invite-tabs">
            <button wire:click="switchTab('email')" class="{{ $activeTab === 'email' ? 'active' : '' }}">
                Email Invite
            </button>
            <button wire:click="switchTab('link')" class="{{ $activeTab === 'link' ? 'active' : '' }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                </svg>
                Share Link
            </button>
        </div>

        {{-- Body --}}
        <div class="invite-body">
            {{-- Messages --}}
            @if($successMessage)
                <div class="alert-invite success">{{ $successMessage }}</div>
            @endif
            @if($errorMessage)
                <div class="alert-invite error">{{ $errorMessage }}</div>
            @endif

            @if($activeTab === 'email')
                {{-- EMAIL TAB --}}
                <div>
                    <label for="invite-email">Email Address</label>
                    <input
                        type="email"
                        id="invite-email"
                        class="form-control"
                        placeholder="member@example.com"
                        wire:model="email"
                    >
                    @error('email')
                        <span class="text-danger" style="font-size: 0.8rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="invite-email-role">Role</label>
                    <select id="invite-email-role" class="form-select" wire:model.live="emailRole">
                        <option value="coach">Тренер</option>
                        <option value="parent">Родитель</option>
                        <option value="player">Игрок</option>
                    </select>
                    <div class="role-hint">
                        @switch($emailRole)
                            @case('coach')
                                Может управлять составом и тренировками
                                @break
                            @case('parent')
                                Может просматривать расписание и информацию о ребёнке
                                @break
                            @default
                                Может просматривать информацию команды и отвечать на события
                        @endswitch
                    </div>
                </div>

                <div class="invite-info-box">
                    Приглашение будет отправлено на этот email. Если получатель уже зарегистрирован, он сможет принять
                    приглашение из дашборда. Если нет — ему будет предложено создать аккаунт.
                </div>
            @else
                {{-- LINK TAB --}}
                <div>
                    <label for="invite-link-role">Role for new members</label>
                    <select id="invite-link-role" class="form-select" wire:model.live="linkRole">
                        <option value="coach">Тренер</option>
                        <option value="parent">Родитель</option>
                        <option value="player">Игрок</option>
                    </select>
                    <div class="role-hint">
                        @switch($linkRole)
                            @case('coach')
                                Любой перешедший по ссылке вступит как тренер.
                                @break
                            @case('parent')
                                Любой перешедший по ссылке вступит как родитель.
                                @break
                            @default
                                Любой перешедший по ссылке вступит как игрок.
                        @endswitch
                    </div>
                </div>

                @if(!$generatedLink)
                    <div class="invite-info-box">
                        Создайте ссылку для приглашения. Ей можно поделиться в мессенджерах, соцсетях или групповых чатах.
                    </div>

                    <button wire:click="generateShareLink" class="btn-generate">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                        </svg>
                        Сгенерировать ссылку
                    </button>
                @else
                    <div class="link-result">
                        <label>Ссылка для приглашения</label>
                        <div class="link-field">
                            <input type="text" value="{{ $generatedLink }}" readonly id="invite-link-input">
                            <button
                                class="btn-copy"
                                onclick="navigator.clipboard.writeText(document.getElementById('invite-link-input').value).then(() => { this.textContent = 'Скопировано!'; setTimeout(() => { this.textContent = 'Копировать'; }, 2000); })"
                            >
                                Копировать
                            </button>
                        </div>
                        <div class="role-hint mt-2">Ссылка действительна 30 дней. Количество использований не ограничено.</div>
                    </div>
                @endif
            @endif
        </div>

        {{-- Footer --}}
        <div class="invite-footer">
            @if($activeTab === 'email')
                <button class="btn-cancel" wire:click="closeModal">Отмена</button>
                <button
                    class="btn-send"
                    wire:click="sendEmailInvite"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="sendEmailInvite">Отправить приглашение</span>
                    <span wire:loading wire:target="sendEmailInvite">Отправка...</span>
                </button>
            @else
                <button class="btn-cancel" wire:click="closeModal" style="flex: 1;">Готово</button>
            @endif
        </div>
    </div>
</div>

</div>
