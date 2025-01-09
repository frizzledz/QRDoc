<nav class="navbar bg-body-tertiary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('')  ?>">QR DOC</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">QR DOC</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav mb-2 mb-md-0 d-flex">
                    <?php if (!session()->auth): ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-success" aria-current="page" href="<?= base_url('login')  ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary" href="<?= base_url('register')  ?>">Register</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <span class="nav-link">
                                <b><?= session('auth')['username']; ?></b>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-danger me-2" aria-current="page" href="<?= base_url('logout')  ?>">Logout</a>
                        </li>
                        </li>
                    <?php endif ?>
                </ul>
            </div>
        </div>
    </div>
</nav>