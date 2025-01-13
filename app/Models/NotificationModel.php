<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
	protected $table = 'notifications';
	protected $primaryKey = 'id';
	protected $allowedFields = ['user_id', 'title', 'message', 'type', 'is_read'];
	
	protected $useTimestamps = true;
	protected $dateFormat = 'datetime';
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';

	public function getUnreadCount($userId)
	{
		return $this->where('user_id', $userId)
					->where('is_read', 0)
					->countAllResults();
	}

	public function markAsRead($id)
	{
		return $this->update($id, ['is_read' => 1]);
	}

	public function createNotification($userId, $title, $message, $type = 'general')
	{
		return $this->insert([
			'user_id' => $userId,
			'title' => $title,
			'message' => $message,
			'type' => $type,
			'is_read' => 0
		]);
	}
}