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

        $user = $this->getUser();
        $tanggalMulai = $this->request->getGet('tanggal_mulai') ?? date('Y-m-01');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');

        $builder = $this->returModel
            ->select('retur.*, retur.kode_retur, warung.nama_warung, sales.nama_sales')
            ->join('warung', 'warung.id = retur.id_warung')
            ->join('sales', 'sales.id = retur.id_sales')
            ->where('retur.tanggal_retur >=', $tanggalMulai)
            ->where('retur.tanggal_retur <=', $tanggalAkhir)
            ->orderBy('retur.tanggal_retur', 'DESC');

        if ($user['role'] === 'sales') {
            $builder->where('retur.id_sales', $this->getSalesId());
        }

        $data = [
            'user' => $user,
            'retur' => $builder->findAll(),
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

        $id_warung = $this->request->getPost('id_warung');
        $produkIds = $this->request->getPost('id_produk');
        $jumlahs = $this->request->getPost('jumlah');

        if ($produkIds) {
            foreach ($produkIds as $i => $produkId) {
                $stokWarung = $this->hitungStokWarung($id_warung, $produkId);
                $jumlah = $jumlahs[$i];
                if ($jumlah > $stokWarung) {
                    $produk = $this->produkModel->find($produkId);
                    $namaProduk = $produk ? $produk['nama_produk'] : 'ID ' . $produkId;
                    return redirect()->back()->withInput()->with('error', 'Stok di warung tidak cukup untuk produk ' . $namaProduk . '. Sisa stok: ' . $stokWarung);
                }
            }
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $totalRetur = 0;

        if ($produkIds) {
            foreach ($produkIds as $i => $produkId) {
                $produk = $this->produkModel->find($produkId);
                $hpp = $produk ? $produk['hpp'] : 0;
                $totalRetur += $hpp * $jumlahs[$i];
            }
        }

        $returData = [
            'kode_retur' => $this->generateKodeTransaksi('retur', 'kode_retur', 'RT'),
            'id_warung' => $id_warung,
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

    public function edit($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $redirect = $this->requireRole(['admin']);
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
            'warung_list' => $this->warungModel->where('status_aktif', true)->findAll(),
            'sales_list' => $this->salesModel->where('status_aktif', true)->findAll(),
            'produk_list' => $this->produkModel->orderBy('nama_produk', 'ASC')->findAll(),
        ];

        return view('retur/edit', $data);
    }

    public function update($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $retur = $this->returModel->find($id);
        if (!$retur) {
            return redirect()->to('retur')->with('error', 'Data tidak ditemukan');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $produkIds = $this->request->getPost('id_produk');
        $jumlahs = $this->request->getPost('jumlah');

        $totalRetur = 0;
        if ($produkIds) {
            foreach ($produkIds as $i => $produkId) {
                $produk = $this->produkModel->find($produkId);
                $hpp = $produk ? $produk['hpp'] : 0;
                $totalRetur += $hpp * $jumlahs[$i];
            }
        }

        $returData = [
            'id_warung' => $this->request->getPost('id_warung'),
            'id_sales' => $this->request->getPost('id_sales'),
            'tanggal_retur' => $this->request->getPost('tanggal_retur'),
            'alasan_retur' => $this->request->getPost('alasan_retur'),
            'total_retur' => $totalRetur,
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $this->returModel->update($id, $returData);

        $this->detailReturModel->where('id_retur', $id)->delete();

        if ($produkIds) {
            foreach ($produkIds as $i => $produkId) {
                $produk = $this->produkModel->find($produkId);
                $hpp = $produk ? $produk['hpp'] : 0;

                $this->detailReturModel->insert([
                    'id_retur' => $id,
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
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate retur');
        }

        return redirect()->to('retur/detail/' . $id)->with('success', 'Retur berhasil diupdate');
    }

    public function delete($id)
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $redirect = $this->requireRole(['admin']);
        if ($redirect) return $redirect;

        $retur = $this->returModel->find($id);
        if (!$retur) {
            return redirect()->to('retur')->with('error', 'Data tidak ditemukan');
        }

        $this->detailReturModel->where('id_retur', $id)->delete();
        $this->returModel->delete($id);

        return redirect()->to('retur')->with('success', 'Retur berhasil dihapus');
    }
}
