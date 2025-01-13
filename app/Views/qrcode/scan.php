<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://use.fontawesome.com/releases/v6.3.0/css/all.css" rel="stylesheet">
	<script src="https://unpkg.com/html5-qrcode"></script>
	<script>
		function updateTheme() {
			document.body.setAttribute('data-bs-theme', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
		}
		updateTheme();
		window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', updateTheme);
	</script>
</head>
<body>
	<?= $this->include('layout/nav') ?>
	<div class="container mt-4">
		<div class="card border-0 shadow-sm">
			<div class="card-header bg-transparent border-bottom">
				<h5 class="mb-0"><i class="fas fa-qrcode"></i> Scan QR Code</h5>
			</div>
			<div class="card-body">
				<?php if (isset($error)): ?>
					<div class="alert alert-danger text-center">
						<i class="fas fa-exclamation-circle"></i> <?= $error ?>
					</div>
				<?php endif; ?>
				
				<div class="row justify-content-center">
					<div class="col-md-8 col-lg-6">
						<div id="reader" class="qr-scanner-container"></div>
						<div id="result" class="mt-3 text-center"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://unpkg.com/html5-qrcode"></script>

	<style>
	.qr-scanner-container {
		border-radius: 10px;
		overflow: hidden;
		box-shadow: 0 0 20px rgba(0,0,0,0.1);
	}

	#reader {
		width: 100%;
		max-width: 500px;
		margin: 0 auto;
	}

	#reader video {
		width: 100% !important;
		border-radius: 10px;
	}

	/* Dark mode styles */
	[data-bs-theme="dark"] .card {
		background-color: #2b2b2b;
	}

	[data-bs-theme="dark"] .qr-scanner-container {
		box-shadow: 0 0 20px rgba(0,0,0,0.3);
	}

	[data-bs-theme="dark"] .alert-danger {
		background-color: #442326;
		border-color: #842029;
		color: #ea868f;
	}

	/* Improved button styles */
	#reader button {
		background-color: var(--bs-primary);
		color: white;
		border: none;
		padding: 8px 16px;
		border-radius: 6px;
		margin: 5px;
		transition: all 0.3s ease;
	}

	#reader button:hover {
		opacity: 0.9;
	}

	#reader select {
		padding: 8px;
		border-radius: 6px;
		border: 1px solid var(--bs-border-color);
		margin: 5px;
		background-color: var(--bs-body-bg);
		color: var(--bs-body-color);
	}

	.alert {
		margin-bottom: 20px;
		padding: 15px;
		border-radius: 8px;
	}

	.alert-danger {
		background-color: #fff2f2;
		border-color: #ffcdd2;
		color: #d32f2f;
	}

	.alert i {
		margin-right: 8px;
	}
	</style>

	<script>
	function onScanSuccess(decodedText, decodedResult) {
		html5QrcodeScanner.clear();
		
		// Encode the scanned text for URL safety
		const encodedText = encodeURIComponent(decodedText);
		
		// Parse the JSON to get the file_name
		try {
			const qrData = JSON.parse(decodedText);
			if (qrData.file_name) {
				window.location.href = '<?= base_url('qrcode/scan/') ?>' + qrData.file_name;
			} else {
				window.location.href = '<?= base_url('qrcode/scan/') ?>' + encodedText;
			}
		} catch (e) {
			// If JSON parsing fails, use the encoded full text
			window.location.href = '<?= base_url('qrcode/scan/') ?>' + encodedText;
		}
	}

	function onScanFailure(error) {
		// Silent failure
	}

	let html5QrcodeScanner = new Html5QrcodeScanner(
		"reader", 
		{ 
			fps: 10,
			qrbox: { width: 300, height: 300 },
			aspectRatio: 1.0,
			formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ]
		}
	);
	html5QrcodeScanner.render(onScanSuccess, onScanFailure);
	</script>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>