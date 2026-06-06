<?php
namespace App\Models;

use CodeIgniter\Model;

class DetailDistribusiModel extends Model
{
    protected $table = 'detail_distribusi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_distribusi', 'id_produk', 'id_harga', 'jumlah', 'harga_satuan', 'subtotal'];
    protected $useTimestamps = true;
}
