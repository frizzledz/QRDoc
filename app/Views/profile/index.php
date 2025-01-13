<?= $this->include('layout/head') ?>
<?= $this->include('layout/nav') ?>

<div class="container mt-4">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card border-0 shadow-sm">
				<div class="card-header bg-transparent">
					<h5 class="mb-0"><i class="fas fa-user-edit"></i> Edit Profile</h5>
				</div>
				<div class="card-body">
					<?php if (session()->getFlashdata('success')): ?>
						<div class="alert alert-success">
							<i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
						</div>
					<?php endif; ?>

					<?php if (session()->getFlashdata('error')): ?>
						<div class="alert alert-danger">
							<i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
						</div>
					<?php endif; ?>

					<?php if (session()->getFlashdata('errors')): ?>
						<div class="alert alert-danger">
							<i class="fas fa-exclamation-circle me-2"></i>
							<?= implode('<br>', session()->getFlashdata('errors')) ?>
						</div>
					<?php endif; ?>

					<form action="<?= base_url('profile/update') ?>" method="post">
						<div class="mb-3">
							<label for="username" class="form-label">Username</label>
							<div class="input-group">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
								<input type="text" name="username" class="form-control" value="<?= $user['username'] ?>" required>
							</div>
						</div>
						<div class="mb-3">
							<label for="email" class="form-label">Email</label>
							<div class="input-group">
								<span class="input-group-text"><i class="fas fa-envelope"></i></span>
								<input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" required>
							</div>
						</div>
						<div class="mb-3">
							<label for="password" class="form-label">New Password (leave blank to keep current)</label>
							<div class="input-group">
								<span class="input-group-text"><i class="fas fa-lock"></i></span>
								<input type="password" name="password" class="form-control">
							</div>
						</div>
						<div class="mb-3">
							<label for="password_confirm" class="form-label">Confirm New Password</label>
							<div class="input-group">
								<span class="input-group-text"><i class="fas fa-lock"></i></span>
								<input type="password" name="password_confirm" class="form-control">
							</div>
						</div>
						<div class="d-grid gap-2">
							<button type="submit" class="btn btn-primary">
								<i class="fas fa-save me-2"></i>Update Profile
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
.card {
	transition: all 0.3s ease;
}

.input-group-text {
	background-color: transparent;
	border-right: none;
}

.input-group .form-control {
	border-left: none;
}

.input-group .form-control:focus {
	border-color: var(--bs-border-color);
	box-shadow: none;
}

.input-group:focus-within {
	box-shadow: 0 0 0 0.25rem rgba(111, 117, 229, 0.25);
	border-radius: 0.375rem;
}

.input-group:focus-within .input-group-text,
.input-group:focus-within .form-control {
	border-color: var(--primary-color);
}

/* Dark mode specific styles */
[data-bs-theme="dark"] {
	--bs-body-bg: #212529;
}

[data-bs-theme="dark"] .card {
	background-color: #2b3035;
}

[data-bs-theme="dark"] .card-header {
	border-bottom-color: #373b3e;
}

[data-bs-theme="dark"] .input-group-text {
	border-color: var(--bs-border-color);
	color: var(--bs-body-color);
}

[data-bs-theme="dark"] .form-control {
	border-color: var(--bs-border-color);
	background-color: #2b3035;
	color: var(--bs-body-color);
}
</style>