<div class="container-fluid">
    @if($match)
    <div class="row mb-4">
        <div class="col-12">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <a href="/dashboard" style="color: #6b7280; text-decoration: none;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </a>
                <span style="color: #6b7280;">/</span>
                <span style="color: #1f2937;">Матч</span>
            </div>
            <h2 class="page-title" style="font-size: 1.5rem; font-weight: 700; color: #1f2937;">
                {{ $match->team->name }} vs {{ $match->opponent?->name ?? 'Соперник' }}
            </h2>
        </div>
    </div>

    <!-- Счет и статус -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-custom" style="background: linear-gradient(135deg, #8fbd56 0%, #6d9e3a 100%); border-radius: 14px; padding: 30px; color: #fff; text-align: center;">
                <div style="display: flex; justify-content: center; align-items: center; gap: 30px;">
                    <div style="text-align: center;">
                        <div style="font-size: 1.2rem; font-weight: 600; margin-bottom: 8px;">{{ $match->team->name }}</div>
                        <div style="font-size: 3rem; font-weight: 700;">{{ $match->score_home ?? 0 }}</div>
                    </div>
                    <div style="font-size: 2rem; font-weight: 300; opacity: 0.8;">:</div>
                    <div style="text-align: center;">
                        <div style="font-size: 1.2rem; font-weight: 600; margin-bottom: 8px;">{{ $match->opponent?->name ?? 'Соперник' }}</div>
                        <div style="font-size: 3rem; font-weight: 700;">{{ $match->score_away ?? 0 }}</div>
                    </div>
                </div>
                <div style="margin-top: 16px; font-size: 0.9rem; opacity: 0.9;">
                    {{ $match->match_date?->format('d.m.Y') }} | {{ Str::substr($match->start_time ?? '', 0, 5) }}
                    @if($match->status === 'live')
                        <span style="margin-left: 12px; padding: 4px 12px; background: #ef4444; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">LIVE</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Информация о матче -->
        <div class="col-md-4 mb-4">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 24px;">
                <h5 style="margin: 0 0 20px 0; color: #1f2937; font-weight: 600;">Информация</h5>
                
                <div style="margin-bottom: 16px;">
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 4px;">Турнир</div>
                    <div style="color: #1f2937; font-weight: 600;">{{ $match->tournament?->name ?? 'Товарищеский матч' }}</div>
                </div>

                <div style="margin-bottom: 16px;">
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 4px;">Место проведения</div>
                    <div style="color: #1f2937; font-weight: 600;">{{ $match->venue?->name ?? 'Не указано' }}</div>
                </div>

                <div style="margin-bottom: 16px;">
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 4px;">Тип матча</div>
                    <div style="color: #1f2937; font-weight: 600;">
                        {{ $match->is_home ? 'Домашний' : 'Выездной' }}
                    </div>
                </div>

                <div>
                    <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 4px;">Статус</div>
                    <div>
                        @if($match->status === 'scheduled')
                            <span style="padding: 4px 12px; background: #fef3c7; color: #92400e; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">Запланирован</span>
                        @elseif($match->status === 'live')
                            <span style="padding: 4px 12px; background: #dcfce7; color: #16a34a; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">Идет</span>
                        @elseif($match->status === 'finished')
                            <span style="padding: 4px 12px; background: #f0fdf4; color: #6d9e3a; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">Завершен</span>
                        @else
                            <span style="padding: 4px 12px; background: #fef2f2; color: #ef4444; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">Отменен</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- События матча -->
        <div class="col-md-8 mb-4">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 24px;">
                <h5 style="margin: 0 0 20px 0; color: #1f2937; font-weight: 600;">Ход матча</h5>
                
                @if(empty($events))
                    <div style="text-align: center; padding: 40px; color: #6b7280;">
                        <p>Пока нет событий</p>
                    </div>
                @else
                    <div style="position: relative; padding-left: 30px;">
                        <div style="position: absolute; left: 8px; top: 0; bottom: 0; width: 2px; background: #e5e7eb;"></div>
                        @foreach($events as $event)
                            <div style="position: relative; margin-bottom: 16px;">
                                <div style="position: absolute; left: -26px; top: 4px; width: 16px; height: 16px; background: 
                                    @if($event['type'] === 'goal') #22c55e
                                    @elseif($event['type'] === 'yellow_card') #eab308
                                    @elseif($event['type'] === 'red_card') #ef4444
                                    @else #8fbd56 @endif; 
                                    border-radius: 50%; border: 2px solid #fff;"></div>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <span style="font-weight: 700; color: #8fbd56; min-width: 40px;">{{ $event['minute }}'</span>
                                    <span style="color: #1f2937;">
                                        @if($event['type'] === 'goal')
                                            <strong>Гол!</strong> {{ $event['player'] }}
                                        @elseif($event['type'] === 'yellow_card')
                                            <strong>Желтая карточка</strong> {{ $event['player'] }}
                                        @elseif($event['type'] === 'red_card')
                                            <strong>Красная карточка</strong> {{ $event['player'] }}
                                        @else
                                            {{ $event['description'] }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Состав -->
    <div class="row">
        <div class="col-12">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 24px;">
                <h5 style="margin: 0 0 20px 0; color: #1f2937; font-weight: 600;">Состав команды</h5>
                
                @if(empty($lineup))
                    <div style="text-align: center; padding: 40px; color: #6b7280;">
                        <p>Состав не объявлен</p>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-6">
                            <h6 style="margin: 0 0 16px 0; color: #1f2937; font-weight: 600;">Стартовый состав</h6>
                            @foreach(collect($lineup)->where('is_starting', true) as $player)
                                <div style="display: flex; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 8px; margin-bottom: 8px;">
                                    <div style="width: 28px; height: 28px; background: #8fbd56; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 12px; font-weight: 600; margin-right: 12px;">
                                        {{ $loop->iteration }}
                                    </div>
                                    <span style="color: #1f2937; font-weight: 500;">{{ $player['name'] }}</span>
                                    @if($player['position'])
                                        <span style="margin-left: auto; font-size: 0.75rem; color: #6b7280;">{{ $player['position'] }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="col-md-6">
                            <h6 style="margin: 0 0 16px 0; color: #1f2937; font-weight: 600;">Запасные</h6>
                            @foreach(collect($lineup)->where('is_starting', false) as $player)
                                <div style="display: flex; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 8px; margin-bottom: 8px;">
                                    <div style="width: 28px; height: 28px; background: #e5e7eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #6b7280; font-size: 12px; font-weight: 600; margin-right: 12px;">
                                        {{ $loop->iteration }}
                                    </div>
                                    <span style="color: #1f2937; font-weight: 500;">{{ $player['name'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
