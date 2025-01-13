<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserManagement extends BaseController
{
	protected $userModel;

	public function __construct()
	{
		$this->userModel = new UserModel();
		// Check if user is logged in and is admin
		if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
			header('Location: ' . base_url('dashboard'));
			exit();
		}
	}

	public function index()
	{
		$data = [
			'title' => 'User Management',
			'users' => $this->userModel->findAll()
		];
		return view('user_management/index', $data);
	}

	public function edit($id = null)
	{
		if ($id === null) {
			return redirect()->to('/user-management')->with('error', 'User not found');
		}

		$data = [
			'title' => 'Edit User',
			'user' => $this->userModel->find($id)
		];

		if (empty($data['user'])) {
			return redirect()->to('/user-management')->with('error', 'User not found');
		}

		return view('user_management/edit', $data);
	}

	public function update($id = null)
	{
		if ($id === null) {
			return redirect()->to('/user-management')->with('error', 'User not found');
		}

		$data = [
			'username' => $this->request->getPost('username'),
			'email' => $this->request->getPost('email')
		];

		if ($this->request->getPost('password')) {
			$data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
		}

		if ($this->userModel->update($id, $data)) {
			return redirect()->to('/user-management')->with('success', 'User updated successfully');
		}

		return redirect()->back()->withInput()->with('error', 'Failed to update user');
	}

	public function delete($id = null)
	{
		if ($id === null) {
			return redirect()->to('/user-management')->with('error', 'User not found');
		}

		if ($id == session()->get('id')) {
			return redirect()->to('/user-management')->with('error', 'Cannot delete your own account');
		}

		if ($this->userModel->delete($id)) {
			return redirect()->to('/user-management')->with('success', 'User deleted successfully');
		}

		return redirect()->to('/user-management')->with('error', 'Failed to delete user');
	}

	public function updateRole($id = null)
	{
		if ($id === null) {
			return redirect()->to('/user-management')->with('error', 'User not found');
		}

		$role = $this->request->getPost('role');
		if (!in_array($role, ['admin', 'user'])) {
			return redirect()->back()->with('error', 'Invalid role specified');
		}

		if ($this->userModel->update($id, ['role' => $role])) {
			return redirect()->back()->with('success', 'User role updated successfully');
		}

		return redirect()->back()->with('error', 'Failed to update user role');
	}

	public function verifyUser($id = null)
	{
		if ($id === null) {
			return redirect()->to('/user-management')->with('error', 'User not found');
		}

		if ($this->userModel->update($id, ['is_verified' => 1])) {
			return redirect()->back()->with('success', 'User verified successfully');
		}

		return redirect()->back()->with('error', 'Failed to verify user');
	}
}