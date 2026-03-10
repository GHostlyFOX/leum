                <!-- app-Header -->
				<div class="hor-header header">
					<div class="container main-container">
						<div class="d-flex align-items-center">
							<a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)"></a>
							<!-- sidebar-toggle-->
							<a class="logo-horizontal" href="{{url('index')}}">
								<img src="{{asset('assets/images/brand/logo.png')}}" class="header-brand-img desktop-logo" alt="logo">
								<img src="{{asset('assets/images/brand/logo-3.png')}}" class="header-brand-img light-logo1" alt="logo">
							</a>
							<!-- LOGO -->

							<!-- Навигация -->
							<nav class="d-none d-lg-flex ms-4 gap-3">
								<a href="#features" class="nav-link text-muted hover-primary">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
										<polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
										<polyline points="2 17 12 22 22 17"></polyline>
										<polyline points="2 12 12 17 22 12"></polyline>
									</svg>
									Возможности
								</a>
								<a href="#stats" class="nav-link text-muted hover-primary">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
										<line x1="18" y1="20" x2="18" y2="10"></line>
										<line x1="12" y1="20" x2="12" y2="4"></line>
										<line x1="6" y1="20" x2="6" y2="14"></line>
									</svg>
									Статистика
								</a>
								<a href="#audience" class="nav-link text-muted hover-primary">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
										<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
										<circle cx="9" cy="7" r="4"></circle>
										<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
										<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
									</svg>
									Для кого
								</a>
								<a href="#testimonials" class="nav-link text-muted hover-primary">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
										<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
									</svg>
									Отзывы
								</a>
							</nav>

							<div class="d-flex order-lg-2 ms-auto header-right-icons">
								<button class="navbar-toggler navresponsive-toggler d-md-none ms-auto" type="button"
									data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
									aria-controls="navbarSupportedContent-4" aria-expanded="false"
									aria-label="Toggle navigation">
									<span class="navbar-toggler-icon fe fe-more-vertical"></span>
								</button>
								<div class="navbar navbar-collapse responsive-navbar p-0">
									<div class="collapse navbar-collapse" id="navbarSupportedContent-4">
										<div class="d-flex order-lg-2 m-4 m-lg-0 m-md-1 gap-2">
											<a href="{{ route('auth.loginForm') }}" class="btn btn-pill btn-outline-primary btn-w-md py-2">
												<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
													<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
													<polyline points="10 17 15 12 10 7"></polyline>
													<line x1="15" y1="12" x2="3" y2="12"></line>
												</svg>
												Войти
											</a>
											<a href="{{ route('auth.register') }}" class="btn btn-pill btn-primary btn-w-md py-2">
												<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
													<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
													<circle cx="12" cy="7" r="4"></circle>
												</svg>
												Регистрация
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /app-Header -->
