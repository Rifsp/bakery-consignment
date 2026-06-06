<?php
namespace App\Models;

use CodeIgniter\Model;

class DistribusiModel extends Model
{
    protected $table = 'distribusi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_distribusi', 'id_stok_sales', 'id_warung', 'id_sales', 'tanggal_distribusi', 'keterangan'];
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'id_warung' => 'required|integer',
        'id_sales' => 'required|integer',
        'tanggal_distribusi' => 'required|valid_date',
    ];
}
