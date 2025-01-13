<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://use.fontawesome.com/releases/v6.3.0/css/all.css" rel="stylesheet">
</head>
<body>
	<?= $this->include('layout/nav') ?>

	<div class="container mt-4">
		<h2 class="mb-4"><i class="fas fa-tasks me-2"></i><?= $title ?></h2>

		<?php if (session()->getFlashdata('success')): ?>
			<div class="alert alert-success">
				<?= session()->getFlashdata('success') ?>
			</div>
		<?php endif; ?>

		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>User</th>
								<th>Purpose</th>
								<th>Image</th>
								<th>Created At</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($pendingQrCodes)): ?>
								<?php foreach ($pendingQrCodes as $qr): ?>
									<tr>
										<td><?= esc($qr['username']) ?></td>
										<td><?= esc($qr['purpose']) ?></td>
										<td>
											<img src="<?= base_url('uploads/' . $qr['image_path']) ?>" 
												 alt="QR Code" 
												 style="max-width: 50px;">
										</td>
										<td><?= date('M d, Y h:i A', strtotime($qr['created_at'])) ?></td>
										<td>
											<div class="btn-group" role="group">
												<a href="<?= base_url('uploads/' . $qr['image_path']) ?>" 
												   class="btn btn-sm btn-primary" 
												   target="_blank">
													<i class="fas fa-eye"></i>
												</a>
												<form action="<?= base_url('qrcode/verify/' . $qr['id']) ?>" 
													  method="post" 
													  class="d-inline">
													<?= csrf_field() ?>
													<button type="submit" class="btn btn-sm btn-success">
														<i class="fas fa-check"></i>
													</button>
												</form>
												<form action="<?= base_url('qrcode/reject/' . $qr['id']) ?>" 
													  method="post" 
													  class="d-inline">
													<?= csrf_field() ?>
													<button type="submit" class="btn btn-sm btn-danger">
														<i class="fas fa-times"></i>
													</button>
												</form>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="5" class="text-center">No pending QR codes found</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>