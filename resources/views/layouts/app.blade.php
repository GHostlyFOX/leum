<!doctype html>
<html lang="en" dir="ltr">
	<head>
		<!-- META DATA -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="Noa - Laravel Bootstrap 5 Admin & Dashboard Template">
		<meta name="author" content="Spruko Technologies Private Limited">
		<meta name="keywords" content="laravel admin template, bootstrap admin template, admin dashboard template, admin dashboard, admin template, admin, bootstrap 5, laravel admin, laravel admin dashboard template, laravel ui template, laravel admin panel, admin panel, laravel admin dashboard, laravel template, admin ui dashboard">

        @include('layouts.components.styles')

    </head>

    <body class="bg-gray-50">
        <!-- Mobile Menu Button -->
        <div class="md:hidden fixed top-4 left-4 z-30">
            <button id="menuBtn" class="p-2 rounded-md bg-blue-600 text-white">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Overlay for mobile menu -->
        <div id="overlay" class="overlay"></div>

        @include('layouts.components.app-sidebar')

        <!-- Main Content -->
        <div class="md:ml-64 min-h-screen">
            @include('layouts.components.app-header')

            <!--app-content open-->
            <div class="app-content main-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                            @yield('content')

                    </div>
                </div>
            </div>
                <!-- CONTAINER CLOSED -->
        </div>

        @include('layouts.components.scripts')

    </body>

</html>
