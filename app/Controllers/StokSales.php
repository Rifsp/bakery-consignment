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

        $tanggalMulai = $this->request->getGet('tanggal_mulai') ?? date('Y-m-01');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');

        $data = [
            'user' => $this->getUser(),
            'stok_sales' => $this->stokSalesModel
                ->select('stok_sales.*, sales.nama_sales, produk.nama_produk')
                ->join('sales', 'sales.id = stok_sales.id_sales')
                ->join('produk', 'produk.id = stok_sales.id_produk')
                ->where('stok_sales.tanggal_input >=', $tanggalMulai)
                ->where('stok_sales.tanggal_input <=', $tanggalAkhir)
                ->orderBy('stok_sales.tanggal_input', 'DESC')
                ->findAll(),
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_akhir' => $tanggalAkhir,
        ];

        return view('stok_sales/index', $data);
    }

    public function create()
    {
        $redirect = $this->requireLogin();
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

        $data = [
            'kode_stok' => $this->generateKodeTransaksi('stok_sales', 'kode_stok', 'SK'),
            'id_sales' => $this->request->getPost('id_sales'),
            'id_produk' => $this->request->getPost('id_produk'),
            'jumlah' => $this->request->getPost('jumlah'),
            'tanggal_input' => $this->request->getPost('tanggal_input'),
            'status' => 'di_sales',
            'created_by' => $this->getUser()['id'],
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        if ($this->stokSalesModel->insert($data)) {
            return redirect()->to('stok-sales')->with('success', 'Stok berhasil ditambahkan ke sales');
        }

        return redirect()->back()->withInput()->with('errors', $this->stokSalesModel->errors());
    }

    public function detail($id)
    {
        $redirect = $this->requireLogin();
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

        $status = $this->request->getPost('status');

        if (!in_array($status, ['di_sales', 'sudah_distribusi', 'retur'])) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        $this->stokSalesModel->update($id, ['status' => $status]);
        return redirect()->to('stok-sales/detail/' . $id)->with('success', 'Status berhasil diupdate');
    }
}
