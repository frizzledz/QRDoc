<?= $this->include('layout/head') ?>
    <style>
        #canvas-container {
            position: relative;
            width: max-content;
            height: max-content;
            padding: 0px;
            margin: 20px auto;
        }

        .draggable {
            display: none;
            width: 25px;
            height: 25px;
            background-color: rgba(13, 110, 253, 0.15);
            border: 1px dashed #0d6efd;
            touch-action: none;
            user-select: none;
            text-align: center;
            padding: 5px 0;
            color: #0d6efd;
            position: absolute;
            cursor: move;
            z-index: 1000;
            transition: all 0.2s ease-out;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
            transform: translate(0px, 0px);
            will-change: transform;
            -webkit-transform: translate(0px, 0px);
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            perspective: 1000;
            -webkit-perspective: 1000;
        }

        .draggable:hover,
        .draggable:active {
            background-color: rgba(13, 110, 253, 0.25);
            box-shadow: 0 0 0 6px rgba(13, 110, 253, 0.15);
        }

        .draggable i {
            font-size: 15px;
            line-height: 15px;
            transition: all 0.2s ease-out;
        }

        .draggable:active i {
            transform: scale(0.9);
        }

        #pdf-canvas {
            max-width: 100%;
            height: auto;
        }

        /* If icon needs to be contained within a wrapper */
        .qr-icon-wrapper {
            width: 256px;
            height: 256px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    </style>
</head>

<body class="">
    <?= $this->include('layout/nav') ?>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="mb-4">Imbue PDF with QR Code</h4>

                        <form action="<?= base_url('qrcode/embedCustomPdf') ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger">
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php endif; ?>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Select QR Code</label>
                                    <select name="qr_path" class="form-select" required>
                                        <option value="">Choose a verified QR code...</option>
                                        <?php foreach ($qrCodes as $qr): ?>
                                            <option value="<?= $qr['qr_path'] ?>">
                                                <?= esc($qr['purpose']) ?> (<?= date('Y-m-d', strtotime($qr['created_at'])) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Upload PDF</label>
                                    <input type="file"
                                        class="form-control"
                                        name="pdf_file"
                                        id="document-result"
                                        accept=".pdf"
                                        required>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Page Number</label>
                                    <input type="number"
                                        class="form-control"
                                        name="page_number"
                                        value="1"
                                        min="1"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">QR Code Size (px)</label>
                                    <input type="number"
                                        class="form-control"
                                        name="qr_size"
                                        value="25"
                                        min="15"
                                        max="50"
                                        required>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Position X</label>
                                    <input type="number"
                                        class="form-control"
                                        id="positionX"
                                        readonly
                                        value="0">
                                    <input type="hidden"
                                        name="position_x"
                                        id="hiddenX"
                                        value="0">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Position Y</label>
                                    <input type="number"
                                        class="form-control"
                                        id="positionY"
                                        readonly
                                        value="0">
                                    <input type="hidden"
                                        name="position_y"
                                        id="hiddenY"
                                        value="0">
                                </div>
                            </div>

                            <div id="canvas-container">
                                <div class="draggable">
                                    <i class="fas fa-qrcode"></i>
                                </div>
                                <canvas id="pdf-canvas" class="border-2">
                                    Loading PDF...
                                </canvas>
                            </div>

                            <!-- Hidden inputs for coordinates -->
                            <input type="hidden" id="stampX" name="stampX" value="0" required>
                            <input type="hidden" id="stampY" name="stampY" value="0" required>
                            <input type="hidden" id="canvasHeight" name="canvasHeight" value="0" required>
                            <input type="hidden" id="canvasWidth" name="canvasWidth" value="0" required>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-file-pdf me-2"></i>Generate PDF
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.269/pdf.min.mjs" type="module"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.269/pdf.worker.min.mjs" type="module"></script>

    <!-- Interact.js -->
    <script src="https://cdn.jsdelivr.net/npm/interactjs@1.10.20/dist/interact.min.js"></script>

    <script>
        // Initialize position tracking
        let position = {
            x: 0,
            y: 0
        };

        // Maximum allowed positions (A4 dimensions in mm)
        const MAX_X = 210;
        const MAX_Y = 297;

        // Function to validate position
        function validatePosition(x, y) {
            return x >= 0 && x <= MAX_X && y >= 0 && y <= MAX_Y;
        }

        // Function to update all position fields with validation
        function updatePosition(x, y) {
            const roundedX = Math.round(x);
            const roundedY = Math.round(y);

            // Scale positions to mm (assuming canvas represents A4)
            const canvas = document.getElementById('pdf-canvas');
            const scaleX = MAX_X / canvas.width;
            const scaleY = MAX_Y / canvas.height;

            const posX = Math.round(roundedX * scaleX);
            const posY = Math.round(roundedY * scaleY);

            if (validatePosition(posX, posY)) {
                // Update display fields
                document.getElementById('positionX').value = posX;
                document.getElementById('positionY').value = posY;

                // Update hidden fields
                document.getElementById('hiddenX').value = posX;
                document.getElementById('hiddenY').value = posY;

                // Update original stamp fields
                document.getElementById('stampX').value = posX;
                document.getElementById('stampY').value = posY;

                // Update position object
                position = {
                    x: roundedX,
                    y: roundedY
                };
                return true;
            }
            return false;
        }

        // Function to update coordinates from draggable position
        function updateCoordinates() {
            const draggable = document.querySelector('.draggable');
            const canvas = document.getElementById('pdf-canvas');

            if (!draggable || !canvas) return false;

            const rect = draggable.getBoundingClientRect();
            const canvasRect = canvas.getBoundingClientRect();

            // Calculate relative positions
            const relativeX = Math.round(rect.left - canvasRect.left);
            const relativeY = Math.round(rect.top - canvasRect.top);

            // Update all position fields with validation
            return updatePosition(relativeX, relativeY);
        }

        // PDF handling
        document.querySelector("#document-result").addEventListener("change", async function(e) {
            var file = e.target.files[0]
            if (file.type != "application/pdf") {
                alert(file.name + " is not a pdf file.")
                return
            }

            var fileReader = new FileReader();

            fileReader.onload = async function() {
                var typedarray = new Uint8Array(this.result);

                const loadingTask = pdfjsLib.getDocument(typedarray);
                loadingTask.promise.then(pdf => {
                    pdf.getPage(1).then(function(page) {
                        var scale = 1.5;
                        var viewport = page.getViewport({
                            scale: scale
                        });
                        var canvas = document.getElementById('pdf-canvas');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        document.getElementById('canvasHeight').value = viewport.height;
                        document.getElementById('canvasWidth').value = viewport.width;

                        page.render({
                            canvasContext: canvas.getContext('2d'),
                            viewport: viewport
                        });

                        canvas.classList.remove('border-2');
                    });
                });
            };

            fileReader.readAsArrayBuffer(file);

            // Reset position and show draggable
            position = {
                x: 0,
                y: 0
            };
            const draggable = document.querySelector('.draggable');
            draggable.style.display = 'block';
            draggable.style.transform = 'translate(0px, 0px)';
            updateCoordinates();
        });

        // Draggable QR code implementation
        interact('.draggable').draggable({
            listeners: {
                start(event) {
                    if (!position) position = {
                        x: 0,
                        y: 0
                    };
                    const draggable = event.target;
                    const size = parseInt(draggable.style.width);
                    const shadowSize = Math.max(6, Math.round(size * 0.2));
                    draggable.style.boxShadow = `0 0 0 ${shadowSize}px rgba(13, 110, 253, 0.15)`;
                },
                move(event) {
                    position.x += event.dx;
                    position.y += event.dy;

                    // Direct DOM manipulation for better performance
                    event.target.style.transform = `translate(${position.x}px, ${position.y}px)`;

                    // Update coordinates without rounding for smoother movement
                    document.getElementById('stampX').value = position.x;
                    document.getElementById('stampY').value = position.y;
                },
                end(event) {
                    // Round only at the end of movement
                    const draggable = event.target;
                    const size = parseInt(draggable.style.width);
                    const shadowSize = Math.max(4, Math.round(size * 0.15));
                    draggable.style.boxShadow = `0 0 0 ${shadowSize}px rgba(13, 110, 253, 0.1)`;

                    document.getElementById('stampX').value = Math.round(position.x);
                    document.getElementById('stampY').value = Math.round(position.y);
                    updateCoordinates();
                }
            },
            // Remove inertia for direct response
            inertia: false,
            autoScroll: false, // Disable auto-scrolling
            modifiers: [
                interact.modifiers.restrictRect({
                    restriction: 'parent',
                    endOnly: false // Apply restriction during movement
                })
            ],
        });


        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const stampX = parseInt(document.getElementById('stampX').value);
            const stampY = parseInt(document.getElementById('stampY').value);

            if (!stampX || !stampY || !validatePosition(stampX, stampY)) {
                e.preventDefault();
                alert('Please position the QR code within valid bounds (X ≤ 210, Y ≤ 297)');
                return false;
            }
        });


        // Add this JavaScript code to handle QR code size synchronization
        document.addEventListener('DOMContentLoaded', function() {
            const qrSizeInput = document.querySelector('input[name="qr_size"]');
            const draggable = document.querySelector('.draggable');
            const icon = draggable.querySelector('i');

            // Initial size setup
            const initialSize = parseInt(qrSizeInput.value);
            draggable.style.width = initialSize + 'px';
            draggable.style.height = initialSize + 'px';
            draggable.style.padding = (initialSize / 5) + 'px 0';
            icon.style.fontSize = (initialSize * 0.6) + 'px';
            icon.style.lineHeight = (initialSize * 0.6) + 'px';

            // Listen for size changes
            qrSizeInput.addEventListener('input', function() {
                const size = parseInt(this.value);
                draggable.style.width = size + 'px';
                draggable.style.height = size + 'px';
                draggable.style.padding = (size / 5) + 'px 0';
                icon.style.fontSize = (size * 0.6) + 'px';
                icon.style.lineHeight = (size * 0.6) + 'px';
            });
        });

        // Initialize coordinates when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure stampX and stampY have initial values
            if (!document.getElementById('stampX').value) {
                document.getElementById('stampX').value = "0";
            }
            if (!document.getElementById('stampY').value) {
                document.getElementById('stampY').value = "0";
            }
        });
    </script>

</body>

</html>