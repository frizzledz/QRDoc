<?php

namespace App\Controllers;

class Profile extends BaseController
{
	protected $userModel;

	public function __construct()
	{
		$this->userModel = new \App\Models\UserModel();
	}

	public function index()
	{
		$data = [
			'title' => 'My Profile',
			'user' => $this->userModel->find(session()->get('id'))
		];

		return view('profile/index', $data);
	}

	public function settings()
	{
		$data = [
			'title' => 'Profile Settings',
			'user' => $this->userModel->find(session()->get('id'))
		];

		return view('profile/settings', $data);
	}

	public function password()
	{
		$data = [
			'title' => 'Change Password'
		];

		return view('profile/password', $data);
	}

	public function saveSettings()
	{
		// Handle settings update
		$rules = [
			'username' => 'required|min_length[3]',
			'email' => 'required|valid_email'
		];

		if (!$this->validate($rules)) {
			return redirect()->back()
				->with('error', $this->validator->getErrors())
				->withInput();
		}

		$data = [
			'username' => $this->request->getPost('username'),
			'email' => $this->request->getPost('email')
		];

		$this->userModel->update(session()->get('id'), $data);
		return redirect()->back()->with('success', 'Profile updated successfully');
	}

	public function changePassword()
	{
		$rules = [
			'current_password' => 'required',
			'new_password' => 'required|min_length[6]',
			'confirm_password' => 'required|matches[new_password]'
		];

		if (!$this->validate($rules)) {
			return redirect()->back()
				->with('error', $this->validator->getErrors())
				->withInput();
		}

		$user = $this->userModel->find(session()->get('id'));
		
		if (!password_verify($this->request->getPost('current_password'), $user['password'])) {
			return redirect()->back()
				->with('error', 'Current password is incorrect')
				->withInput();
		}

		$this->userModel->update(session()->get('id'), [
			'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT)
		]);

		return redirect()->back()->with('success', 'Password changed successfully');
	}
}