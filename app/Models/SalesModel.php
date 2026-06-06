<?php
namespace App\Models;

use CodeIgniter\Model;

class SalesModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_user', 'kode_sales', 'nama_sales', 'no_telepon', 'alamat', 'status_aktif'];
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'kode_sales' => 'required|max_length[20]|is_unique[sales.kode_sales,id,{id}]',
        'nama_sales' => 'required|max_length[100]',
    ];
}
