<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Notifications</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
	<?= view('layout/menu') ?>

	<div class="container mt-4">
		<div class="card">
			<div class="card-header d-flex justify-content-between align-items-center">
				<h3><i class="fas fa-bell"></i> Notifications</h3>
				<a href="<?= base_url('notification/markAllAsRead') ?>" class="btn btn-primary btn-sm">
					Mark All as Read
				</a>
			</div>
			<div class="card-body">
				<?php if (empty($notifications)): ?>
					<p class="text-center">No notifications found</p>
				<?php else: ?>
					<div class="list-group">
						<?php foreach ($notifications as $notification): ?>
							<div class="list-group-item list-group-item-action <?= !$notification['is_read'] ? 'bg-light' : '' ?>">
								<div class="d-flex w-100 justify-content-between">
									<h5 class="mb-1"><?= $notification['title'] ?></h5>
									<small><?= date('M d, Y H:i', strtotime($notification['created_at'])) ?></small>
								</div>
								<p class="mb-1"><?= $notification['message'] ?></p>
								<?php if (!$notification['is_read']): ?>
									<a href="<?= base_url('notification/markAsRead/' . $notification['id']) ?>" 
									   class="btn btn-sm btn-success mt-2">
										Mark as Read
									</a>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>