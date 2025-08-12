<!-- BACK-TO-TOP -->
        <a href="#top" id="back-to-top"><i class="fa fa-long-arrow-up"></i></a>

        @yield('scripts')

        <!-- CUSTOM JS -->
        <script>
            // Mobile menu toggle
            const menuBtn = document.getElementById('menuBtn');
            const closeMenuBtn = document.getElementById('closeMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            if(menuBtn) {
                menuBtn.addEventListener('click', () => {
                    sidebar.classList.add('open');
                    overlay.classList.add('open');
                });
            }

            if(closeMenuBtn) {
                closeMenuBtn.addEventListener('click', () => {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('open');
                });
            }

            if(overlay) {
                overlay.addEventListener('click', () => {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('open');
                });
            }

            // Tab functionality
            const tabs = document.querySelectorAll('[data-tab]');
            if(tabs) {
                tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        const tabId = tab.getAttribute('data-tab');
                        const tabContent = document.getElementById(tabId);

                        // Hide all tab contents
                        document.querySelectorAll('.tab-content').forEach(content => {
                            content.classList.remove('active');
                        });

                        // Deactivate all tabs
                        document.querySelectorAll('[data-tab]').forEach(t => {
                            t.classList.remove('bg-blue-600', 'text-white');
                            t.classList.add('bg-white', 'text-gray-700');
                        });

                        // Activate current tab
                        tab.classList.remove('bg-white', 'text-gray-700');
                        tab.classList.add('bg-blue-600', 'text-white');

                        // Show current tab content
                        if(tabContent) {
                            tabContent.classList.add('active');
                        }
                    });
                });

                // Initialize first tab as active
                if (tabs.length > 0) {
                    tabs[0].click();
                }
            }
        </script>
