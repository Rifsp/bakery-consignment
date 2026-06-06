<?php
namespace App\Controllers;

use App\Models\PembelianModel;
use App\Models\DetailPembelianModel;
use App\Models\SupplierModel;
use App\Models\ProdukModel;

class Pembelian extends BaseController
{
    protected $pembelianModel;
    protected $detailPembelianModel;
    protected $supplierModel;
    protected $produkModel;

    public function __construct()
    {
        $this->pembelianModel = new PembelianModel();
        $this->detailPembelianModel = new DetailPembelianModel();
        $this->supplierModel = new SupplierModel();
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
            'pembelian' => $this->pembelianModel
                ->select('pembelian.*, supplier.nama_supplier')
                ->join('supplier', 'supplier.id = pembelian.id_supplier')
                ->where('pembelian.tanggal_pembelian >=', $tanggalMulai)
                ->where('pembelian.tanggal_pembelian <=', $tanggalAkhir)
                ->orderBy('pembelian.tanggal_pembelian', 'DESC')
                ->findAll(),
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_akhir' => $tanggalAkhir,
        ];

        return view('pembelian/index', $data);
    }

    public function create()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $data = [
            'user' => $this->getUser(),
            'supplier_list' => $this->supplierModel->where('status_aktif', true)->findAll(),
            'produk_list' => $this->produkModel->orderBy('nama_produk', 'ASC')->findAll(),
        ];

        return view('pembelian/form', $data);
    }

    public function store()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();
        $db->transStart();

        $totalPembelian = 0;
        $produkIds = $this->request->getPost('id_produk');
        $jumlahs = $this->request->getPost('jumlah');
        $hargaBelis = $this->request->getPost('harga_beli');

        if ($produkIds) {
            foreach ($produkIds as $i => $produkId) {
                $totalPembelian += $hargaBelis[$i] * $jumlahs[$i];
            }
        }

        $pembelianData = [
            'kode_pembelian' => $this->generateKodeTransaksi('pembelian', 'kode_pembelian', 'BL'),
            'id_supplier' => $this->request->getPost('id_supplier'),
            'tanggal_pembelian' => $this->request->getPost('tanggal_pembelian'),
            'total_pembelian' => $totalPembelian,
            'status_pembayaran' => $this->request->getPost('status_pembayaran'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $this->pembelianModel->insert($pembelianData);
        $pembelianId = $this->pembelianModel->getInsertID();

        if ($produkIds) {
            foreach ($produkIds as $i => $produkId) {
                $this->detailPembelianModel->insert([
                    'id_pembelian' => $pembelianId,
                    'id_produk' => $produkId,
                    'jumlah' => $jumlahs[$i],
                    'harga_beli' => $hargaBelis[$i],
                    'subtotal' => $hargaBelis[$i] * $jumlahs[$i],
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan pembelian');
        }

        return redirect()->to('pembelian')->with('success', 'Pembelian berhasil disimpan');
    }

    public function detail($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $pembelian = $this->pembelianModel
            ->select('pembelian.*, supplier.nama_supplier')
            ->join('supplier', 'supplier.id = pembelian.id_supplier')
            ->find($id);

        if (!$pembelian) {
            return redirect()->to('pembelian')->with('error', 'Data tidak ditemukan');
        }

        $detail = $this->detailPembelianModel
            ->select('detail_pembelian.*, produk.nama_produk')
            ->join('produk', 'produk.id = detail_pembelian.id_produk')
            ->where('id_pembelian', $id)
            ->findAll();

        $data = [
            'user' => $this->getUser(),
            'pembelian' => $pembelian,
            'detail' => $detail,
        ];

        return view('pembelian/detail', $data);
    }

    public function updateStatus($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $status = $this->request->getPost('status_pembayaran');

        if (!in_array($status, ['belum_lunas', 'cicil', 'lunas'])) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        $this->pembelianModel->update($id, ['status_pembayaran' => $status]);
        return redirect()->to('pembelian/detail/' . $id)->with('success', 'Status pembayaran berhasil diupdate');
    }
}
