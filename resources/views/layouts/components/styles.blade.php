<!-- TITLE -->
		<title>Youth Sports Team Manager</title>

        <!-- FAVICON -->
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/images/brand/favicon.ico')}}" />

        <!-- TAILWIND CSS -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- FONT-AWESOME CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- CUSTOM STYLES -->
        <style>
            .sidebar {
                transition: all 0.3s ease;
            }
            .tab-content {
                display: none;
            }
            .tab-content.active {
                display: block;
            }
            .calendar-day {
                transition: all 0.2s ease;
            }
            .calendar-day:hover {
                transform: scale(1.05);
            }
            .player-card {
                transition: all 0.2s ease;
            }
            .player-card:hover {
                transform: translateY(-5px);
            }
            .dropdown-content {
                display: none;
                position: absolute;
                z-index: 10;
            }
            .dropdown:hover .dropdown-content {
                display: block;
            }
            @media (max-width: 768px) {
                .sidebar {
                    transform: translateX(-100%);
                    position: fixed;
                    top: 0;
                    left: 0;
                    height: 100vh;
                    z-index: 20;
                }
                .sidebar.open {
                    transform: translateX(0);
                }
                .overlay {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: rgba(0,0,0,0.5);
                    z-index: 10;
                }
                .overlay.open {
                    display: block;
                }
            }
        </style>
