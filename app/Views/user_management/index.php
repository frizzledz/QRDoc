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
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="<?= base_url('auth/logout') ?>">
							<i class="fas fa-sign-out-alt"></i> Logout
						</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="container mt-4">
		<?php if (session()->getFlashdata('success')): ?>
			<div class="alert alert-success">
				<?= session()->getFlashdata('success') ?>
			</div>
		<?php endif; ?>

		<?php if (session()->getFlashdata('error')): ?>
			<div class="alert alert-danger">
				<?= session()->getFlashdata('error') ?>
			</div>
		<?php endif; ?>

		<div class="card">
			<div class="card-header">
				<h3>User Management</h3>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Username</th>
								<th>Email</th>
								<th>Role</th>
								<th>Status</th>
								<th>Created At</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($users as $user): ?>
							<tr>
								<td><?= $user['id'] ?></td>
								<td><?= $user['username'] ?></td>
								<td><?= $user['email'] ?></td>
								<td>
									<?php if(session()->get('role') === 'admin'): ?>
										<form action="<?= base_url('user-management/updateRole/' . $user['id']) ?>" method="post" class="d-inline">
											<select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
												<option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
												<option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
											</select>
										</form>
									<?php else: ?>
										<?= $user['role'] ?>
									<?php endif; ?>
								</td>
								<td>
									<?php if (!$user['is_verified']): ?>
										<form action="<?= base_url('user-management/verifyUser/' . $user['id']) ?>" method="post" class="d-inline">
											<button type="submit" class="btn btn-warning btn-sm">Verify User</button>
										</form>
									<?php else: ?>
										<span class="badge bg-success">Verified</span>
									<?php endif; ?>
								</td>
								<td><?= $user['created_at'] ?></td>
								<td>
									<a href="<?= base_url('user-management/edit/' . $user['id']) ?>" class="btn btn-sm btn-primary">
										<i class="fas fa-edit"></i>
									</a>
									<?php if ($user['id'] != session()->get('id')): ?>
									<a href="<?= base_url('user-management/delete/' . $user['id']) ?>" 
									   class="btn btn-sm btn-danger" 
									   onclick="return confirm('Are you sure you want to delete this user?')">
										<i class="fas fa-trash"></i>
									</a>
									<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>