<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - QR Doc System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v6.3.0/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
</head>

<body>
    <?= $this->include('layout/nav') ?>

    <div class="container py-4">
        <!-- Welcome Section -->
        <div class="row mb-4 fade-in">
            <div class="col-12">
                <div class="card bg-gradient-primary text-white">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="rounded p-3 bg-white bg-opacity-25 me-3">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold">Welcome back, <?= session()->get('username') ?>!</h4>
                            <p class="mb-0 opacity-75">Here's what's happening with your QR codes today.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="row g-4 mb-4">
            <!-- Total QR Codes -->
            <div class="col-md-4 fade-in" style="animation-delay: 0.1s">
                <div class="card h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted fw-medium mb-2">Total QR Codes</p>
                                <h2 class="mb-2 fw-bold"><?= $totalQRCodes ?></h2>
                                <?php if (isset($totalGrowth)): ?>
                                    <p class="mb-0 <?= $totalGrowth >= 0 ? 'text-success' : 'text-danger' ?>">
                                        <i class="fas fa-arrow-<?= $totalGrowth >= 0 ? 'up' : 'down' ?> me-1"></i>
                                        <span class="fw-medium"><?= abs($totalGrowth) ?>%</span>
                                        <span class="text-muted ms-1">vs last month</span>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="rounded p-3" style="background: rgba(102, 126, 234, 0.1)">
                                <i class="fas fa-qrcode fa-lg text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verified QR Codes -->
            <div class="col-md-4 fade-in" style="animation-delay: 0.2s">
                <div class="card h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted fw-medium mb-2">Verified QR Codes</p>
                                <h2 class="mb-2 fw-bold"><?= $verifiedQRCodes ?></h2>
                                <?php if (isset($verifiedGrowth)): ?>
                                    <p class="mb-0 <?= $verifiedGrowth >= 0 ? 'text-success' : 'text-danger' ?>">
                                        <i class="fas fa-arrow-<?= $verifiedGrowth >= 0 ? 'up' : 'down' ?> me-1"></i>
                                        <span class="fw-medium"><?= abs($verifiedGrowth) ?>%</span>
                                        <span class="text-muted ms-1">vs last month</span>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="rounded p-3" style="background: rgba(16, 185, 129, 0.1)">
                                <i class="fas fa-check fa-lg text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending QR Codes -->
            <div class="col-md-4 fade-in" style="animation-delay: 0.3s">
                <div class="card h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted fw-medium mb-2">Pending QR Codes</p>
                                <h2 class="mb-2 fw-bold"><?= $pendingQRCodes ?></h2>
                                <?php if (isset($pendingGrowth)): ?>
                                    <p class="mb-0 <?= $pendingGrowth >= 0 ? 'text-success' : 'text-danger' ?>">
                                        <i class="fas fa-arrow-<?= $pendingGrowth >= 0 ? 'up' : 'down' ?> me-1"></i>
                                        <span class="fw-medium"><?= abs($pendingGrowth) ?>%</span>
                                        <span class="text-muted ms-1">vs last month</span>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="rounded p-3" style="background: rgba(245, 158, 11, 0.1)">
                                <i class="fas fa-clock fa-lg text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 fade-in" style="animation-delay: 0.4s">
                    <div class="card-header bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0 fw-bold">Recent Activity</h5>
                                <?php if (isset($debug_count)): ?>
                                    <?php /* Removing debug statement as code is working */ ?>
                                <?php endif; ?>
                                <p class="text-muted small mb-0">Your recent QR code activities</p>
                            </div>
                            <?php if (session()->get('role') === 'admin'): ?>
                                <a href="<?= base_url('qrcode/pending') ?>" class="btn btn-primary btn-sm">
                                    View All
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <?php if (session()->get('role') === 'admin'): ?>
                                            <th class="px-4 py-3">User</th>
                                        <?php endif; ?>
                                        <th class="px-4 py-3">Purpose</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Date</th>
                                        <th class="px-4 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recentActivities)): ?>
                                        <?php foreach ($recentActivities as $activity): ?>
                                            <tr>
                                                <?php if (session()->get('role') === 'admin'): ?>
                                                    <td class="px-4 py-3">
                                                        <?= isset($activity['username']) ? esc($activity['username']) : 'N/A' ?>
                                                    </td>
                                                <?php endif; ?>
                                                <td class="px-4 py-3"><?= esc($activity['purpose']) ?></td>
                                                <td class="px-4 py-3">
                                                    <?php if ($activity['status'] === 'verified'): ?>
                                                        <span class="badge bg-success">Verified</span>
                                                    <?php elseif ($activity['status'] === 'pending'): ?>
                                                        <span class="badge bg-warning">Pending</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Rejected</span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($activity['imbued_pdf_path'])): ?>
                                                        <span class="badge bg-info ms-1">Imbued</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <?= date('M d, Y h:i A', strtotime($activity['updated_at'])) ?>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="btn-group">
                                                        <?php if (!empty($activity['image_path'])): ?>
                                                            <a href="<?= base_url('uploads/' . $activity['image_path']) ?>"
                                                                class="btn btn-sm btn-outline-primary"
                                                                target="_blank"
                                                                title="View Original">
                                                                <i class="fas fa-image"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if (!empty($activity['imbued_pdf_path'])): ?>
                                                            <a href="<?= base_url('uploads/imbued_pdfs/' . $activity['imbued_pdf_path']) ?>"
                                                                class="btn btn-sm btn-outline-success"
                                                                target="_blank"
                                                                title="View Imbued PDF">
                                                                <i class="fas fa-file-pdf"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <a href="<?= base_url('qrcode/scan/' . $activity['qr_path']) ?>"
                                                            class="btn btn-sm btn-outline-info"
                                                            title="View Details">
                                                            <i class="fas fa-info-circle"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="<?= session()->get('role') === 'admin' ? '5' : '4' ?>" class="text-center py-4 text-muted">
                                                <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                                <p class="mb-0">No recent activities found</p>
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

        [data-bs-theme="dark"] .bg-light {
            background-color: #1a1d21 !important;
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

        [data-bs-theme="dark"] .bg-gradient-primary {
            background: linear-gradient(45deg, #2e3650, #0f1118) !important;
        }

        [data-bs-theme="dark"] .text-muted {
            color: #8f959e !important;
        }

        [data-bs-theme="dark"] thead.bg-light {
            background-color: #2c3034 !important;
        }

        /* Icon backgrounds in dark mode */
        [data-bs-theme="dark"] .rounded[style*="background"] {
            background: #2c3034 !important;
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>