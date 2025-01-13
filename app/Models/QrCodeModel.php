<?php

namespace App\Models;

use CodeIgniter\Model;

class QrCodeModel extends Model
{
	protected $table = 'qr_codes';
	protected $primaryKey = 'id';
	protected $allowedFields = ['user_id', 'purpose', 'image_path', 'qr_path', 'status', 'verified_by', 'verified_at', 'imbued_pdf_path'];
	
	protected $useTimestamps = true;
	protected $dateFormat = 'datetime';
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';

	public function getQrCodesWithUsers($status = null)
	{
		$builder = $this->db->table('qr_codes');
		$builder->select('qr_codes.*, users.username, verifier.username as verifier_name');
		$builder->join('users', 'users.id = qr_codes.user_id');
		$builder->join('users as verifier', 'verifier.id = qr_codes.verified_by', 'left');
		
		if ($status !== null) {
			$builder->where('qr_codes.status', $status);
		}
		
		$builder->orderBy('qr_codes.created_at', 'DESC');
		
		return $builder->get()->getResultArray();
	}

	public function generateQrCode($data)
	{
		$options = new QROptions([
			'eccLevel' => QRCode::ECC_L,
			'outputType' => QRCode::OUTPUT_IMAGE_PNG,
			'version' => 5,
			'scale' => 10,
		]);
		
		$qrcode = new QRCode($options);
		return $qrcode->render($data);
	}
}