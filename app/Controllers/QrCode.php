<?php

namespace App\Controllers;

require_once ROOTPATH . 'vendor/autoload.php';

use chillerlan\QRCode\{QRCode as QRGenerator, QROptions};

class QrCode extends BaseController
{
    protected $qrCodeModel;
    protected $userModel;

    public function __construct()
    {
        $this->qrCodeModel = new \App\Models\QrCodeModel();
        $this->userModel = new \App\Models\UserModel();
    }

    public function index()
    {
        $userId = session()->get('id');

        $db = \Config\Database::connect();
        $builder = $db->table('qr_codes');
        $builder->select('qr_codes.*, users.username');
        $builder->join('users', 'users.id = qr_codes.user_id');
        $builder->where('qr_codes.user_id', $userId);
        $builder->orderBy('qr_codes.created_at', 'DESC');

        $data = [
            'title' => 'My QR Codes',
            'qrCodes' => $builder->get()->getResultArray()
        ];

        return view('qrcode/index', $data);
    }

    public function generate()
    {
        $data = [
            'title' => 'Generate QR Code'
        ];

        return view('qrcode/generate', $data);
    }

    public function save()
    {
        // Validate input
        if (!$this->validate([
            'purpose' => 'required|min_length[3]',
            'image' => [
                'uploaded[image]',
                'mime_in[image,image/jpg,image/jpeg,image/png,image/gif]',
                'max_size[image,4096]',
            ]
        ])) {
            return redirect()->back()
                ->with('error', $this->validator->getErrors())
                ->withInput();
        }

        // Handle file upload
        $file = $this->request->getFile('image');
        if (!$file->isValid()) {
            return redirect()->back()
                ->with('error', 'Failed to upload file')
                ->withInput();
        }

        // Generate unique names for files
        $imageName = $file->getRandomName();
        $qrName = 'qr_' . time() . '_' . rand(1000, 9999) . '.png';

        // Move uploaded file
        $file->move(ROOTPATH . 'public/uploads', $imageName);

        // Generate QR Code
        try {
            // Create data for QR code (local file path)
            $qrData = [
                'purpose' => $this->request->getPost('purpose'),
                'file_name' => $imageName,
                'timestamp' => date('Y-m-d H:i:s')
            ];

            // Convert data to JSON
            $qrContent = json_encode($qrData);

            // QR Code options
            $options = new QROptions([
                'version'    => 10,
                'outputType' => 'png',
                'eccLevel'   => QRGenerator::ECC_M,
                'scale'      => 5,
                'imageBase64' => false,
                'addQuietzone' => true,
                'quietzoneSize' => 2
            ]);

            // Create QR Code
            $qrcode = new QRGenerator($options);

            // Generate and save QR code
            $qrcode->render($qrContent, ROOTPATH . 'public/uploads/' . $qrName);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate QR code: ' . $e->getMessage())
                ->withInput();
        }

        // Save to database
        $dbData = [
            'purpose' => $this->request->getPost('purpose'),
            'image_path' => $imageName,
            'qr_path' => $qrName,
            'user_id' => session()->get('id'),
            'status' => 'pending'
        ];

        if (!$this->qrCodeModel->insert($dbData)) {
            return redirect()->back()
                ->with('error', 'Failed to save QR code')
                ->withInput();
        }

        return redirect()->to('qrcode')
            ->with('success', 'QR Code generated successfully');
    }

    public function myQrCodes()
    {
        $data = [
            'title' => 'My QR Codes',
            'qrcodes' => $this->qrCodeModel->where('user_id', session()->get('id'))
                ->orderBy('created_at', 'DESC')
                ->findAll()
        ];

        return view('qrcode/my_qrcodes', $data);
    }

    public function pending()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('dashboard');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('qr_codes');
        $builder->select('qr_codes.*, users.username');
        $builder->join('users', 'users.id = qr_codes.user_id');
        $builder->where('qr_codes.status', 'pending');
        $builder->orderBy('qr_codes.created_at', 'DESC');

        $data = [
            'title' => 'Pending QR Codes',
            'pendingQrCodes' => $builder->get()->getResultArray()
        ];

        return view('qrcode/pending', $data);
    }

    public function verify($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('dashboard');
        }

        $this->qrCodeModel->update($id, [
            'status' => 'verified',
            'verified_by' => session()->get('id'),
            'verified_at' => date('Y-m-d H:i:s')
        ]);
        
        return redirect()->back()->with('success', 'QR Code verified successfully');
    }

    public function reject($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('dashboard');
        }

        $this->qrCodeModel->update($id, [
            'status' => 'rejected',
            'rejected_by' => session()->get('id'),
            'rejected_at' => date('Y-m-d H:i:s')
        ]);
        
        return redirect()->back()->with('success', 'QR Code rejected successfully');
    }

    public function scan($qr_path = null)
    {
        if ($qr_path) {
            try {
                // Get QR code details from database
                $db = \Config\Database::connect();
                $builder = $db->table('qr_codes');
                $builder->select('qr_codes.*, users.username as creator_name, verifier.username as verifier_name');
                $builder->join('users', 'users.id = qr_codes.user_id');
                $builder->join('users as verifier', 'verifier.id = qr_codes.verified_by', 'left');

                // Try both the full path and just the filename
                $builder->groupStart()
                    ->where('qr_codes.qr_path', $qr_path)
                    ->orWhere('qr_codes.image_path', $qr_path)
                    ->groupEnd();

                $qrDetails = $builder->get()->getRowArray();

                if ($qrDetails) {
                    // Format dates for display
                    $qrDetails['created_at'] = date('F j, Y g:i A', strtotime($qrDetails['created_at']));
                    if ($qrDetails['verified_at']) {
                        $qrDetails['verified_at'] = date('F j, Y g:i A', strtotime($qrDetails['verified_at']));
                    }

                    $data = [
                        'title' => 'Scanned QR Code Result',
                        'qrDetails' => $qrDetails
                    ];
                    return view('qrcode/scan_result', $data);
                } else {
                    $data = [
                        'title' => 'Scanned QR Code Result',
                        'error' => 'QR code not found in database'
                    ];
                    return view('qrcode/scan_result', $data);
                }
            } catch (\Exception $e) {
                log_message('error', 'QR Code scan error: ' . $e->getMessage());
                $data = [
                    'title' => 'Scanned QR Code Result',
                    'error' => 'Failed to read QR code: ' . $e->getMessage()
                ];
                return view('qrcode/scan_result', $data);
            }
        }

        // If no QR path, show scanner
        $data = [
            'title' => 'Scan QR Code'
        ];
        return view('qrcode/scan', $data);
    }

    public function embedPdf()
    {
        // Create new PDF document
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('QR Doc System');
        $pdf->SetTitle('Document with QR Code');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Add a page
        $pdf->AddPage();

        try {
            // Get the QR code path from POST
            $qrPath = $this->request->getPost('qr_path');
            $purpose = $this->request->getPost('purpose');
            $originalImage = $this->request->getPost('image_path');

            if (!$qrPath || !$originalImage) {
                throw new \Exception('Required files not found');
            }

            // Add title
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->Cell(0, 15, 'Verified Document with QR Code', 0, 1, 'C');

            // Add purpose
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 10, 'Purpose: ' . $purpose, 0, 1, 'L');

            // Add timestamp
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 10, 'Generated on: ' . date('Y-m-d H:i:s'), 0, 1, 'L');

            // Add original document
            $imagePath = ROOTPATH . 'public/uploads/' . $originalImage;
            $pdf->Image($imagePath, 15, 60, 180);

            // Add QR code
            $qrCodePath = ROOTPATH . 'public/uploads/' . $qrPath;
            $pdf->Image($qrCodePath, 15, 15, 30);

            // Output PDF
            $pdfName = 'document_' . time() . '.pdf';
            $pdfPath = ROOTPATH . 'public/uploads/imbued_pdfs/' . $pdfName;
            $pdf->Output($pdfPath, 'F');

            // Update database with imbued PDF path
            $db = \Config\Database::connect();
            $builder = $db->table('qr_codes');
            $builder->where('qr_path', $qrPath);
            $builder->update(['imbued_pdf_path' => $pdfName]);

            // Redirect to dashboard with success message
            return redirect()->to('dashboard')
                ->with('success', 'PDF has been generated and saved successfully. You can view it in the QR code details.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    public function imbue()
    {
        $userId = session()->get('id');

        $db = \Config\Database::connect();
        $builder = $db->table('qr_codes');
        $builder->select('qr_codes.*, users.username');
        $builder->join('users', 'users.id = qr_codes.user_id');
        $builder->where('qr_codes.user_id', $userId);
        $builder->where('qr_codes.status', 'verified');
        $builder->orderBy('qr_codes.created_at', 'DESC');

        $data = [
            'title' => 'Imbue PDF with QR Code',
            'qrCodes' => $builder->get()->getResultArray()
        ];

        return view('qrcode/imbue', $data);
    }

    public function embedCustomPdf()
    {
        try {
            // Validate file upload
            $validationRule = [
                'pdf_file' => [
                    'label' => 'PDF File',
                    'rules' => 'uploaded[pdf_file]|mime_in[pdf_file,application/pdf]|max_size[pdf_file,5120]'
                ],
                'page_number' => [
                    'label' => 'Page Number',
                    'rules' => 'required|greater_than[0]'
                ],
                'position_x' => [
                    'label' => 'Position X',
                    'rules' => 'required|greater_than_equal_to[0]|less_than_equal_to[210]'
                ],
                'position_y' => [
                    'label' => 'Position Y',
                    'rules' => 'required|greater_than_equal_to[0]|less_than_equal_to[297]'
                ],
                'qr_size' => [
                    'label' => 'QR Size',
                    'rules' => 'required|greater_than_equal_to[10]|less_than_equal_to[100]'
                ]
            ];

            if (!$this->validate($validationRule)) {
                return redirect()->back()
                    ->with('error', $this->validator->listErrors());
            }

            // Get form data
            $file = $this->request->getFile('pdf_file');
            $qrPath = $this->request->getPost('qr_path');
            $pageNumber = (int)$this->request->getPost('page_number');
            $positionX = (float)$this->request->getPost('position_x');
            $positionY = (float)$this->request->getPost('position_y');
            $qrSize = (float)$this->request->getPost('qr_size');
            $showText = (bool)$this->request->getPost('show_text');

            if (!$file->isValid() || !$qrPath) {
                throw new \Exception('Invalid file or QR code');
            }

            // Create new PDF document using FPDI
            $pdf = new \setasign\Fpdi\Tcpdf\Fpdi(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // Remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // Import pages from uploaded PDF
            $pageCount = $pdf->setSourceFile($file->getTempName());

            // Validate page number
            if ($pageNumber > $pageCount) {
                throw new \Exception("Selected page number ($pageNumber) exceeds document length ($pageCount)");
            }

            // Import all pages
            for ($i = 1; $i <= $pageCount; $i++) {
                $tplIdx = $pdf->importPage($i);
                $pdf->AddPage();
                $pdf->useTemplate($tplIdx);

                // Add QR code to specified page
                if ($i === $pageNumber) {
                    $qrCodePath = ROOTPATH . 'public/uploads/' . $qrPath;
                    $pdf->Image($qrCodePath, $positionX, $positionY, $qrSize);

                    // Add verification text if enabled
                    if ($showText) {
                        $pdf->SetFont('helvetica', '', 8);
                        $pdf->SetXY($positionX, $positionY + $qrSize + 2);
                        $pdf->Cell($qrSize, 5, 'Verified Document', 0, 1, 'C');
                        $pdf->SetXY($positionX, $positionY + $qrSize + 7);
                        $pdf->Cell($qrSize, 5, date('Y-m-d H:i:s'), 0, 1, 'C');
                    }
                }
            }

            // Output PDF
            $pdfName = 'verified_' . time() . '.pdf';
            $pdfPath = ROOTPATH . 'public/uploads/imbued_pdfs/' . $pdfName;
            $pdf->Output($pdfPath, 'F');

            // Update database with imbued PDF path
            $db = \Config\Database::connect();
            $builder = $db->table('qr_codes');
            $builder->where('qr_path', $qrPath);
            $builder->update(['imbued_pdf_path' => $pdfName]);

            // Redirect to dashboard with success message
            return redirect()->to('dashboard')
                ->with('success', 'PDF has been generated and saved successfully. You can view it in the QR code details.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    // ... other methods ...
}
