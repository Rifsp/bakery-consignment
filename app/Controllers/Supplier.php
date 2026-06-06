<?php

namespace App\Controllers;

use App\Models\SupplierModel;

class Supplier extends BaseController
{
    protected $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
    }

    public function index()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $data = [
            'user' => $this->getUser(),
            'supplier' => $this->supplierModel->orderBy('nama_supplier', 'ASC')->findAll(),
        ];

        return view('supplier/index', $data);
    }

    public function create()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $data = ['user' => $this->getUser()];
        return view('supplier/form', $data);
    }

    public function store()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $data = [
            'kode_supplier' => $this->generateKodeMaster('supplier', 'kode_supplier', 'SUP'),
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'alamat' => $this->request->getPost('alamat'),
            'no_telepon' => $this->request->getPost('no_telepon'),
            'nama_kontak' => $this->request->getPost('nama_kontak'),
            'status_aktif' => $this->request->getPost('status_aktif') ? true : false,
        ];

        if ($this->supplierModel->insert($data)) {
            return redirect()->to('supplier')->with('success', 'Supplier berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('errors', $this->supplierModel->errors());
    }

    public function edit($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $supplier = $this->supplierModel->find($id);
        if (!$supplier) {
            return redirect()->to('supplier')->with('error', 'Supplier tidak ditemukan');
        }

        $data = [
            'user' => $this->getUser(),
            'supplier' => $supplier,
        ];

        return view('supplier/form', $data);
    }

    public function update($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $supplier = $this->supplierModel->find($id);
        if (!$supplier) {
            return redirect()->to('supplier')->with('error', 'Supplier tidak ditemukan');
        }

        $data = [
            'kode_supplier' => $this->request->getPost('kode_supplier'),
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'alamat' => $this->request->getPost('alamat'),
            'no_telepon' => $this->request->getPost('no_telepon'),
            'nama_kontak' => $this->request->getPost('nama_kontak'),
            'status_aktif' => $this->request->getPost('status_aktif') ? true : false,
        ];

        if ($this->supplierModel->update($id, $data)) {
            return redirect()->to('supplier')->with('success', 'Supplier berhasil diupdate');
        }

        return redirect()->back()->withInput()->with('errors', $this->supplierModel->errors());
    }

    public function delete($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $supplier = $this->supplierModel->find($id);
        if (!$supplier) {
            return redirect()->to('supplier')->with('error', 'Supplier tidak ditemukan');
        }

        $this->supplierModel->delete($id);
        return redirect()->to('supplier')->with('success', 'Supplier berhasil dihapus');
    }
}
