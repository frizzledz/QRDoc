<?= $this->include('layout/head') ?>
<?= $this->include('layout/nav') ?>

<div class="container py-4">
	<div class="row mb-4">
		<div class="col-12">
			<div class="card border-0 fade-in">
				<div class="card-header bg-transparent">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<h5 class="mb-0 fw-bold"><i class="fas fa-qrcode me-2"></i><?= $title ?></h5>
						</div>
						<a href="<?= base_url('qrcode/generate') ?>" class="btn btn-primary btn-sm">
							<i class="fas fa-plus me-2"></i>Generate New
						</a>
					</div>
				</div>

				<?php if (session()->getFlashdata('success')): ?>
					<div class="alert alert-success m-3">
						<i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
					</div>
				<?php endif; ?>

				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-hover align-middle mb-0">
							<thead>
								<tr>
									<th class="px-4 py-3 border-bottom">Purpose</th>
									<th class="px-4 py-3 border-bottom">Original Image</th>
									<th class="px-4 py-3 border-bottom">QR Code</th>
									<th class="px-4 py-3 border-bottom">Created At</th>
									<th class="px-4 py-3 border-bottom">Status</th>
									<th class="px-4 py-3 border-bottom">Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($qrCodes)): ?>
									<?php foreach ($qrCodes as $qr): ?>
										<tr>
											<td class="px-4 py-3"><?= esc($qr['purpose']) ?></td>
											<td class="px-4 py-3">
												<img src="<?= base_url('uploads/' . $qr['image_path']) ?>" 
													 alt="Original Image" 
													 class="rounded"
													 style="width: 40px; height: 40px; object-fit: cover;">
											</td>
											<td class="px-4 py-3">
												<img src="<?= base_url('uploads/' . $qr['qr_path']) ?>" 
													 alt="QR Code" 
													 class="rounded"
													 style="width: 40px; height: 40px; object-fit: cover;">
											</td>
											<td class="px-4 py-3">
												<div>
													<i class="fas fa-calendar-alt me-1"></i>
													<?= date('M d, Y', strtotime($qr['created_at'])) ?>
												</div>
												<small class="text-muted">
													<i class="fas fa-clock me-1"></i>
													<?= date('h:i A', strtotime($qr['created_at'])) ?>
												</small>
											</td>
											<td class="px-4 py-3">
												<?php if ($qr['status'] === 'verified'): ?>
													<span class="badge bg-success">
														<i class="fas fa-check me-1"></i>Verified
													</span>
												<?php elseif ($qr['status'] === 'pending'): ?>
													<span class="badge bg-warning">
														<i class="fas fa-hourglass-half me-1"></i>Pending
													</span>
												<?php else: ?>
													<span class="badge bg-danger">
														<i class="fas fa-times me-1"></i>Rejected
													</span>
												<?php endif; ?>
											</td>
											<td class="px-4 py-3">
												<div class="btn-group">
													<a href="<?= base_url('uploads/' . $qr['image_path']) ?>" 
													   class="btn btn-sm btn-outline-primary" 
													   target="_blank" 
													   title="View Original Image">
														<i class="fas fa-image"></i>
													</a>
													<a href="<?= base_url('uploads/' . $qr['qr_path']) ?>" 
													   class="btn btn-sm btn-outline-info" 
													   target="_blank" 
													   title="View QR Code">
														<i class="fas fa-qrcode"></i>
													</a>
													<?php if ($qr['status'] === 'verified'): ?>
														<form action="<?= base_url('qrcode/embedPdf') ?>" method="post" class="d-inline">
															<?= csrf_field() ?>
															<input type="hidden" name="qr_path" value="<?= $qr['qr_path'] ?>">
															<input type="hidden" name="image_path" value="<?= $qr['image_path'] ?>">
															<input type="hidden" name="purpose" value="<?= $qr['purpose'] ?>">
															<button type="submit" 
																	class="btn btn-sm btn-outline-primary" 
																	title="Download PDF with QR">
																<i class="fas fa-file-pdf"></i>
															</button>
														</form>
													<?php endif; ?>
												</div>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="6" class="text-center py-4 text-muted">
											<i class="fas fa-qrcode fa-2x mb-3 d-block"></i>
											<p class="mb-0">No QR codes found</p>
											<a href="<?= base_url('qrcode/generate') ?>" class="btn btn-primary btn-sm mt-3">
												<i class="fas fa-plus me-2"></i>Generate Your First QR Code
											</a>
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
	/* Dark mode styles */
	[data-bs-theme="dark"] {
		--bs-body-bg: #1a1d21;
	}

	[data-bs-theme="dark"] .card {
		background-color: #212529;
		border-color: #2c3034;
		box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
	}

	[data-bs-theme="dark"] .card-header {
		border-bottom-color: #2c3034;
	}

	[data-bs-theme="dark"] .table {
		--bs-table-bg: #212529;
		--bs-table-hover-bg: #2c3034;
		color: var(--bs-body-color);
		border-color: #2c3034;
	}

	[data-bs-theme="dark"] .table thead th {
		border-bottom-color: #2c3034;
		color: #8f959e;
	}

	[data-bs-theme="dark"] .table tbody tr {
		border-color: #2c3034;
	}

	/* Button outlines in dark mode */
	[data-bs-theme="dark"] .btn-outline-primary,
	[data-bs-theme="dark"] .btn-outline-success,
	[data-bs-theme="dark"] .btn-outline-info {
		border-color: #2c3034;
		color: var(--bs-body-color);
	}

	[data-bs-theme="dark"] .btn-outline-primary:hover,
	[data-bs-theme="dark"] .btn-outline-success:hover,
	[data-bs-theme="dark"] .btn-outline-info:hover {
		background-color: #2c3034;
		border-color: #373b3e;
		color: var(--bs-body-color);
	}
</style>