<?php
namespace App\Models;

use CodeIgniter\Model;

class ReturModel extends Model
{
    protected $table = 'retur';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_retur', 'id_warung', 'id_sales', 'tanggal_retur', 'alasan_retur', 'total_retur', 'keterangan'];
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'id_warung' => 'required|integer',
        'id_sales' => 'required|integer',
        'tanggal_retur' => 'required|valid_date',
        'alasan_retur' => 'required',
    ];
}
