<?php
namespace App\Models;

use CodeIgniter\Model;

class PembelianModel extends Model
{
    protected $table = 'pembelian';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_pembelian', 'id_supplier', 'tanggal_pembelian', 'total_pembelian', 'status_pembayaran', 'keterangan'];
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'id_supplier' => 'required|integer',
        'tanggal_pembelian' => 'required|valid_date',
    ];
}
