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
                                    <th class="px-4 py-3 border-bottom">Image</th>
                                    <th class="px-4 py-3 border-bottom">Status</th>
                                    <th class="px-4 py-3 border-bottom">Created At</th>
                                    <th class="px-4 py-3 border-bottom">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($qrcodes)): ?>
                                    <?php foreach ($qrcodes as $qr): ?>
                                        <tr>
                                            <td class="px-4 py-3"><?= esc($qr['purpose']) ?></td>
                                            <td class="px-4 py-3">
                                                <img src="<?= base_url('uploads/' . $qr['image_path']) ?>" 
                                                     alt="QR Code" 
                                                     class="rounded"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            </td>
                                            <td class="px-4 py-3">
                                                <?php
                                                $statusClass = [
                                                    'pending' => 'warning',
                                                    'verified' => 'success',
                                                    'rejected' => 'danger'
                                                ];
                                                ?>
                                                <span class="badge bg-<?= $statusClass[$qr['status']] ?>">
                                                    <?= ucfirst($qr['status']) ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <?= date('M d, Y h:i A', strtotime($qr['created_at'])) ?>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="btn-group">
                                                    <a href="<?= base_url('uploads/' . $qr['image_path']) ?>" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       target="_blank"
                                                       title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?= base_url('uploads/' . $qr['image_path']) ?>" 
                                                       class="btn btn-sm btn-outline-success" 
                                                       download
                                                       title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-qrcode fa-2x mb-3 d-block"></i>
                                            <p class="mb-0">No QR codes found</p>
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

    [data-bs-theme="dark"] thead.bg-light {
        background-color: #2c3034 !important;
    }

    /* Button outlines in dark mode */
    [data-bs-theme="dark"] .btn-outline-primary,
    [data-bs-theme="dark"] .btn-outline-success {
        border-color: #2c3034;
        color: var(--bs-body-color);
    }

    [data-bs-theme="dark"] .btn-outline-primary:hover,
    [data-bs-theme="dark"] .btn-outline-success:hover {
        background-color: #2c3034;
        border-color: #373b3e;
        color: var(--bs-body-color);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 