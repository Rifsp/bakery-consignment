<?php
namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_supplier', 'nama_supplier', 'alamat', 'no_telepon', 'nama_kontak', 'status_aktif'];
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'kode_supplier' => 'required|max_length[20]|is_unique[supplier.kode_supplier,id,{id}]',
        'nama_supplier' => 'required|max_length[100]',
    ];
}
