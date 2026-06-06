<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\StokSalesModel;
use App\Models\WarungModel;
use App\Models\SalesModel;
use App\Models\ProdukModel;
use App\Models\DistribusiModel;

class Laporan extends BaseController
{
    public function penjualan()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $penjualanModel = new PenjualanModel();
        $salesModel = new SalesModel();

        $tanggalMulai = $this->request->getGet('tanggal_mulai') ?? date('Y-m-01');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');
        $idSales = $this->request->getGet('id_sales');

        $baseQuery = $penjualanModel
            ->select('penjualan.*, sales.nama_sales, warung.nama_warung')
            ->join('sales', 'sales.id = penjualan.id_sales')
            ->join('warung', 'warung.id = penjualan.id_warung')
            ->where('penjualan.tanggal_penjualan >=', $tanggalMulai)
            ->where('penjualan.tanggal_penjualan <=', $tanggalAkhir);

        if ($idSales) {
            $baseQuery = $baseQuery->where('penjualan.id_sales', $idSales);
        }

        $data = [
            'user' => $this->getUser(),
            'penjualan' => (clone $baseQuery)->orderBy('penjualan.tanggal_penjualan', 'DESC')->findAll(),
            'sales_list' => $salesModel->where('status_aktif', true)->findAll(),
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_akhir' => $tanggalAkhir,
            'id_sales' => $idSales,
            'total_pendapatan' => (clone $baseQuery)->selectSum('total_penjualan')->first()['total_penjualan'] ?? 0,
            'total_hpp' => (clone $baseQuery)->selectSum('total_hpp')->first()['total_hpp'] ?? 0,
            'total_fee' => (clone $baseQuery)->selectSum('total_fee_sales')->first()['total_fee_sales'] ?? 0,
            'total_profit' => (clone $baseQuery)->selectSum('total_profit')->first()['total_profit'] ?? 0,
        ];

        return view('laporan/penjualan', $data);
    }

    public function stok()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();

        // Stok di Pusat (per produk)
        // Rumus: Total Pembelian - Total Dikirim ke Sales
        $stokPusat = $db->query("
            SELECT 
                p.id,
                p.kode_produk,
                p.nama_produk,
                p.kategori,
                COALESCE(pb.total_beli, 0) as total_beli,
                COALESCE(ss.total_kirim_sales, 0) as total_kirim_sales,
                (COALESCE(pb.total_beli, 0) - COALESCE(ss.total_kirim_sales, 0)) as stok_pusat
            FROM produk p
            LEFT JOIN (
                SELECT id_produk, SUM(jumlah) as total_beli 
                FROM detail_pembelian 
                GROUP BY id_produk
            ) pb ON pb.id_produk = p.id
            LEFT JOIN (
                SELECT id_produk, SUM(jumlah) as total_kirim_sales 
                FROM stok_sales 
                GROUP BY id_produk
            ) ss ON ss.id_produk = p.id
            ORDER BY p.nama_produk
        ")->getResultArray();

        // Stok di Sales (per sales per produk)
        // Rumus: Diterima dari Pusat - Distribusi ke Warung - Retur
        $stokDiSales = $db->query("
            SELECT 
                s.id as id_sales,
                s.nama_sales,
                p.id as id_produk,
                p.nama_produk,
                COALESCE(ss.total_terima, 0) as total_terima,
                COALESCE(db.total_distribusi, 0) as total_distribusi,
                COALESCE(rt.total_retur, 0) as total_retur,
                (COALESCE(ss.total_terima, 0) - COALESCE(db.total_distribusi, 0) - COALESCE(rt.total_retur, 0)) as stok_sales
            FROM sales s
            CROSS JOIN produk p
            LEFT JOIN (
                SELECT id_sales, id_produk, SUM(jumlah) as total_terima 
                FROM stok_sales 
                GROUP BY id_sales, id_produk
            ) ss ON ss.id_sales = s.id AND ss.id_produk = p.id
            LEFT JOIN (
                SELECT d.id_sales, dd.id_produk, SUM(dd.jumlah) as total_distribusi 
                FROM distribusi d
                JOIN detail_distribusi dd ON dd.id_distribusi = d.id
                GROUP BY d.id_sales, dd.id_produk
            ) db ON db.id_sales = s.id AND db.id_produk = p.id
            LEFT JOIN (
                SELECT r.id_sales, dr.id_produk, SUM(dr.jumlah) as total_retur 
                FROM retur r
                JOIN detail_retur dr ON dr.id_retur = r.id
                GROUP BY r.id_sales, dr.id_produk
            ) rt ON rt.id_sales = s.id AND rt.id_produk = p.id
            WHERE s.status_aktif = true
            GROUP BY s.id, s.nama_sales, p.id, p.nama_produk, ss.total_terima, db.total_distribusi, rt.total_retur
            HAVING (COALESCE(ss.total_terima, 0) - COALESCE(db.total_distribusi, 0) - COALESCE(rt.total_retur, 0)) > 0
            ORDER BY s.nama_sales, p.nama_produk
        ")->getResultArray();

        // Summary per sales
        $summarySales = $db->query("
            SELECT 
                s.nama_sales,
                SUM(COALESCE(ss.total_terima, 0) - COALESCE(db.total_distribusi, 0) - COALESCE(rt.total_retur, 0)) as total_stok
            FROM sales s
            CROSS JOIN produk p
            LEFT JOIN (
                SELECT id_sales, id_produk, SUM(jumlah) as total_terima 
                FROM stok_sales 
                GROUP BY id_sales, id_produk
            ) ss ON ss.id_sales = s.id AND ss.id_produk = p.id
            LEFT JOIN (
                SELECT d.id_sales, dd.id_produk, SUM(dd.jumlah) as total_distribusi 
                FROM distribusi d
                JOIN detail_distribusi dd ON dd.id_distribusi = d.id
                GROUP BY d.id_sales, dd.id_produk
            ) db ON db.id_sales = s.id AND db.id_produk = p.id
            LEFT JOIN (
                SELECT r.id_sales, dr.id_produk, SUM(dr.jumlah) as total_retur 
                FROM retur r
                JOIN detail_retur dr ON dr.id_retur = r.id
                GROUP BY r.id_sales, dr.id_produk
            ) rt ON rt.id_sales = s.id AND rt.id_produk = p.id
            WHERE s.status_aktif = true
            GROUP BY s.nama_sales
            HAVING SUM(COALESCE(ss.total_terima, 0) - COALESCE(db.total_distribusi, 0) - COALESCE(rt.total_retur, 0)) > 0
            ORDER BY s.nama_sales
        ")->getResultArray();

        $data = [
            'user' => $this->getUser(),
            'stok_pusat' => $stokPusat,
            'stok_di_sales' => $stokDiSales,
            'summary_sales' => $summarySales,
        ];

        return view('laporan/stok', $data);
    }

    public function stokWarung()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();

        $stokWarung = $db->query("
            SELECT 
                w.id as id_warung,
                w.nama_warung,
                p.id as id_produk,
                p.nama_produk,
                COALESCE(SUM(dd.jumlah), 0) as total_distribusi,
                COALESCE(SUM(dp.jumlah_terjual), 0) as total_terjual,
                COALESCE(SUM(dr.jumlah), 0) as total_retur,
                (COALESCE(SUM(dd.jumlah), 0) - COALESCE(SUM(dp.jumlah_terjual), 0) - COALESCE(SUM(dr.jumlah), 0)) as sisa_stok
            FROM warung w
            CROSS JOIN produk p
            LEFT JOIN distribusi d ON d.id_warung = w.id
            LEFT JOIN detail_distribusi dd ON dd.id_distribusi = d.id AND dd.id_produk = p.id
            LEFT JOIN penjualan pj ON pj.id_warung = w.id
            LEFT JOIN detail_penjualan dp ON dp.id_penjualan = pj.id AND dp.id_produk = p.id
            LEFT JOIN retur r ON r.id_warung = w.id
            LEFT JOIN detail_retur dr ON dr.id_retur = r.id AND dr.id_produk = p.id
            WHERE w.status_aktif = true
            GROUP BY w.id, w.nama_warung, p.id, p.nama_produk
            HAVING (COALESCE(SUM(dd.jumlah), 0) - COALESCE(SUM(dp.jumlah_terjual), 0) - COALESCE(SUM(dr.jumlah), 0)) > 0
            ORDER BY w.nama_warung, p.nama_produk
        ")->getResultArray();

        // Calculate summary from stokWarung results
        $summaryWarung = [];
        foreach ($stokWarung as $sw) {
            $nama = $sw['nama_warung'];
            if (!isset($summaryWarung[$nama])) {
                $summaryWarung[$nama] = ['nama_warung' => $nama, 'total_stok' => 0];
            }
            $summaryWarung[$nama]['total_stok'] += $sw['sisa_stok'];
        }
        $summaryWarung = array_values($summaryWarung);

        $data = [
            'user' => $this->getUser(),
            'stok_warung' => $stokWarung,
            'summary_warung' => $summaryWarung,
        ];

        return view('laporan/stok_warung', $data);
    }

    public function exportPenjualan()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $penjualanModel = new PenjualanModel();

        $tanggalMulai = $this->request->getGet('tanggal_mulai') ?? date('Y-m-01');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');
        $idSales = $this->request->getGet('id_sales');

        $query = $penjualanModel
            ->select('penjualan.kode_penjualan, penjualan.tanggal_penjualan, sales.nama_sales, warung.nama_warung, penjualan.total_penjualan, penjualan.total_hpp, penjualan.total_fee_sales, penjualan.total_profit')
            ->join('sales', 'sales.id = penjualan.id_sales')
            ->join('warung', 'warung.id = penjualan.id_warung')
            ->where('penjualan.tanggal_penjualan >=', $tanggalMulai)
            ->where('penjualan.tanggal_penjualan <=', $tanggalAkhir);

        if ($idSales) {
            $query = $query->where('penjualan.id_sales', $idSales);
        }

        $data = $query->orderBy('penjualan.tanggal_penjualan', 'ASC')->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'LAPORAN PENJUALAN');
        $sheet->setCellValue('A2', 'Periode: ' . $tanggalMulai . ' s/d ' . $tanggalAkhir);
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');

        $headers = ['Kode', 'Tanggal', 'Sales', 'Warung', 'Total', 'HPP', 'Fee Sales', 'Profit'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '4', $h);
            $sheet->getStyle($col . '4')->getFont()->setBold(true);
            $sheet->getStyle($col . '4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('D9E1F2');
            $col++;
        }

        $row = 5;
        foreach ($data as $d) {
            $sheet->setCellValue('A' . $row, $d['kode_penjualan']);
            $sheet->setCellValue('B' . $row, $d['tanggal_penjualan']);
            $sheet->setCellValue('C' . $row, $d['nama_sales']);
            $sheet->setCellValue('D' . $row, $d['nama_warung']);
            $sheet->setCellValue('E' . $row, $d['total_penjualan']);
            $sheet->setCellValue('F' . $row, $d['total_hpp']);
            $sheet->setCellValue('G' . $row, $d['total_fee_sales']);
            $sheet->setCellValue('H' . $row, $d['total_profit']);
            $sheet->getStyle('E' . $row . ':H' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row++;
        }

        $sheet->setCellValue('D' . $row, 'TOTAL');
        $sheet->getStyle('D' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('E' . $row, '=SUM(E5:E' . ($row - 1) . ')');
        $sheet->setCellValue('F' . $row, '=SUM(F5:F' . ($row - 1) . ')');
        $sheet->setCellValue('G' . $row, '=SUM(G5:G' . ($row - 1) . ')');
        $sheet->setCellValue('H' . $row, '=SUM(H5:H' . ($row - 1) . ')');
        $sheet->getStyle('E' . $row . ':H' . $row)->getFont()->setBold(true);
        $sheet->getStyle('E' . $row . ':H' . $row)->getNumberFormat()->setFormatCode('#,##0');

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'laporan_penjualan_' . date('Y-m') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function exportStok()
    {
        $redirect = $this->requireLogin();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();

        // Stok di Pusat
        $stokPusat = $db->query("
            SELECT 
                p.kode_produk,
                p.nama_produk,
                p.kategori,
                COALESCE(pb.total_beli, 0) as total_beli,
                COALESCE(ss.total_kirim_sales, 0) as total_kirim_sales,
                (COALESCE(pb.total_beli, 0) - COALESCE(ss.total_kirim_sales, 0)) as stok_pusat
            FROM produk p
            LEFT JOIN (
                SELECT id_produk, SUM(jumlah) as total_beli 
                FROM detail_pembelian 
                GROUP BY id_produk
            ) pb ON pb.id_produk = p.id
            LEFT JOIN (
                SELECT id_produk, SUM(jumlah) as total_kirim_sales 
                FROM stok_sales 
                GROUP BY id_produk
            ) ss ON ss.id_produk = p.id
            ORDER BY p.nama_produk
        ")->getResultArray();

        // Stok di Sales
        $stokDiSales = $db->query("
            SELECT 
                s.nama_sales,
                p.nama_produk,
                COALESCE(ss.total_terima, 0) as total_terima,
                COALESCE(db.total_distribusi, 0) as total_distribusi,
                COALESCE(rt.total_retur, 0) as total_retur,
                (COALESCE(ss.total_terima, 0) - COALESCE(db.total_distribusi, 0) - COALESCE(rt.total_retur, 0)) as stok_sales
            FROM sales s
            CROSS JOIN produk p
            LEFT JOIN (
                SELECT id_sales, id_produk, SUM(jumlah) as total_terima 
                FROM stok_sales 
                GROUP BY id_sales, id_produk
            ) ss ON ss.id_sales = s.id AND ss.id_produk = p.id
            LEFT JOIN (
                SELECT d.id_sales, dd.id_produk, SUM(dd.jumlah) as total_distribusi 
                FROM distribusi d
                JOIN detail_distribusi dd ON dd.id_distribusi = d.id
                GROUP BY d.id_sales, dd.id_produk
            ) db ON db.id_sales = s.id AND db.id_produk = p.id
            LEFT JOIN (
                SELECT r.id_sales, dr.id_produk, SUM(dr.jumlah) as total_retur 
                FROM retur r
                JOIN detail_retur dr ON dr.id_retur = r.id
                GROUP BY r.id_sales, dr.id_produk
            ) rt ON rt.id_sales = s.id AND rt.id_produk = p.id
            WHERE s.status_aktif = true
            HAVING (COALESCE(ss.total_terima, 0) - COALESCE(db.total_distribusi, 0) - COALESCE(rt.total_retur, 0)) > 0
            ORDER BY s.nama_sales, p.nama_produk
        ")->getResultArray();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Sheet 1: Stok di Pusat
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Stok di Pusat');
        $sheet1->setCellValue('A1', 'LAPORAN STOK DI PUSAT (GUDANG)');
        $sheet1->mergeCells('A1:F1');
        $sheet1->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $headers1 = ['Kode Produk', 'Nama Produk', 'Kategori', 'Total Beli', 'Kirim ke Sales', 'Stok Pusat'];
        foreach ($headers1 as $i => $h) {
            $col = chr(65 + $i);
            $sheet1->setCellValue($col . '3', $h);
            $sheet1->getStyle($col . '3')->getFont()->setBold(true);
            $sheet1->getStyle($col . '3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('D9E1F2');
        }
        $row = 4;
        foreach ($stokPusat as $d) {
            $sheet1->setCellValue('A' . $row, $d['kode_produk']);
            $sheet1->setCellValue('B' . $row, $d['nama_produk']);
            $sheet1->setCellValue('C' . $row, $d['kategori']);
            $sheet1->setCellValue('D' . $row, $d['total_beli']);
            $sheet1->setCellValue('E' . $row, $d['total_kirim_sales']);
            $sheet1->setCellValue('F' . $row, $d['stok_pusat']);
            $row++;
        }
        foreach (range('A', 'F') as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }

        // Sheet 2: Stok di Sales
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Stok di Sales');
        $sheet2->setCellValue('A1', 'LAPORAN STOK DI SALES');
        $sheet2->mergeCells('A1:F1');
        $sheet2->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $headers2 = ['Sales', 'Produk', 'Diterima', 'Distribusi', 'Retur', 'Sisa Stok'];
        foreach ($headers2 as $i => $h) {
            $col = chr(65 + $i);
            $sheet2->setCellValue($col . '3', $h);
            $sheet2->getStyle($col . '3')->getFont()->setBold(true);
            $sheet2->getStyle($col . '3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('D9E1F2');
        }
        $row = 4;
        foreach ($stokDiSales as $d) {
            $sheet2->setCellValue('A' . $row, $d['nama_sales']);
            $sheet2->setCellValue('B' . $row, $d['nama_produk']);
            $sheet2->setCellValue('C' . $row, $d['total_terima']);
            $sheet2->setCellValue('D' . $row, $d['total_distribusi']);
            $sheet2->setCellValue('E' . $row, $d['total_retur']);
            $sheet2->setCellValue('F' . $row, $d['stok_sales']);
            $row++;
        }
        foreach (range('A', 'F') as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'laporan_stok_' . date('Y-m') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
