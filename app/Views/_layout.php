<!DOCTYPE html>
<html class="h-100" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= (!empty($title) ? $title : 'No Title') ?></title>

    <?= $this->include('layout/css') ?>
</head>

<body class="d-flex flex-column h-100">

    <?= $this->include('layout/nav') ?>

    <?= $this->renderSection('content') ?>

    <?= $this->include('layout/footer') ?>
    <?= $this->include('layout/js') ?>
</body>

</html>