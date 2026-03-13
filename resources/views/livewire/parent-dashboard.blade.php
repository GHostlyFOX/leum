<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="page-title" style="font-size: 1.5rem; font-weight: 700; color: #1f2937;">
                Мои дети
            </h2>
            <p class="text-secondary" style="color: #6b7280;">
                Отслеживайте расписание и статистику ваших детей
            </p>
        </div>
    </div>

    @if(empty($children))
        <div class="row">
            <div class="col-12">
                <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 40px; text-align: center;">
                    <div class="icon-circle-light" style="width: 80px; height: 80px; border-radius: 50%; background: #f0fdf4; display: flex; align-items: center; justify-content: center; color: #8fbd56; margin: 0 auto 20px;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <h4 style="color: #1f2937; font-weight: 600; margin-bottom: 8px;">Нет привязанных детей</h4>
                    <p style="color: #6b7280; max-width: 400px; margin: 0 auto;">
                        Обратитесь к администратору или тренеру, чтобы вас привязали к профилю ребенка
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- Выбор ребенка -->
        <div class="row mb-4">
            <div class="col-12">
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    @foreach($children as $child)
                        <button wire:click="selectChild({{ $child['id'] }})"
                            style="padding: 12px 20px; border-radius: 12px; border: 2px solid {{ $selectedChildId === $child['id'] ? '#8fbd56' : '#e5e7eb' }}; background: {{ $selectedChildId === $child['id'] ? '#f0fdf4' : '#fff' }}; color: {{ $selectedChildId === $child['id'] ? '#1f2937' : '#6b7280' }}; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: #8fbd56; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; font-size: 14px;">
                                    {{ substr($child['name'], 0, 1) }}
                                </div>
                                <div style="text-align: left;">
                                    <div style="font-size: 0.95rem;">{{ $child['name'] }}</div>
                                    <div style="font-size: 0.75rem; font-weight: 400; opacity: 0.8;">{{ $child['team'] }}</div>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        @if($selectedChild)
        <!-- Информация о выбранном ребенке -->
        <div class="row">
            <!-- Карточка профиля -->
            <div class="col-md-4 mb-4">
                <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 24px; height: 100%;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #8fbd56 0%, #6d9e3a 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 36px; font-weight: 600; margin: 0 auto 16px;">
                            {{ substr($selectedChild['name'], 0, 1) }}
                        </div>
                        <h4 style="margin: 0 0 4px 0; color: #1f2937; font-weight: 600;">{{ $selectedChild['name'] }}</h4>
                        <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">{{ $selectedChild['position'] ?? 'Игрок' }}</p>
                    </div>

                    <div style="border-top: 1px solid #e5e7eb; padding-top: 16px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                            <span style="color: #6b7280;">Команда:</span>
                            <span style="color: #1f2937; font-weight: 500;">{{ $selectedChild['team'] }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                            <span style="color: #6b7280;">Клуб:</span>
                            <span style="color: #1f2937; font-weight: 500;">{{ $selectedChild['club'] }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #6b7280;">Дата рождения:</span>
                            <span style="color: #1f2937; font-weight: 500;">{{ $selectedChild['birth_date'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Статистика посещаемости -->
            <div class="col-md-4 mb-4">
                <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 24px; height: 100%;">
                    <h5 style="margin: 0 0 20px 0; color: #1f2937; font-weight: 600;">Посещаемость (30 дней)</h5>
                    
                    <div style="text-align: center; margin-bottom: 24px;">
                        <div style="position: relative; width: 120px; height: 120px; margin: 0 auto;">
                            <svg viewBox="0 0 36 36" style="width: 100%; height: 100%; transform: rotate(-90deg);">
                                <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e5e7eb" stroke-width="3" />
                                <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#8fbd56" stroke-width="3" 
                                    stroke-dasharray="{{ $attendanceStats['percentage'] }}, 100" />
                            </svg>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 24px; font-weight: 700; color: #1f2937;">
                                {{ $attendanceStats['percentage'] }}%
                            </div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; text-align: center;">
                        <div style="padding: 12px; background: #f0fdf4; border-radius: 10px;">
                            <div style="font-size: 20px; font-weight: 700; color: #22c55e;">{{ $attendanceStats['present'] }}</div>
                            <div style="font-size: 0.75rem; color: #6b7280;">Был</div>
                        </div>
                        <div style="padding: 12px; background: #fef2f2; border-radius: 10px;">
                            <div style="font-size: 20px; font-weight: 700; color: #ef4444;">{{ $attendanceStats['absent'] }}</div>
                            <div style="font-size: 0.75rem; color: #6b7280;">Пропустил</div>
                        </div>
                        <div style="padding: 12px; background: #f8f9fa; border-radius: 10px;">
                            <div style="font-size: 20px; font-weight: 700; color: #6b7280;">{{ $attendanceStats['total'] }}</div>
                            <div style="font-size: 0.75rem; color: #6b7280;">Всего</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Быстрые действия -->
            <div class="col-md-4 mb-4">
                <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 24px; height: 100%;">
                    <h5 style="margin: 0 0 20px 0; color: #1f2937; font-weight: 600;">Быстрые действия</h5>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <a href="/club/team/{{ $selectedChild['team_id'] }}" 
                            style="display: flex; align-items: center; padding: 16px; background: #f8f9fa; border-radius: 10px; text-decoration: none; color: inherit; transition: all 0.2s;"
                            onmouseover="this.style.background='#f0fdf4'" 
                            onmouseout="this.style.background='#f8f9fa'">
                            <div class="icon-circle-light" style="width: 40px; height: 40px; border-radius: 50%; background: #f0fdf4; display: flex; align-items: center; justify-content: center; color: #8fbd56; margin-right: 12px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #1f2937;">Команда</div>
                                <div style="font-size: 0.8rem; color: #6b7280;">Состав и расписание</div>
                            </div>
                        </a>

                        <a href="/profile" 
                            style="display: flex; align-items: center; padding: 16px; background: #f8f9fa; border-radius: 10px; text-decoration: none; color: inherit; transition: all 0.2s;"
                            onmouseover="this.style.background='#f0fdf4'" 
                            onmouseout="this.style.background='#f8f9fa'">
                            <div class="icon-circle-light" style="width: 40px; height: 40px; border-radius: 50%; background: #f0fdf4; display: flex; align-items: center; justify-content: center; color: #8fbd56; margin-right: 12px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #1f2937;">Профиль</div>
                                <div style="font-size: 0.8rem; color: #6b7280;">Настройки уведомлений</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Предстоящие тренировки -->
        <div class="row">
            <div class="col-12">
                <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 24px;">
                    <h5 style="margin: 0 0 20px 0; color: #1f2937; font-weight: 600;">Предстоящие тренировки</h5>
                    
                    @if(empty($upcomingTrainings))
                        <div style="text-align: center; padding: 30px; color: #6b7280;">
                            <p>Нет запланированных тренировок</p>
                        </div>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            @foreach($upcomingTrainings as $training)
                                <div style="display: flex; align-items: center; padding: 16px; background: #f8f9fa; border-radius: 10px;">
                                    <div style="width: 60px; height: 60px; background: #f0fdf4; border-radius: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; margin-right: 16px;">
                                        <div style="font-size: 12px; color: #6d9e3a; font-weight: 600;">{{ substr($training['date'], 3, 2) }}</div>
                                        <div style="font-size: 18px; color: #1f2937; font-weight: 700;">{{ substr($training['date'], 0, 2) }}</div>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: #1f2937; margin-bottom: 4px;">
                                            {{ $training['time'] }} — Тренировка
                                        </div>
                                        @if($training['venue'])
                                            <div style="font-size: 0.875rem; color: #6b7280;">
                                                {{ $training['venue'] }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        @if($training['status'] === 'confirmed')
                                            <span style="padding: 4px 12px; background: #dcfce7; color: #16a34a; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">Иду</span>
                                        @elseif($training['status'] === 'declined')
                                            <span style="padding: 4px 12px; background: #fef2f2; color: #ef4444; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">Не иду</span>
                                        @else
                                            <span style="padding: 4px 12px; background: #fef3c7; color: #92400e; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">Ожидает</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    @endif
</div>
