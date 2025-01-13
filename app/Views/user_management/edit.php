<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container">
			<a class="navbar-brand" href="#">QR Doc Admin</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav me-auto">
					<li class="nav-item">
						<a class="nav-link" href="<?= base_url('dashboard') ?>">
							<i class="fas fa-tachometer-alt"></i> Dashboard
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="<?= base_url('user-management') ?>">
							<i class="fas fa-users"></i> Users
						</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="container mt-4">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<h3>Edit User</h3>
					</div>
					<div class="card-body">
						<?php if (session()->getFlashdata('errors')): ?>
							<div class="alert alert-danger">
								<?= implode('<br>', session()->getFlashdata('errors')) ?>
							</div>
						<?php endif; ?>

						<form action="<?= base_url('user-management/update/' . $user['id']) ?>" method="post">
							<div class="mb-3">
								<label for="username" class="form-label">Username</label>
								<input type="text" name="username" class="form-control" value="<?= $user['username'] ?>" required>
							</div>
							<div class="mb-3">
								<label for="email" class="form-label">Email</label>
								<input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" required>
							</div>
							<div class="mb-3">
								<label for="password" class="form-label">New Password (leave blank to keep current)</label>
								<input type="password" name="password" class="form-control">
							</div>
							<div class="d-grid gap-2">
								<button type="submit" class="btn btn-primary">Update User</button>
								<a href="<?= base_url('user-management') ?>" class="btn btn-secondary">Cancel</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>