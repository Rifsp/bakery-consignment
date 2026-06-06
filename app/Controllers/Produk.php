<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use App\Models\HargaJualModel;

class Produk extends BaseController
{
    protected $produkModel;
    protected $hargaJualModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
        $this->hargaJualModel = new HargaJualModel();
    }

    public function index()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $data = [
            'user' => $this->getUser(),
            'produk' => $this->produkModel->orderBy('nama_produk', 'ASC')->findAll(),
        ];

        return view('produk/index', $data);
    }

    public function create()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $data = ['user' => $this->getUser()];
        return view('produk/form', $data);
    }

    public function store()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $data = [
            'kode_produk' => $this->request->getPost('kode_produk'),
            'nama_produk' => $this->request->getPost('nama_produk'),
            'kategori' => $this->request->getPost('kategori'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'hpp' => $this->request->getPost('hpp'),
            'stok_minimum' => $this->request->getPost('stok_minimum'),
        ];

        if ($this->produkModel->insert($data)) {
            return redirect()->to('produk')->with('success', 'Produk berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('errors', $this->produkModel->errors());
    }

    public function edit($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $produk = $this->produkModel->find($id);
        if (!$produk) {
            return redirect()->to('produk')->with('error', 'Produk tidak ditemukan');
        }

        $data = [
            'user' => $this->getUser(),
            'produk' => $produk,
            'harga_jual' => $this->hargaJualModel->where('id_produk', $id)->findAll(),
        ];

        return view('produk/form', $data);
    }

    public function update($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $data = [
            'kode_produk' => $this->request->getPost('kode_produk'),
            'nama_produk' => $this->request->getPost('nama_produk'),
            'kategori' => $this->request->getPost('kategori'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'hpp' => $this->request->getPost('hpp'),
            'stok_minimum' => $this->request->getPost('stok_minimum'),
        ];

        if ($this->produkModel->update($id, $data)) {
            return redirect()->to('produk')->with('success', 'Produk berhasil diupdate');
        }

        return redirect()->back()->withInput()->with('errors', $this->produkModel->errors());
    }

    public function delete($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $this->hargaJualModel->where('id_produk', $id)->delete();
        $this->produkModel->delete($id);

        return redirect()->to('produk')->with('success', 'Produk berhasil dihapus');
    }

    // Harga Jual
    public function harga($produk_id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $produk = $this->produkModel->find($produk_id);
        if (!$produk) {
            return redirect()->to('produk')->with('error', 'Produk tidak ditemukan');
        }

        $data = [
            'user' => $this->getUser(),
            'produk' => $produk,
            'harga_jual' => $this->hargaJualModel->where('id_produk', $produk_id)->findAll(),
        ];

        return view('produk/harga', $data);
    }

    public function storeHarga($produk_id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $data = [
            'id_produk' => $produk_id,
            'nama_harga' => $this->request->getPost('nama_harga'),
            'harga_jual' => $this->request->getPost('harga_jual'),
            'fee_sales' => $this->request->getPost('fee_sales'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        if ($this->hargaJualModel->insert($data)) {
            return redirect()->back()->with('success', 'Harga jual berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('errors', $this->hargaJualModel->errors());
    }

    public function deleteHarga($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $harga = $this->hargaJualModel->find($id);
        $this->hargaJualModel->delete($id);

        return redirect()->back()->with('success', 'Harga jual berhasil dihapus');
    }
}
