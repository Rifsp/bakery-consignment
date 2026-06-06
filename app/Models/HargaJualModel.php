<?php
namespace App\Models;

use CodeIgniter\Model;

class HargaJualModel extends Model
{
    protected $table = 'harga_jual';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_produk', 'nama_harga', 'harga_jual', 'fee_sales', 'keterangan'];
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'id_produk' => 'required|integer',
        'nama_harga' => 'required|max_length[50]',
        'harga_jual' => 'required|decimal',
        'fee_sales' => 'required|decimal',
    ];
}
