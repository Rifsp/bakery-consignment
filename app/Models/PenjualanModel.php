<?php
namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_penjualan', 'id_distribusi', 'id_sales', 'id_warung', 'tanggal_penjualan', 'total_penjualan', 'total_hpp', 'total_fee_sales', 'total_profit', 'keterangan'];
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'id_sales' => 'required|integer',
        'id_warung' => 'required|integer',
        'tanggal_penjualan' => 'required|valid_date',
    ];
}
