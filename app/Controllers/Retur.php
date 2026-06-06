<?php

namespace App\Controllers;

use App\Models\ReturModel;
use App\Models\DetailReturModel;
use App\Models\WarungModel;
use App\Models\SalesModel;
use App\Models\ProdukModel;

class Retur extends BaseController
{
    protected $returModel;
    protected $detailReturModel;
    protected $warungModel;
    protected $salesModel;
    protected $produkModel;

    public function __construct()
    {
        $this->returModel = new ReturModel();
        $this->detailReturModel = new DetailReturModel();
        $this->warungModel = new WarungModel();
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
            'retur' => $this->returModel
                ->select('retur.*, warung.nama_warung, sales.nama_sales')
                ->join('warung', 'warung.id = retur.id_warung')
                ->join('sales', 'sales.id = retur.id_sales')
                ->where('retur.tanggal_retur >=', $tanggalMulai)
                ->where('retur.tanggal_retur <=', $tanggalAkhir)
                ->orderBy('retur.tanggal_retur', 'DESC')
                ->findAll(),
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_akhir' => $tanggalAkhir,
        ];

        return view('retur/index', $data);
    }

    public function create()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $data = [
            'user' => $this->getUser(),
            'warung_list' => $this->warungModel->where('status_aktif', true)->findAll(),
            'sales_list' => $this->salesModel->where('status_aktif', true)->findAll(),
            'produk_list' => $this->produkModel->orderBy('nama_produk', 'ASC')->findAll(),
        ];

        return view('retur/form', $data);
    }

    public function store()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();
        $db->transStart();

        $totalRetur = 0;
        $produkIds = $this->request->getPost('id_produk');
        $jumlahs = $this->request->getPost('jumlah');

        if ($produkIds) {
            foreach ($produkIds as $i => $produkId) {
                $produk = $this->produkModel->find($produkId);
                $hpp = $produk ? $produk['hpp'] : 0;
                $totalRetur += $hpp * $jumlahs[$i];
            }
        }

        $returData = [
            'kode_retur' => $this->generateKodeTransaksi('retur', 'kode_retur', 'RT'),
            'id_warung' => $this->request->getPost('id_warung'),
            'id_sales' => $this->request->getPost('id_sales'),
            'tanggal_retur' => $this->request->getPost('tanggal_retur'),
            'alasan_retur' => $this->request->getPost('alasan_retur'),
            'total_retur' => $totalRetur,
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $this->returModel->insert($returData);
        $returId = $this->returModel->getInsertID();

        if ($produkIds) {
            foreach ($produkIds as $i => $produkId) {
                $produk = $this->produkModel->find($produkId);
                $hpp = $produk ? $produk['hpp'] : 0;

                $this->detailReturModel->insert([
                    'id_retur' => $returId,
                    'id_produk' => $produkId,
                    'jumlah' => $jumlahs[$i],
                    'harga_satuan' => $hpp,
                    'subtotal' => $hpp * $jumlahs[$i],
                    'alasan' => $this->request->getPost('alasan_retur'),
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan retur');
        }

        return redirect()->to('retur')->with('success', 'Retur berhasil disimpan');
    }

    public function detail($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $retur = $this->returModel
            ->select('retur.*, warung.nama_warung, sales.nama_sales')
            ->join('warung', 'warung.id = retur.id_warung')
            ->join('sales', 'sales.id = retur.id_sales')
            ->find($id);

        if (!$retur) {
            return redirect()->to('retur')->with('error', 'Data tidak ditemukan');
        }

        $detail = $this->detailReturModel
            ->select('detail_retur.*, produk.nama_produk')
            ->join('produk', 'produk.id = detail_retur.id_produk')
            ->where('id_retur', $id)
            ->findAll();

        $data = [
            'user' => $this->getUser(),
            'retur' => $retur,
            'detail' => $detail,
        ];

        return view('retur/detail', $data);
    }
}
