<div>
<style>
    .onboarding-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }
    .onboarding-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.08);
        max-width: 600px;
        width: 100%;
        padding: 40px;
    }
    .onboarding-header {
        text-align: center;
        margin-bottom: 32px;
    }
    .onboarding-header h2 {
        font-weight: 700;
        margin-bottom: 8px;
        color: #1f2937;
    }
    .onboarding-header p {
        color: #6b7280;
    }
    .search-box {
        position: relative;
        margin-bottom: 24px;
    }
    .search-box input {
        width: 100%;
        padding: 14px 20px 14px 48px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.2s;
    }
    .search-box input:focus {
        outline: none;
        border-color: #8fbd56;
        box-shadow: 0 0 0 3px rgba(143,189,86,0.1);
    }
    .search-box svg {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }
    .search-results {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .result-item {
        display: flex;
        align-items: center;
        padding: 16px;
        background: #f9fafb;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid transparent;
    }
    .result-item:hover {
        background: #f0fdf4;
        border-color: #8fbd56;
    }
    .result-icon {
        width: 48px;
        height: 48px;
        background: #8fbd56;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        margin-right: 16px;
        flex-shrink: 0;
    }
    .result-info h4 {
        font-weight: 600;
        margin-bottom: 4px;
        color: #1f2937;
    }
    .result-info p {
        color: #6b7280;
        font-size: 0.9rem;
        margin: 0;
    }
    .request-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1050;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .request-modal {
        background: #fff;
        border-radius: 16px;
        max-width: 480px;
        width: 100%;
        padding: 32px;
        margin: 20px;
    }
    .request-modal h3 {
        font-weight: 700;
        margin-bottom: 8px;
    }
    .request-modal p {
        color: #6b7280;
        margin-bottom: 20px;
    }
    .pending-status {
        background: #fef3c7;
        border: 1px solid #fde68a;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
    }
    .pending-status svg {
        color: #f59e0b;
        margin-bottom: 12px;
    }
    .btn-primary-custom {
        background: #8fbd56;
        border: none;
        color: #fff;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-primary-custom:hover {
        background: #6d9e3a;
    }
    .btn-outline-custom {
        background: #fff;
        border: 2px solid #e5e7eb;
        color: #374151;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-outline-custom:hover {
        border-color: #8fbd56;
        color: #8fbd56;
    }
</style>

<div class="onboarding-container">
    <div class="onboarding-card">
        <div class="onboarding-header">
            <h2>{{ $title }}</h2>
            <p>{{ $subtitle }}</p>
        </div>

        @if($pendingRequest)
            <div class="pending-status">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <h4>Заявка на рассмотрении</h4>
                <p class="mb-0">
                    Вы отправили заявку на вступление 
                    @if($pendingRequest->type === 'club')
                        в клуб <strong>{{ $pendingRequest->club?->name }}</strong>
                    @else
                        в команду <strong>{{ $pendingRequest->team?->name }}</strong>
                    @endif
                </p>
                <small class="text-muted">Ожидайте подтверждения от администратора</small>
            </div>
        @else
            <div class="search-box">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="searchQuery"
                    wire:keyup="search"
                    placeholder="{{ $searchType === 'club' ? 'Название клуба...' : 'Название команды...' }}"
                >
            </div>

            @if(!empty($searchResults))
                <div class="search-results">
                    @foreach($searchResults as $result)
                        <div class="result-item" wire:click="selectItem({{ $result['id'] }}, '{{ $result['name'] }}')">
                            <div class="result-icon">
                                @if($searchType === 'club')
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                @else
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="result-info">
                                <h4>{{ $result['name'] }}</h4>
                                <p>
                                    @if($searchType === 'club')
                                        {{ $result['city']['name'] ?? '' }} • {{ $result['sport_type']['name'] ?? '' }}
                                    @else
                                        {{ $result['club']['name'] ?? '' }} • {{ $result['sport_type']['name'] ?? '' }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif(strlen($searchQuery) >= 2)
                <div class="text-center text-muted py-4">
                    <p>Ничего не найдено</p>
                </div>
            @endif
        @endif
    </div>
</div>

@if($showRequestModal)
    <div class="request-modal-overlay" wire:click.self="closeModal">
        <div class="request-modal">
            <h3>Отправить заявку</h3>
            <p>Вы собираетесь отправить заявку на вступление <strong>{{ $selectedItemName }}</strong></p>
            
            <div class="mb-3">
                <label class="form-label">Сообщение (опционально)</label>
                <textarea 
                    wire:model="requestMessage" 
                    class="form-control" 
                    rows="3"
                    placeholder="Представьтесь и расскажите о себе..."
                ></textarea>
            </div>

            <div class="d-flex gap-2">
                <button class="btn-outline-custom flex-1" wire:click="closeModal">Отмена</button>
                <button class="btn-primary-custom flex-1" wire:click="sendRequest">
                    Отправить заявку
                </button>
            </div>
        </div>
    </div>
@endif
</div>
