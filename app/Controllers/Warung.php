<?php

namespace App\Controllers;

use App\Models\WarungModel;

class Warung extends BaseController
{
    protected $warungModel;

    public function __construct()
    {
        $this->warungModel = new WarungModel();
    }

    public function index()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $data = [
            'user' => $this->getUser(),
            'warung' => $this->warungModel->orderBy('nama_warung', 'ASC')->findAll(),
        ];

        return view('warung/index', $data);
    }

    public function create()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $data = ['user' => $this->getUser()];
        return view('warung/form', $data);
    }

    public function store()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $data = [
            'kode_warung' => $this->generateKodeMaster('warung', 'kode_warung', 'WRG'),
            'nama_warung' => $this->request->getPost('nama_warung'),
            'alamat' => $this->request->getPost('alamat'),
            'nama_pemilik' => $this->request->getPost('nama_pemilik'),
            'no_telepon' => $this->request->getPost('no_telepon'),
            'status_aktif' => $this->request->getPost('status_aktif') ? true : false,
        ];

        if ($this->warungModel->insert($data)) {
            return redirect()->to('warung')->with('success', 'Warung berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('errors', $this->warungModel->errors());
    }

    public function edit($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $warung = $this->warungModel->find($id);
        if (!$warung) {
            return redirect()->to('warung')->with('error', 'Warung tidak ditemukan');
        }

        $data = [
            'user' => $this->getUser(),
            'warung' => $warung,
        ];

        return view('warung/form', $data);
    }

    public function update($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $warung = $this->warungModel->find($id);
        if (!$warung) {
            return redirect()->to('warung')->with('error', 'Warung tidak ditemukan');
        }

        $data = [
            'kode_warung' => $this->request->getPost('kode_warung'),
            'nama_warung' => $this->request->getPost('nama_warung'),
            'alamat' => $this->request->getPost('alamat'),
            'nama_pemilik' => $this->request->getPost('nama_pemilik'),
            'no_telepon' => $this->request->getPost('no_telepon'),
            'status_aktif' => $this->request->getPost('status_aktif') ? true : false,
        ];

        if ($this->warungModel->update($id, $data)) {
            return redirect()->to('warung')->with('success', 'Warung berhasil diupdate');
        }

        return redirect()->back()->withInput()->with('errors', $this->warungModel->errors());
    }

    public function delete($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $warung = $this->warungModel->find($id);
        if (!$warung) {
            return redirect()->to('warung')->with('error', 'Warung tidak ditemukan');
        }

        $this->warungModel->delete($id);
        return redirect()->to('warung')->with('success', 'Warung berhasil dihapus');
    }
}
