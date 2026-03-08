        <!-- BACK-TO-TOP -->
        <a href="#top" id="back-to-top"><i class="fa fa-long-arrow-up"></i></a>

        <!-- JQUERY JS -->
        <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>

        <!-- BOOTSTRAP JS -->
        <script src="{{asset('assets/plugins/bootstrap/js/popper.min.js')}}"></script>
        <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

        <!-- Owl carousel JS -->
        <script src="{{asset('assets/plugins/company-slider/slider.js')}}"></script>
        <script src="{{asset('assets/plugins/owl-carousel/owl.carousel.js')}}"></script>

        <!-- landing JS -->
        <script src="{{asset('assets/js/landing.js')}}"></script>

        <!-- Rotating slogans -->
        <script>
        (function() {
            var slogans = [
                'Сбор — команда начинается с единства.',
                'Сбор — вся команда в одном ритме.',
                'Сбор — когда команда действительно вместе.'
            ];
            var el = document.getElementById('hero-slogan');
            if (!el) return;

            // Shuffle array (Fisher-Yates)
            for (var i = slogans.length - 1; i > 0; i--) {
                var j = Math.floor(Math.random() * (i + 1));
                var t = slogans[i]; slogans[i] = slogans[j]; slogans[j] = t;
            }

            var idx = 0;
            el.textContent = slogans[idx];

            setInterval(function() {
                el.style.opacity = '0';
                setTimeout(function() {
                    idx = (idx + 1) % slogans.length;
                    el.textContent = slogans[idx];
                    el.style.opacity = '1';
                }, 500);
            }, 4000);
        })();
        </script>

        @yield('scripts')
