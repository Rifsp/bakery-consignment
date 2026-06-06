<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\StokSalesModel;
use App\Models\WarungModel;
use App\Models\SalesModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $produkModel = new ProdukModel();
        $penjualanModel = new PenjualanModel();
        $detailPenjualanModel = new DetailPenjualanModel();
        $stokSalesModel = new StokSalesModel();
        $warungModel = new WarungModel();
        $salesModel = new SalesModel();

        $today = date('Y-m-d');

        $data = [
            'user' => $this->getUser(),
            'total_produk' => $produkModel->countAll(),
            'total_warung' => $warungModel->where('status_aktif', true)->countAllResults(),
            'total_sales' => $salesModel->where('status_aktif', true)->countAllResults(),
            'penjualan_hari_ini' => $penjualanModel->where('tanggal_penjualan', $today)->countAllResults(),
            'total_pendapatan_hari_ini' => $penjualanModel->where('tanggal_penjualan', $today)->selectSum('total_penjualan')->first()['total_penjualan'] ?? 0,
            'total_hpp_hari_ini' => $penjualanModel->where('tanggal_penjualan', $today)->selectSum('total_hpp')->first()['total_hpp'] ?? 0,
            'total_fee_hari_ini' => $penjualanModel->where('tanggal_penjualan', $today)->selectSum('total_fee_sales')->first()['total_fee_sales'] ?? 0,
            'total_profit_hari_ini' => $penjualanModel->where('tanggal_penjualan', $today)->selectSum('total_profit')->first()['total_profit'] ?? 0,
            'produk_terlaris' => $detailPenjualanModel
                ->select('produk.nama_produk, SUM(detail_penjualan.jumlah_terjual) as total_terjual')
                ->join('produk', 'produk.id = detail_penjualan.id_produk')
                ->join('penjualan', 'penjualan.id = detail_penjualan.id_penjualan')
                ->where('penjualan.tanggal_penjualan >=', date('Y-m-d', strtotime('-7 days')))
                ->groupBy('produk.nama_produk')
                ->orderBy('total_terjual', 'DESC')
                ->limit(5)
                ->findAll(),
            'stok_sales_pending' => $stokSalesModel->where('status', 'di_sales')->countAllResults(),
        ];

        return view('dashboard/index', $data);
    }
}
