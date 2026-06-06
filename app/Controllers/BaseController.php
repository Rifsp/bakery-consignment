<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $request;
    protected $response;
    protected $logger;
    protected $session;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = session();
    }

    protected function isLoggedIn()
    {
        return $this->session->get('logged_in') === true;
    }

    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('login')->with('error', 'Silakan login terlebih dahulu');
        }
        return null;
    }

    protected function getUser()
    {
        return [
            'id' => $this->session->get('user_id'),
            'username' => $this->session->get('username'),
            'nama_lengkap' => $this->session->get('nama_lengkap'),
            'role' => $this->session->get('role'),
        ];
    }

    /**
     * Generate kode transaksi (reset per bulan)
     * Format: PREFIX-YYMM-0001
     * Contoh: PJ-2606-0001
     */
    protected function generateKodeTransaksi($table, $column, $prefix)
    {
        $ym = date('ym'); // YYMM
        $pattern = $prefix . '-' . $ym . '-%';

        $db = \Config\Database::connect();
        $last = $db->table($table)
            ->select($column)
            ->like($column, $prefix . '-' . $ym . '-', 'after')
            ->orderBy($column, 'DESC')
            ->get()
            ->getRowArray();

        if ($last) {
            $lastNum = (int) substr($last[$column], -4);
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        return $prefix . '-' . $ym . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate kode master (global, terus naik)
     * Format: PREFIX-0001
     * Contoh: WRG-0001
     */
    protected function generateKodeMaster($table, $column, $prefix)
    {
        $db = \Config\Database::connect();
        $last = $db->table($table)
            ->select($column)
            ->like($column, $prefix . '-', 'after')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        if ($last) {
            $lastNum = (int) substr($last[$column], strlen($prefix) + 1);
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        return $prefix . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    protected function requireRole($allowedRoles)
    {
        $user = $this->getUser();
        if (!in_array($user['role'], $allowedRoles)) {
            return redirect()->to('dashboard')->with('error', 'Anda tidak memiliki akses');
        }
        return null;
    }

    protected function getSalesId()
    {
        return $this->session->get('sales_id');
    }

    protected function hitungStokWarung($id_warung, $id_produk)
    {
        $db = \Config\Database::connect();
        $result = $db->query("
            SELECT 
                (COALESCE(SUM(dd.jumlah), 0) - COALESCE(SUM(dp.jumlah_terjual), 0) - COALESCE(SUM(dr.jumlah), 0)) as sisa
            FROM produk p
            LEFT JOIN distribusi d ON d.id_warung = ?
            LEFT JOIN detail_distribusi dd ON dd.id_distribusi = d.id AND dd.id_produk = p.id
            LEFT JOIN penjualan pj ON pj.id_warung = ?
            LEFT JOIN detail_penjualan dp ON dp.id_penjualan = pj.id AND dp.id_produk = p.id
            LEFT JOIN retur r ON r.id_warung = ?
            LEFT JOIN detail_retur dr ON dr.id_retur = r.id AND dr.id_produk = p.id
            WHERE p.id = ?
            GROUP BY p.id
        ", [$id_warung, $id_warung, $id_warung, $id_produk])->getRowArray();

        return $result ? (int) $result['sisa'] : 0;
    }

    protected function hitungStokSales($id_sales, $id_produk)
    {
        $db = \Config\Database::connect();
        $result = $db->query("
            SELECT 
                (COALESCE(SUM(ss.jumlah), 0) - COALESCE(SUM(dd.jumlah), 0) - COALESCE(SUM(dr.jumlah), 0)) as sisa
            FROM produk p
            LEFT JOIN stok_sales ss ON ss.id_sales = ? AND ss.id_produk = p.id
            LEFT JOIN distribusi d ON d.id_sales = ?
            LEFT JOIN detail_distribusi dd ON dd.id_distribusi = d.id AND dd.id_produk = p.id
            LEFT JOIN retur r ON r.id_sales = ?
            LEFT JOIN detail_retur dr ON dr.id_retur = r.id AND dr.id_produk = p.id
            WHERE p.id = ?
            GROUP BY p.id
        ", [$id_sales, $id_sales, $id_sales, $id_produk])->getRowArray();

        return $result ? (int) $result['sisa'] : 0;
    }
}
