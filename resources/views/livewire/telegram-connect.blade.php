<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="page-title" style="font-size: 1.5rem; font-weight: 700; color: #1f2937;">
                Подключение Telegram
            </h2>
            <p class="text-secondary" style="color: #6b7280;">
                Получайте уведомления о тренировках и матчах в Telegram
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 24px;">
                @if($isConnected)
                    <!-- Уже подключено -->
                    <div style="text-align: center; padding: 20px;">
                        <div style="width: 80px; height: 80px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <h4 style="color: #1f2937; font-weight: 600; margin-bottom: 8px;">Telegram подключен!</h4>
                        @if($telegramUsername)
                            <p style="color: #6b7280; margin-bottom: 20px;">@{{ $telegramUsername }}</p>
                        @endif
                        <button wire:click="disconnect" 
                            style="background: #fef2f2; border: 1.5px solid #ef4444; color: #ef4444; font-weight: 600; padding: 10px 22px; border-radius: 10px; cursor: pointer;">
                            Отключить
                        </button>
                    </div>
                @else
                    <!-- Не подключено -->
                    <div style="text-align: center; padding: 20px;">
                        <div style="width: 80px; height: 80px; background: #f0fdf4; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#8fbd56" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </div>
                        <h4 style="color: #1f2937; font-weight: 600; margin-bottom: 8px;">Подключите Telegram</h4>
                        <p style="color: #6b7280; margin-bottom: 20px;">
                            Получайте уведомления о тренировках, матчах и важных событиях
                        </p>

                        @if($showCode && $code)
                            <!-- Показываем код -->
                            <div style="background: #f0fdf4; border: 2px solid #8fbd56; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                                <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 8px;">Ваш код подключения:</div>
                                <div style="font-size: 2rem; font-weight: 700; color: #1f2937; letter-spacing: 4px; font-family: monospace;">{{ $code }}</div>
                                <div style="font-size: 0.75rem; color: #6b7280; margin-top: 8px;">
                                    Код действителен до: {{ \Carbon\Carbon::parse($expiresAt)->format('H:i') }}
                                </div>
                            </div>

                            <div style="text-align: left; background: #f8f9fa; border-radius: 10px; padding: 16px; margin-bottom: 20px;">
                                <p style="margin: 0 0 12px 0; font-weight: 600; color: #1f2937;">Как подключить:</p>
                                <ol style="margin: 0; padding-left: 20px; color: #6b7280;">
                                    <li style="margin-bottom: 8px;">Откройте бота <a href="https://t.me/sbor_team_bot" target="_blank" style="color: #8fbd56;">@sbor_team_bot</a></li>
                                    <li style="margin-bottom: 8px;">Отправьте команду: <code style="background: #fff; padding: 2px 6px; border-radius: 4px;">/connect {{ $code }}</code></li>
                                    <li>Готово! Бот подтвердит подключение</li>
                                </ol>
                            </div>

                            <button wire:click="$set('showCode', false)" 
                                style="background: #fff; border: 1.5px solid #e5e7eb; color: #6b7280; font-weight: 600; padding: 10px 22px; border-radius: 10px; cursor: pointer; margin-right: 8px;">
                                Отмена
                            </button>
                        @else
                            <!-- Кнопка генерации кода -->
                            <button wire:click="generateCode" 
                                style="background: #8fbd56; border: none; color: #fff; font-weight: 600; padding: 14px 28px; border-radius: 10px; cursor: pointer;"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove>Получить код подключения</span>
                                <span wire:loading>Генерация...</span>
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 24px;">
                <h5 style="margin: 0 0 16px 0; color: #1f2937; font-weight: 600;">Что вы получите:</h5>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; align-items: center; padding: 12px; background: #f8f9fa; border-radius: 10px;">
                        <div style="width: 40px; height: 40px; background: #f0fdf4; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #8fbd56; margin-right: 12px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #1f2937;">Напоминания о тренировках</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">За 24 часа и за 1 час до начала</div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; padding: 12px; background: #f8f9fa; border-radius: 10px;">
                        <div style="width: 40px; height: 40px; background: #f0fdf4; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #8fbd56; margin-right: 12px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #1f2937;">Уведомления о матчах</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">Состав, результаты, важные события</div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; padding: 12px; background: #f8f9fa; border-radius: 10px;">
                        <div style="width: 40px; height: 40px; background: #f0fdf4; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #8fbd56; margin-right: 12px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #1f2937;">Объявления клуба</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">Важные новости и изменения</div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; padding: 12px; background: #f8f9fa; border-radius: 10px;">
                        <div style="width: 40px; height: 40px; background: #f0fdf4; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #8fbd56; margin-right: 12px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #1f2937;">Быстрый доступ к расписанию</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">Команды /schedule и /next</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
