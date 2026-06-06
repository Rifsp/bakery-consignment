<?php
namespace App\Controllers;

use App\Models\DistribusiModel;
use App\Models\DetailDistribusiModel;
use App\Models\StokSalesModel;
use App\Models\SalesModel;
use App\Models\WarungModel;
use App\Models\ProdukModel;
use App\Models\HargaJualModel;

class Distribusi extends BaseController
{
    protected $distribusiModel;
    protected $detailDistribusiModel;
    protected $stokSalesModel;
    protected $salesModel;
    protected $warungModel;
    protected $produkModel;
    protected $hargaJualModel;

    public function __construct()
    {
        $this->distribusiModel = new DistribusiModel();
        $this->detailDistribusiModel = new DetailDistribusiModel();
        $this->stokSalesModel = new StokSalesModel();
        $this->salesModel = new SalesModel();
        $this->warungModel = new WarungModel();
        $this->produkModel = new ProdukModel();
        $this->hargaJualModel = new HargaJualModel();
    }

    public function index()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $tanggalMulai = $this->request->getGet('tanggal_mulai') ?? date('Y-m-01');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');

        $data = [
            'user' => $this->getUser(),
            'distribusi' => $this->distribusiModel
                ->select('distribusi.*, sales.nama_sales, warung.nama_warung')
                ->join('sales', 'sales.id = distribusi.id_sales')
                ->join('warung', 'warung.id = distribusi.id_warung')
                ->where('distribusi.tanggal_distribusi >=', $tanggalMulai)
                ->where('distribusi.tanggal_distribusi <=', $tanggalAkhir)
                ->orderBy('distribusi.tanggal_distribusi', 'DESC')
                ->findAll(),
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_akhir' => $tanggalAkhir,
        ];

        return view('distribusi/index', $data);
    }

    public function create()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $data = [
            'user' => $this->getUser(),
            'sales_list' => $this->salesModel->where('status_aktif', true)->findAll(),
            'warung_list' => $this->warungModel->where('status_aktif', true)->findAll(),
            'produk_list' => $this->produkModel->orderBy('nama_produk', 'ASC')->findAll(),
            'harga_list' => $this->hargaJualModel->findAll(),
        ];

        return view('distribusi/form', $data);
    }

    public function store()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();
        $db->transStart();

        $distribusiData = [
            'kode_distribusi' => $this->generateKodeTransaksi('distribusi', 'kode_distribusi', 'DB'),
            'id_stok_sales' => $this->request->getPost('id_stok_sales') ?: null,
            'id_warung' => $this->request->getPost('id_warung'),
            'id_sales' => $this->request->getPost('id_sales'),
            'tanggal_distribusi' => $this->request->getPost('tanggal_distribusi'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $this->distribusiModel->insert($distribusiData);
        $distribusiId = $this->distribusiModel->getInsertID();

        $produkIds = $this->request->getPost('id_produk');
        $hargaIds = $this->request->getPost('id_harga');
        $jumlahs = $this->request->getPost('jumlah');

        if ($produkIds) {
            foreach ($produkIds as $i => $produkId) {
                $harga = $this->hargaJualModel->find($hargaIds[$i]);
                $jumlah = $jumlahs[$i];
                $hargaSatuan = $harga ? $harga['harga_jual'] : 0;

                $this->detailDistribusiModel->insert([
                    'id_distribusi' => $distribusiId,
                    'id_produk' => $produkId,
                    'id_harga' => $hargaIds[$i],
                    'jumlah' => $jumlah,
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $hargaSatuan * $jumlah,
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan distribusi');
        }

        return redirect()->to('distribusi')->with('success', 'Distribusi berhasil disimpan');
    }

    public function detail($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $distribusi = $this->distribusiModel
            ->select('distribusi.*, sales.nama_sales, warung.nama_warung')
            ->join('sales', 'sales.id = distribusi.id_sales')
            ->join('warung', 'warung.id = distribusi.id_warung')
            ->find($id);

        if (!$distribusi) {
            return redirect()->to('distribusi')->with('error', 'Data tidak ditemukan');
        }

        $detail = $this->detailDistribusiModel
            ->select('detail_distribusi.*, produk.nama_produk, harga_jual.nama_harga')
            ->join('produk', 'produk.id = detail_distribusi.id_produk')
            ->join('harga_jual', 'harga_jual.id = detail_distribusi.id_harga')
            ->where('id_distribusi', $id)
            ->findAll();

        $data = [
            'user' => $this->getUser(),
            'distribusi' => $distribusi,
            'detail' => $detail,
        ];

        return view('distribusi/detail', $data);
    }
}
