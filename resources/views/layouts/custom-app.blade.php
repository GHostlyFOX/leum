<!doctype html>
<html lang="ru" dir="ltr">
	<head>

		<!-- META DATA -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="Сбор — единое пространство для детского спорта">
		<meta name="author" content="Сбор">

        @include('layouts.components.styles')
        @livewireStyles
        @stack('styles')

    </head>

    <body class="ltr login-img">

            <!-- GLOBAL-LOADER -->
            <div id="global-loader">
                <img src="{{asset('assets/images/loader.svg')}}" class="loader-img" alt="Loader">
            </div>

                {{ $slot ?? '' }}
                @yield('content')

        @include('layouts.components.custom-scripts')
        @livewireScripts
        @stack('scripts')

    </body>

</html>
