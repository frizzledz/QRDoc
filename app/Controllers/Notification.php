<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class Notification extends BaseController
{
	protected $notificationModel;

	public function __construct()
	{
		if (!session()->get('logged_in')) {
			header('Location: ' . base_url('auth/login'));
			exit();
		}
		$this->notificationModel = new NotificationModel();
	}

	public function index()
	{
		$data = [
			'title' => 'Notifications',
			'notifications' => $this->notificationModel->where('user_id', session()->get('id'))
													 ->orderBy('created_at', 'DESC')
													 ->findAll()
		];
		return view('notification/index', $data);
	}

	public function markAsRead($id)
	{
		$notification = $this->notificationModel->find($id);
		
		if ($notification && $notification['user_id'] == session()->get('id')) {
			$this->notificationModel->markAsRead($id);
			
			// If it's an AJAX request, return JSON
			if ($this->request->isAJAX()) {
				return $this->response->setJSON(['success' => true]);
			}
			
			return redirect()->back()->with('success', 'Notification marked as read');
		}
		
		if ($this->request->isAJAX()) {
			return $this->response->setJSON(['success' => false]);
		}
		
		return redirect()->back()->with('error', 'Notification not found');
	}

	public function markAllAsRead()
	{
		$this->notificationModel->where('user_id', session()->get('id'))
							   ->set(['is_read' => 1])
							   ->update();
		
		if ($this->request->isAJAX()) {
			return $this->response->setJSON(['success' => true]);
		}
		
		return redirect()->back()->with('success', 'All notifications marked as read');
	}
}