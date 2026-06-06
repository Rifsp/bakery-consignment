<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if ($this->isLoggedIn()) {
            return redirect()->to('dashboard');
        }

        return view('auth/login');
    }

    public function doLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Username atau password salah');
        }

        if (!$user['status_aktif']) {
            return redirect()->back()->with('error', 'Akun tidak aktif');
        }

        $this->session->set([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'nama_lengkap' => $user['nama_lengkap'],
            'role' => $user['role'],
            'logged_in' => true,
        ]);

        return redirect()->to('dashboard');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('login');
    }
}
