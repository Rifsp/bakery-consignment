<?php

namespace App\Controllers;

use App\Models\SalesModel;
use App\Models\UserModel;

class Sales extends BaseController
{
    protected $salesModel;
    protected $userModel;

    public function __construct()
    {
        $this->salesModel = new SalesModel();
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
            'sales' => $this->salesModel
                ->select('sales.*, users.username')
                ->join('users', 'users.id = sales.id_user', 'left')
                ->orderBy('sales.nama_sales', 'ASC')
                ->findAll(),
        ];

        return view('sales/index', $data);
    }

    public function create()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $data = [
            'user' => $this->getUser(),
            'users' => $this->userModel->where('role', 'sales')->where('status_aktif', true)->findAll(),
        ];

        return view('sales/form', $data);
    }

    public function store()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $data = [
            'kode_sales' => $this->generateKodeMaster('sales', 'kode_sales', 'SLS'),
            'id_user' => $this->request->getPost('id_user') ?: null,
            'nama_sales' => $this->request->getPost('nama_sales'),
            'no_telepon' => $this->request->getPost('no_telepon'),
            'alamat' => $this->request->getPost('alamat'),
            'status_aktif' => $this->request->getPost('status_aktif') ? true : false,
        ];

        if ($this->salesModel->insert($data)) {
            return redirect()->to('sales')->with('success', 'Sales berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('errors', $this->salesModel->errors());
    }

    public function edit($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $sales = $this->salesModel->find($id);
        if (!$sales) {
            return redirect()->to('sales')->with('error', 'Sales tidak ditemukan');
        }

        $data = [
            'user' => $this->getUser(),
            'sales' => $sales,
            'users' => $this->userModel->where('role', 'sales')->where('status_aktif', true)->findAll(),
        ];

        return view('sales/form', $data);
    }

    public function update($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $sales = $this->salesModel->find($id);
        if (!$sales) {
            return redirect()->to('sales')->with('error', 'Sales tidak ditemukan');
        }

        $data = [
            'id_user' => $this->request->getPost('id_user') ?: null,
            'nama_sales' => $this->request->getPost('nama_sales'),
            'no_telepon' => $this->request->getPost('no_telepon'),
            'alamat' => $this->request->getPost('alamat'),
            'status_aktif' => $this->request->getPost('status_aktif') ? true : false,
        ];

        if ($this->salesModel->update($id, $data)) {
            return redirect()->to('sales')->with('success', 'Sales berhasil diupdate');
        }

        return redirect()->back()->withInput()->with('errors', $this->salesModel->errors());
    }

    public function delete($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $sales = $this->salesModel->find($id);
        if (!$sales) {
            return redirect()->to('sales')->with('error', 'Sales tidak ditemukan');
        }

        $this->salesModel->delete($id);
        return redirect()->to('sales')->with('success', 'Sales berhasil dihapus');
    }
}
