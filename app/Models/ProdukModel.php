<?php
namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_produk', 'nama_produk', 'kategori', 'deskripsi', 'hpp', 'stok_minimum'];
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'kode_produk' => 'required|max_length[20]|is_unique[produk.kode_produk,id,{id}]',
        'nama_produk' => 'required|max_length[100]',
        'hpp' => 'required|decimal',
    ];
}
