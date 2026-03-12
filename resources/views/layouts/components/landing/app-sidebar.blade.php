						<!--APP-SIDEBAR-->
						<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
						<div class="app-sidebar bg-transparent horizontal-main">
							<div class="container">
								<div class="main-sidemenu navbar px-0">
									<a class="navbar-brand ps-0 d-none d-lg-block" href="{{url('/')}}" style="display: flex; align-items: center;">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 420 100" style="height: 40px; width: auto;">
										  <g transform="translate(10, 5)">
											<circle cx="45" cy="45" r="43" fill="#74bc1f"/>
											<circle cx="45" cy="36" r="9" fill="#000000"/>
											<path d="M45 48 L34 77 L41 77 L45 62 L49 77 L56 77 Z" fill="#000000"/>
											<circle cx="22" cy="43" r="7" fill="#ffffff"/>
											<path d="M22 52 L13 77 L19 77 L22 65 L25 77 L31 77 Z" fill="#ffffff"/>
											<circle cx="68" cy="43" r="7" fill="#ffffff"/>
											<path d="M68 52 L59 77 L65 77 L68 65 L71 77 L77 77 Z" fill="#ffffff"/>
											<ellipse cx="45" cy="80" rx="25" ry="7" fill="none" stroke="#000000" stroke-width="2" opacity="0.3"/>
										  </g>
										  <text x="115" y="62" font-family="Arial, sans-serif" font-size="46" font-weight="bold" fill="#000000">sbor<tspan fill="#74bc1f">.team</tspan></text>
										</svg>
									</a>
									<ul class="side-menu">
										<li class="slide">
											<a class="side-menu__item active" data-bs-toggle="slide" href="#home"><span class="side-menu__label">Главная</span></a>
										</li>
										<li class="slide">
											<a class="side-menu__item" data-bs-toggle="slide" href="#features"><span class="side-menu__label">Возможности</span></a>
										</li>
										<li class="slide">
											<a class="side-menu__item" data-bs-toggle="slide" href="#stats"><span class="side-menu__label">Аналитика</span></a>
										</li>
										<li class="slide">
											<a class="side-menu__item" data-bs-toggle="slide" href="#audience"><span class="side-menu__label">Для кого</span></a>
										</li>
										<li class="slide">
											<a class="side-menu__item" data-bs-toggle="slide" href="#advantages"><span class="side-menu__label">Как начать</span></a>
										</li>
										<li class="slide">
											<a class="side-menu__item" data-bs-toggle="slide" href="#testimonials"><span class="side-menu__label">Отзывы</span></a>
										</li>
									</ul>
									<div class="header-nav-right d-flex">
										<a href="{{route('auth.register')}}" target="_blank" class="btn btn-pill btn-outline-primary btn-w-md py-2 me-1 my-auto d-lg-none d-xl-block d-block">
											Регистрация
										</a>
										<a href="{{route('auth.loginForm')}}" target="_blank" class="btn btn-pill btn-primary btn-w-md py-2 my-auto d-lg-none d-xl-block d-block">
											Войти
										</a>
									</div>
								</div>
							</div>
						</div>
						<!--/APP-SIDEBAR-->
