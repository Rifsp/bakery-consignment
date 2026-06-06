<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $data = [
            'user' => $this->getUser(),
            'users' => $this->userModel->orderBy('nama_lengkap', 'ASC')->findAll(),
        ];

        return view('users/index', $data);
    }

    public function create()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $data = ['user' => $this->getUser()];
        return view('users/form', $data);
    }

    public function store()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $data = [
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'role' => $this->request->getPost('role'),
            'status_aktif' => $this->request->getPost('status_aktif') ? true : false,
        ];

        if ($this->userModel->insert($data)) {
            return redirect()->to('users')->with('success', 'User berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
    }

    public function edit($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $userData = $this->userModel->find($id);
        if (!$userData) {
            return redirect()->to('users')->with('error', 'User tidak ditemukan');
        }

        $data = [
            'user' => $this->getUser(),
            'userData' => $userData,
        ];

        return view('users/form', $data);
    }

    public function update($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $data = [
            'username' => $this->request->getPost('username'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'role' => $this->request->getPost('role'),
            'status_aktif' => $this->request->getPost('status_aktif') ? true : false,
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($this->userModel->update($id, $data)) {
            return redirect()->to('users')->with('success', 'User berhasil diupdate');
        }

        return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
    }

    public function delete($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        if ($id == $this->getUser()['id']) {
            return redirect()->to('users')->with('error', 'Tidak bisa menghapus akun sendiri');
        }

        $this->userModel->delete($id);
        return redirect()->to('users')->with('success', 'User berhasil dihapus');
    }
}
