<?php

namespace App\Controllers;

use App\Models\StokSalesModel;
use App\Models\SalesModel;
use App\Models\ProdukModel;

class StokSales extends BaseController
{
    protected $stokSalesModel;
    protected $salesModel;
    protected $produkModel;

    public function __construct()
    {
        $this->stokSalesModel = new StokSalesModel();
        $this->salesModel = new SalesModel();
        $this->produkModel = new ProdukModel();
    }

    public function index()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $data = [
            'user' => $this->getUser(),
            'stok_sales' => $this->stokSalesModel
                ->select('stok_sales.*, sales.nama_sales, produk.nama_produk')
                ->join('sales', 'sales.id = stok_sales.id_sales')
                ->join('produk', 'produk.id = stok_sales.id_produk')
                ->orderBy('stok_sales.created_at', 'DESC')
                ->findAll(),
        ];

        return view('stok_sales/index', $data);
    }

    public function create()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $data = [
            'user' => $this->getUser(),
            'sales_list' => $this->salesModel->where('status_aktif', true)->findAll(),
            'produk_list' => $this->produkModel->orderBy('nama_produk', 'ASC')->findAll(),
        ];

        return view('stok_sales/form', $data);
    }

    public function store()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $produkIds = $this->request->getPost('id_produk');
        $jumlahs = $this->request->getPost('jumlah');

        $db = \Config\Database::connect();
        $db->transStart();

        if ($produkIds) {
            foreach ($produkIds as $i => $produkId) {
                $this->stokSalesModel->insert([
                    'kode_stok' => $this->generateKodeTransaksi('stok_sales', 'kode_stok', 'SK'),
                    'id_sales' => $this->request->getPost('id_sales'),
                    'id_produk' => $produkId,
                    'jumlah' => $jumlahs[$i],
                    'tanggal_input' => $this->request->getPost('tanggal_input'),
                    'status' => 'di_sales',
                    'created_by' => $this->getUser()['id'],
                    'keterangan' => $this->request->getPost('keterangan'),
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan stok sales');
        }

        return redirect()->to('stok-sales')->with('success', 'Stok sales berhasil disimpan');
    }

    public function detail($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $stok = $this->stokSalesModel
            ->select('stok_sales.*, sales.nama_sales, produk.nama_produk')
            ->join('sales', 'sales.id = stok_sales.id_sales')
            ->join('produk', 'produk.id = stok_sales.id_produk')
            ->find($id);

        if (!$stok) {
            return redirect()->to('stok-sales')->with('error', 'Data tidak ditemukan');
        }

        $data = [
            'user' => $this->getUser(),
            'stok' => $stok,
        ];

        return view('stok_sales/detail', $data);
    }

    public function updateStatus($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;
        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $stok = $this->stokSalesModel->find($id);
        if (!$stok) {
            return redirect()->to('stok-sales')->with('error', 'Data tidak ditemukan');
        }

        $status = $this->request->getPost('status');
        $allowedStatuses = ['di_sales', 'sudah_distribusi', 'retur'];

        if (!in_array($status, $allowedStatuses)) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        $this->stokSalesModel->update($id, ['status' => $status]);

        return redirect()->to("stok-sales/detail/{$id}")->with('success', 'Status berhasil diupdate');
    }
}
