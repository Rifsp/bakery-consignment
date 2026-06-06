<?php
namespace App\Models;

use CodeIgniter\Model;

class StokSalesModel extends Model
{
    protected $table = 'stok_sales';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_stok', 'id_sales', 'id_produk', 'jumlah', 'tanggal_input', 'status', 'created_by', 'keterangan'];
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'id_sales' => 'required|integer',
        'id_produk' => 'required|integer',
        'jumlah' => 'required|integer',
        'tanggal_input' => 'required|valid_date',
    ];
}
