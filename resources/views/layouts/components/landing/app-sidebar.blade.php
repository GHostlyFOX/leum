						<!--APP-SIDEBAR-->
						<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
						<div class="app-sidebar bg-transparent horizontal-main">
							<div class="container">
								<div class="main-sidemenu navbar px-0">
									<a class="navbar-brand ps-0 d-none d-lg-block" href="{{url('/')}}">
										<img alt="" class="logo-2" src="{{asset('assets/images/brand/logo-3.png')}}">
										<img alt="" class="dark-landinglogo" src="{{asset('assets/images/brand/logo.png')}}">
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
