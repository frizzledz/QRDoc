<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('dashboard');
        }
        
        return view('auth/login');
    }

    public function attemptLogin()
    {
        $userModel = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        $user = $userModel->where('username', $username)->first();
        
        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'isLoggedIn' => true,
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ]);
            
            return redirect()->to('dashboard');
        }
        
        return redirect()->back()
            ->with('error', 'Invalid username or password')
            ->withInput();
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login')->with('success', 'You have been successfully logged out.');
    }

    public function register()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('dashboard');
        }
        
        return view('auth/register');
    }

    public function attemptRegister()
    {
        $userModel = new UserModel();
        
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('error', $this->validator->getErrors())
                ->withInput();
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'user'  // Default role
        ];

        $userModel->insert($data);

        return redirect()->to('login')
            ->with('success', 'Registration successful! Please login.');
    }
}
