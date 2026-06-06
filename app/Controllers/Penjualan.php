<?php
namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\SalesModel;
use App\Models\WarungModel;
use App\Models\ProdukModel;
use App\Models\HargaJualModel;
use App\Models\StokSalesModel;
use App\Models\DistribusiModel;

class Penjualan extends BaseController
{
    protected $penjualanModel;
    protected $detailPenjualanModel;
    protected $salesModel;
    protected $warungModel;
    protected $produkModel;
    protected $hargaJualModel;
    protected $stokSalesModel;
    protected $distribusiModel;

    public function __construct()
    {
        $this->penjualanModel = new PenjualanModel();
        $this->detailPenjualanModel = new DetailPenjualanModel();
        $this->salesModel = new SalesModel();
        $this->warungModel = new WarungModel();
        $this->produkModel = new ProdukModel();
        $this->hargaJualModel = new HargaJualModel();
        $this->stokSalesModel = new StokSalesModel();
        $this->distribusiModel = new DistribusiModel();
    }

    public function index()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $tanggalMulai = $this->request->getGet('tanggal_mulai') ?? date('Y-m-01');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');

        $data = [
            'user' => $this->getUser(),
            'penjualan' => $this->penjualanModel
                ->select('penjualan.*, sales.nama_sales, warung.nama_warung')
                ->join('sales', 'sales.id = penjualan.id_sales')
                ->join('warung', 'warung.id = penjualan.id_warung')
                ->where('penjualan.tanggal_penjualan >=', $tanggalMulai)
                ->where('penjualan.tanggal_penjualan <=', $tanggalAkhir)
                ->orderBy('penjualan.tanggal_penjualan', 'DESC')
                ->findAll(),
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_akhir' => $tanggalAkhir,
        ];

        return view('penjualan/index', $data);
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
            'distribusi_list' => $this->distribusiModel
                ->select('distribusi.*, sales.nama_sales, warung.nama_warung')
                ->join('sales', 'sales.id = distribusi.id_sales')
                ->join('warung', 'warung.id = distribusi.id_warung')
                ->findAll(),
        ];

        return view('penjualan/form', $data);
    }

    public function store()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();
        $db->transStart();

        $totalPenjualan = 0;
        $totalHpp = 0;
        $totalFee = 0;

        $produkIds = $this->request->getPost('id_produk');
        $hargaIds = $this->request->getPost('id_harga');
        $jumlahs = $this->request->getPost('jumlah_terjual');

        if ($produkIds) {
            foreach ($produkIds as $i => $produkId) {
                $harga = $this->hargaJualModel->find($hargaIds[$i]);
                $produk = $this->produkModel->find($produkId);
                $jumlah = $jumlahs[$i];

                $hargaSatuan = $harga ? $harga['harga_jual'] : 0;
                $hppSatuan = $produk ? $produk['hpp'] : 0;
                $feeSatuan = $harga ? $harga['fee_sales'] : 0;

                $totalPenjualan += $hargaSatuan * $jumlah;
                $totalHpp += $hppSatuan * $jumlah;
                $totalFee += $feeSatuan * $jumlah;
            }
        }

        $penjualanData = [
            'kode_penjualan' => $this->generateKodeTransaksi('penjualan', 'kode_penjualan', 'PJ'),
            'id_distribusi' => $this->request->getPost('id_distribusi') ?: null,
            'id_sales' => $this->request->getPost('id_sales'),
            'id_warung' => $this->request->getPost('id_warung'),
            'tanggal_penjualan' => $this->request->getPost('tanggal_penjualan'),
            'total_penjualan' => $totalPenjualan,
            'total_hpp' => $totalHpp,
            'total_fee_sales' => $totalFee,
            'total_profit' => $totalPenjualan - $totalHpp - $totalFee,
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $this->penjualanModel->insert($penjualanData);
        $penjualanId = $this->penjualanModel->getInsertID();

        if ($produkIds) {
            foreach ($produkIds as $i => $produkId) {
                $harga = $this->hargaJualModel->find($hargaIds[$i]);
                $produk = $this->produkModel->find($produkId);
                $jumlah = $jumlahs[$i];

                $hargaSatuan = $harga ? $harga['harga_jual'] : 0;
                $hppSatuan = $produk ? $produk['hpp'] : 0;
                $feeSatuan = $harga ? $harga['fee_sales'] : 0;

                $this->detailPenjualanModel->insert([
                    'id_penjualan' => $penjualanId,
                    'id_produk' => $produkId,
                    'id_harga' => $hargaIds[$i],
                    'jumlah_terjual' => $jumlah,
                    'harga_satuan' => $hargaSatuan,
                    'hpp_satuan' => $hppSatuan,
                    'fee_sales_satuan' => $feeSatuan,
                    'subtotal' => $hargaSatuan * $jumlah,
                    'subtotal_hpp' => $hppSatuan * $jumlah,
                    'subtotal_fee' => $feeSatuan * $jumlah,
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan penjualan');
        }

        return redirect()->to('penjualan')->with('success', 'Penjualan berhasil disimpan');
    }

    public function detail($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $penjualan = $this->penjualanModel
            ->select('penjualan.*, sales.nama_sales, warung.nama_warung')
            ->join('sales', 'sales.id = penjualan.id_sales')
            ->join('warung', 'warung.id = penjualan.id_warung')
            ->find($id);

        if (!$penjualan) {
            return redirect()->to('penjualan')->with('error', 'Data tidak ditemukan');
        }

        $detail = $this->detailPenjualanModel
            ->select('detail_penjualan.*, produk.nama_produk, harga_jual.nama_harga')
            ->join('produk', 'produk.id = detail_penjualan.id_produk')
            ->join('harga_jual', 'harga_jual.id = detail_penjualan.id_harga')
            ->where('id_penjualan', $id)
            ->findAll();

        $data = [
            'user' => $this->getUser(),
            'penjualan' => $penjualan,
            'detail' => $detail,
        ];

        return view('penjualan/detail', $data);
    }
}
