<?= $this->extend('_layout') ?>

<?= $this->section('content') ?>

<main class="py-5 my-auto">

    <section>
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
                        class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <div class="container">
                        <?php if (!session()->auth): ?>
                            <span class="nav-link">
                                <h1>Welcome, Guest!</h1>
                                <h1>Please Login!</h1>
                            </span>
                        <?php else: ?>
                            <span class="nav-link">
                                <h1>Welcome!, <?= session('auth')['username']; ?>!</h1>
                            </span>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
</main>

<?= $this->endSection() ?>