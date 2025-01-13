<?= $this->include('layout/head') ?>
<?= $this->include('layout/nav') ?> 
<body>
    <?= $this->include('layout/header') ?>
    <div class="container-fluid p-0">
        <!-- Header with gradient -->
        <div class="p-4 mb-3" style="background: linear-gradient(90deg, #6f75e5 0%, #8045e5 100%);">
            <h4 class="text-white mb-2"><i class="bi bi-qr-code me-2"></i>QR Code Details</h4>
            <p class="text-white-50 mb-0">Scan result information</p>
        </div>

        <!-- Content -->
        <div class="container py-3">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error ?>
                </div>
            <?php elseif (isset($qrDetails)): ?>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="text-muted" style="width: 140px">Purpose</th>
                                        <td class="fw-medium"><?= $qrDetails['purpose'] ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Status</th>
                                        <td>
                                            <?php if ($qrDetails['status'] == 'verified'): ?>
                                                <span class="badge bg-success-subtle text-success px-3 py-2">
                                                    <i class="bi bi-check-circle-fill me-1"></i>Verified
                                                </span>
                                            <?php elseif ($qrDetails['status'] == 'rejected'): ?>
                                                <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                                    <i class="bi bi-x-circle-fill me-1"></i>Rejected
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning-subtle text-warning px-3 py-2">
                                                    <i class="bi bi-clock-fill me-1"></i>Pending
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Created By</th>
                                        <td class="fw-medium"><?= $qrDetails['creator_name'] ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Created At</th>
                                        <td><?= $qrDetails['created_at'] ?></td>
                                    </tr>
                                    <?php if ($qrDetails['status'] == 'verified'): ?>
                                        <tr>
                                            <th class="text-muted">Verified By</th>
                                            <td class="fw-medium"><?= $qrDetails['verifier_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Verified At</th>
                                            <td><?= $qrDetails['verified_at'] ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <?php if ($qrDetails['status'] == 'verified' && isset($qrDetails['imbued_pdf_path'])): ?>
                                <a href="<?= base_url('uploads/imbued_pdfs/' . $qrDetails['imbued_pdf_path']) ?>"
                                    class="btn btn-primary">
                                    <i class="bi bi-file-pdf me-2"></i>View Verified Document
                                </a>
                            <?php endif; ?>
                            <a href="<?= base_url('qrcode/scan') ?>"
                                class="btn btn-outline-primary">
                                <i class="bi bi-qr-code-scan me-2"></i>Scan Another QR Code
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add Bootstrap Icons CSS in your header -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        .table th,
        .table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, .05);
        }

        .table tr:last-child th,
        .table tr:last-child td {
            border-bottom: none;
        }

        .badge {
            font-weight: 500;
        }

        .btn {
            padding: 0.6rem 1.2rem;
            font-weight: 500;
        }
    </style>
</body>