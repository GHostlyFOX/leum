<!--APP-SIDEBAR-->
<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="{{url('dashboard')}}">
                <img src="{{asset('assets/images/brand/logo-header.svg')}}" class="header-brand-img desktop-logo" alt="sbor.team" style="height: 40px; width: auto;">
                <img src="{{asset('assets/images/brand/logo-icon.svg')}}" class="header-brand-img toggle-logo" alt="sbor.team" style="height: 36px; width: auto;">
                <img src="{{asset('assets/images/brand/logo-header.svg')}}" class="header-brand-img light-logo" alt="sbor.team" style="height: 40px; width: auto;">
                <img src="{{asset('assets/images/brand/logo-icon.svg')}}" class="header-brand-img light-logo1" alt="sbor.team" style="height: 36px; width: auto;">
            </a>
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg>
            </div>
            
            @php
                $user = auth()->user();
                $role = $user?->global_role ?? 'player';
                $clubId = session('current_club_id');
            @endphp

            <ul class="side-menu">
                {{-- ═══════════════════════════════════════════════════════════════
                     ОБЩИЕ ПУНКТЫ (все роли)
                ═══════════════════════════════════════════════════════════════ --}}
                
                <li>
                    <h3>Главное</h3>
                </li>
                
                {{-- Dashboard --}}
                <li class="slide">
                    <a class="side-menu__item has-link" href="{{url('dashboard')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        <span class="side-menu__label">Главная</span>
                    </a>
                </li>

                {{-- Профиль --}}
                <li class="slide">
                    <a class="side-menu__item has-link" href="{{url('profile')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span class="side-menu__label">Мой профиль</span>
                    </a>
                </li>

                {{-- ═══════════════════════════════════════════════════════════════
                     АДМИНИСТРАТОР КЛУБА
                ═══════════════════════════════════════════════════════════════ --}}
                @if($role === 'admin')
                    <li>
                        <h3>Управление клубом</h3>
                    </li>
                    
                    {{-- Клуб --}}
                    <li class="slide">
                        <a class="side-menu__item has-link" href="{{url('club')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span class="side-menu__label">Клуб</span>
                        </a>
                    </li>

                    {{-- Команды --}}
                    <li class="slide">
                        <a class="side-menu__item has-link" href="{{url('club/teams')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span class="side-menu__label">Команды</span>
                        </a>
                    </li>

                    {{-- Сотрудники --}}
                    <li class="slide">
                        <a class="side-menu__item has-link" href="{{url('club/staff')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg>
                            <span class="side-menu__label">Сотрудники</span>
                        </a>
                    </li>

                    {{-- Сезоны --}}
                    <li class="slide">
                        <a class="side-menu__item has-link" href="{{url('club/seasons')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <span class="side-menu__label">Сезоны</span>
                        </a>
                    </li>

                    {{-- Инвайты --}}
                    <li class="slide">
                        <a class="side-menu__item has-link" href="{{url('club/invites')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                <line x1="9" y1="10" x2="15" y2="10"></line>
                                <line x1="12" y1="7" x2="12" y2="13"></line>
                            </svg>
                            <span class="side-menu__label">Приглашения</span>
                        </a>
                    </li>

                    {{-- Заявки на вступление --}}
                    <li class="slide">
                        <a class="side-menu__item has-link" href="{{route('join.requests')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg>
                            <span class="side-menu__label">Заявки на вступление</span>
                        </a>
                    </li>
                @endif

                {{-- ═══════════════════════════════════════════════════════════════
                     ТРЕНЕР + АДМИН
                ═══════════════════════════════════════════════════════════════ --}}
                @if(in_array($role, ['admin', 'coach']))
                    <li>
                        <h3>Тренировочный процесс</h3>
                    </li>

                    {{-- Тренировки --}}
                    <li class="slide">
                        <a class="side-menu__item" data-bs-toggle="slide" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <span class="side-menu__label">Тренировки</span><i class="angle fa fa-angle-right"></i>
                        </a>
                        <ul class="slide-menu">
                            <li><a href="{{url('trainings')}}" class="slide-item">Расписание</a></li>
                            <li><a href="{{url('trainings/calendar')}}" class="slide-item">Календарь</a></li>
                            <li><a href="{{url('trainings/recurring')}}" class="slide-item">Шаблоны</a></li>
                        </ul>
                    </li>

                    {{-- Матчи --}}
                    <li class="slide">
                        <a class="side-menu__item has-link" href="{{url('matches')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                <line x1="2" y1="12" x2="22" y2="12"></line>
                            </svg>
                            <span class="side-menu__label">Матчи</span>
                        </a>
                    </li>

                    {{-- Турниры --}}
                    <li class="slide">
                        <a class="side-menu__item has-link" href="{{url('tournaments')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                            <span class="side-menu__label">Турниры</span>
                        </a>
                    </li>

                    {{-- Объявления --}}
                    <li class="slide">
                        <a class="side-menu__item has-link" href="{{url('announcements')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            <span class="side-menu__label">Объявления</span>
                        </a>
                    </li>

                    {{-- Площадки --}}
                    <li class="slide">
                        <a class="side-menu__item has-link" href="{{url('venues')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <span class="side-menu__label">Площадки</span>
                        </a>
                    </li>
                @endif

                {{-- ═══════════════════════════════════════════════════════════════
                     ИГРОК + РОДИТЕЛЬ
                ═══════════════════════════════════════════════════════════════ --}}
                @if(in_array($role, ['player', 'parent']))
                    <li>
                        <h3>Моя команда</h3>
                    </li>

                    {{-- Расписание --}}
                    <li class="slide">
                        <a class="side-menu__item has-link" href="{{url('schedule')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <span class="side-menu__label">Расписание</span>
                        </a>
                    </li>

                    {{-- RSVP / Отклики --}}
                    <li class="slide">
                        <a class="side-menu__item has-link" href="{{url('responses')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span class="side-menu__label">Мои отклики</span>
                        </a>
                    </li>
                @endif

                {{-- ═══════════════════════════════════════════════════════════════
                     ИГРОК
                ═══════════════════════════════════════════════════════════════ --}}
                @if($role === 'player')
                    {{-- Статистика --}}
                    <li class="slide">
                        <a class="side-menu__item has-link" href="{{url('statistics')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                            <span class="side-menu__label">Моя статистика</span>
                        </a>
                    </li>
                @endif

                {{-- ═══════════════════════════════════════════════════════════════
                     РОДИТЕЛЬ
                ═══════════════════════════════════════════════════════════════ --}}
                @if($role === 'parent')
                    {{-- Дети --}}
                    <li class="slide">
                        <a class="side-menu__item has-link" href="{{url('children')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span class="side-menu__label">Мои дети</span>
                        </a>
                    </li>
                @endif

                {{-- ═══════════════════════════════════════════════════════════════
                     ОБЩИЕ (все роли)
                ═══════════════════════════════════════════════════════════════ --}}
                <li>
                    <h3>Система</h3>
                </li>

                {{-- Настройки --}}
                <li class="slide">
                    <a class="side-menu__item has-link" href="{{url('settings')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        <span class="side-menu__label">Настройки</span>
                    </a>
                </li>

                {{-- Помощь --}}
                <li class="slide">
                    <a class="side-menu__item has-link" href="{{url('faq')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        <span class="side-menu__label">Помощь</span>
                    </a>
                </li>
            </ul>

            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg>
            </div>
        </div>
    </div>
</div>
<!--/APP-SIDEBAR-->
