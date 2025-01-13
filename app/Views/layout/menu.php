<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<div class="container">
		<a class="navbar-brand" href="<?= base_url('dashboard') ?>">QR Doc</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav me-auto">
				<li class="nav-item">
					<a class="nav-link <?= current_url() == base_url('dashboard') ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
						<i class="fas fa-tachometer-alt"></i> Dashboard
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= current_url() == base_url('qrcode') ? 'active' : '' ?>" href="<?= base_url('qrcode') ?>">
						<i class="fas fa-qrcode"></i> QR Generator
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= current_url() == base_url('qrcode/scan') ? 'active' : '' ?>" href="<?= base_url('qrcode/scan') ?>">
						<i class="fas fa-camera"></i> QR Scanner
					</a>
				</li>
				<?php if(session()->get('role') === 'admin'): ?>
				<li class="nav-item">
					<a class="nav-link <?= current_url() == base_url('user-management') ? 'active' : '' ?>" href="<?= base_url('user-management') ?>">
						<i class="fas fa-users"></i> User Management
					</a>
				</li>
				<?php endif; ?>
			</ul>
			<ul class="navbar-nav">
				<li class="nav-item dropdown">
                    <?php 
                    $notificationModel = new \App\Models\NotificationModel();
                    $notifications = $notificationModel->where('user_id', session()->get('id'))
                                                     ->orderBy('created_at', 'DESC')
                                                     ->limit(5)
                                                     ->find();
                    $unreadCount = $notificationModel->getUnreadCount(session()->get('id'));
                    ?>
                    <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <?php if ($unreadCount > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $unreadCount ?>
                        </span>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 bg-light border-bottom">
                            <h6 class="mb-0">Notifications</h6>
                            <?php if ($unreadCount > 0): ?>
                            <a href="<?= base_url('notification/markAllAsRead') ?>" class="text-decoration-none small">Mark all as read</a>
                            <?php endif; ?>
                        </div>
                        <?php if (empty($notifications)): ?>
                            <div class="dropdown-item text-muted">No notifications</div>
                        <?php else: ?>
                            <?php foreach ($notifications as $notification): ?>
                            <div class="dropdown-item <?= !$notification['is_read'] ? 'bg-light' : '' ?>">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted"><?= date('M d, H:i', strtotime($notification['created_at'])) ?></small>
                                    <?php if (!$notification['is_read']): ?>
                                    <a href="<?= base_url('notification/markAsRead/' . $notification['id']) ?>" class="badge bg-primary text-decoration-none">Mark as read</a>
                                    <?php endif; ?>
                                </div>
                                <div class="fw-bold"><?= $notification['title'] ?></div>
                                <div class="small"><?= $notification['message'] ?></div>
                            </div>
                            <?php endforeach; ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center small text-primary" href="<?= base_url('notification') ?>">View All Notifications</a>
                        <?php endif; ?>
                    </div>
                </li>

                <style>
                .notification-dropdown {
                    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
                }
                .notification-dropdown .dropdown-item {
                    white-space: normal;
                    border-bottom: 1px solid #eee;
                    padding: 0.75rem 1rem;
                }
                .notification-dropdown .dropdown-item:last-child {
                    border-bottom: none;
                }
                </style>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
						<i class="fas fa-user"></i> <?= session()->get('username') ?>
					</a>
					<ul class="dropdown-menu dropdown-menu-end">
						<li>
							<a class="dropdown-item" href="<?= base_url('profile') ?>">
								<i class="fas fa-user-edit"></i> Edit Profile
							</a>
						</li>
						<li><hr class="dropdown-divider"></li>
						<li>
							<a class="dropdown-item" href="<?= base_url('auth/logout') ?>">
								<i class="fas fa-sign-out-alt"></i> Logout
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>