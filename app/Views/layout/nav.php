<?= $this->include('layout/head') ?>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url('dashboard') ?>">
                <i class="bi bi-qr-code fs-4"></i>
                <span class="d-none d-sm-inline fw-bold">QRDoc</span>
            </a>
            
            <div class="d-flex align-items-center gap-2 order-lg-last">
                <button class="nav-link px-2" id="themeSwitcher">
                    <i class="fas fa-moon" id="themeIcon"></i>
                </button>
                
                <div class="hover-menu">
                    <div class="nav-link d-flex align-items-center gap-2 px-2">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle">
                                <span><?= strtoupper(substr(session()->get('username'), 0, 1)) ?></span>
                            </div>
                            <span class="d-none d-md-inline ms-2"><?= session()->get('username') ?></span>
                        </div>
                        <i class="fas fa-chevron-down small"></i>
                    </div>
                    <ul class="hover-menu-content shadow">
                        <li>
                            <a class="hover-menu-item" href="<?= base_url('profile') ?>">
                                <i class="fas fa-user-circle me-2"></i>Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="hover-menu-item" href="<?= base_url('logout') ?>">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>


            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav gap-1">
                    <?php
                    $menuItems = [
                        ['url' => 'dashboard', 'icon' => 'home', 'text' => 'Dashboard'],
                        ['url' => 'qrcode', 'icon' => 'qrcode', 'text' => 'My QR Codes'],
                        ['url' => 'qrcode/generate', 'icon' => 'plus', 'text' => 'Generate'],
                        ['url' => 'qrcode/scan', 'icon' => 'camera', 'text' => 'Scan'],
                        ['url' => 'qrcode/imbue', 'icon' => 'file-pdf', 'text' => 'Imbue'],
                    ];

                    foreach ($menuItems as $item): ?>
                        <li class="nav-item">
                            <a class="nav-link px-3 <?= url_is($item['url']) ? 'active' : '' ?>" 
                               href="<?= base_url($item['url']) ?>">
                                <i class="fas fa-<?= $item['icon'] ?> me-2"></i><?= $item['text'] ?>
                            </a>
                        </li>
                    <?php endforeach; ?>

                    <?php if (session()->get('role') === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link px-3 <?= url_is('qrcode/pending') ? 'active' : '' ?>" 
                               href="<?= base_url('qrcode/pending') ?>">
                                <i class="fas fa-clock me-2"></i>Pending QR Codes
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <style>
        :root {
            --primary-color: #6f75e5;
            --primary-light: rgba(111, 117, 229, 0.1);
        }

        .navbar {
            padding: 0.75rem 0;
            background: var(--bs-body-bg);
            border-bottom: 1px solid var(--bs-border-color);
            backdrop-filter: blur(10px);
        }
        
        .navbar-brand {
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .nav-link {
            font-weight: 500;
            transition: all 0.2s;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            color: var(--bs-body-color);
        }
        
        .nav-link:hover {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }
        
        .nav-link.active {
            color: var(--primary-color) !important;
            background-color: var(--primary-light);
        }
        
        .dropdown-menu {
            border-radius: 6px;
            border: 1px solid var(--bs-border-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 0.25rem;
            min-width: 180px;
            margin-top: 0.5rem !important;
        }
        
        .dropdown-item {
            border-radius: 4px;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            color: var(--bs-body-color);
            cursor: pointer;
            user-select: none;
        }

        .dropdown-divider {
            margin: 0.25rem 0;
            opacity: 0.1;
        }
        
        .dropdown-item:hover {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }

        .avatar-circle {
            width: 32px;
            height: 32px;
            background-color: var(--primary-light);
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Dark mode styles */
        [data-bs-theme="dark"] {
            --primary-light: rgba(111, 117, 229, 0.2);
        }

        [data-bs-theme="dark"] .navbar {
            box-shadow: 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        [data-bs-theme="dark"] .dropdown-menu {
            background-color: #2c3034;
            border-color: rgba(255, 255, 255, 0.05);
        }
        
        [data-bs-theme="dark"] .dropdown-divider {
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: var(--bs-body-bg);
                padding: 1rem;
                border-radius: 12px;
                border: 1px solid var(--bs-border-color);
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                position: absolute;
                top: 100%;
                left: 1rem;
                right: 1rem;
                margin-top: 0.5rem;
            }
            
            .nav-link {
                padding: 0.75rem 1rem;
            }
        }

        /* Update the hover effect for dropdown */
        @media (min-width: 992px) {
            .dropdown:hover .dropdown-menu {
                display: block;
                animation: fadeIn 0.1s ease;
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
        }

        /* Add this to make the entire menu item clickable */
        .dropdown-menu li a {
            display: block;
            width: 100%;
            text-decoration: none;
        }

        /* Add pointer cursor to the dropdown toggle */
        .dropdown .nav-link {
            cursor: pointer;
        }

        /* Make sure dropdown stays visible when moving to menu items */
        @media (min-width: 992px) {
            .dropdown-menu {
                display: none;
                margin-top: 0.5rem !important;
            }

            .dropdown:hover .dropdown-menu,
            .dropdown-menu:hover {
                display: block;
                animation: fadeIn 0.1s ease;
            }
        }

        /* Replace all dropdown and hover menu styles with these */
        .hover-menu {
            position: relative;
        }

        .hover-menu-content {
            position: absolute;
            top: 70%;
            right: 0;
            display: none;
            min-width: 180px;
            padding: 0.5rem 0;
            margin: 0.5rem 0;
            list-style: none;
            background-color: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: 0.375rem;
            z-index: 1000;
        }

        .hover-menu:hover .hover-menu-content {
            display: block;
            animation: fadeIn 0.1s ease;
        }

        .hover-menu-item {
            display: block;
            width: 100%;
            padding: 0.5rem 1rem;
            color: var(--bs-body-color);
            text-decoration: none;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out;
        }

        .hover-menu-item:hover {
            color: var(--primary-color);
            background-color: var(--primary-light);
            text-decoration: none;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Light mode adjustments */
        [data-bs-theme="light"] .hover-menu-content {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        [data-bs-theme="light"] .hover-menu-content li a {
            color: #333;
        }

        [data-bs-theme="light"] .hover-menu-content li a:hover {
            background: rgba(0, 0, 0, 0.05);
            color: #0d6efd;
        }

        [data-bs-theme="light"] .hover-menu-content hr {
            border-color: rgba(0, 0, 0, 0.1);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // Theme switcher functionality
        const themeSwitcher = document.getElementById('themeSwitcher');
        const themeIcon = document.getElementById('themeIcon');

        function updateTheme(isDark) {
            document.documentElement.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
            themeIcon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }

        // Initialize theme
        const savedTheme = localStorage.getItem('theme') || 
                          (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        updateTheme(savedTheme === 'dark');

        themeSwitcher.addEventListener('click', () => {
            const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            updateTheme(!isDark);
        });

        // Close mobile menu on click outside
        $(document).click(function(e) {
            if (!$(e.target).closest('.navbar').length) {
                $('.navbar-collapse').collapse('hide');
            }
        });
        
        $('.navbar-nav>li>a').on('click', function(){
            $('.navbar-collapse').collapse('hide');
        });
    </script>
</body>
</html> 