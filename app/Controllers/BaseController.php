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
}
