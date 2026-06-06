<?php
namespace App\Models;

use CodeIgniter\Model;

class DetailReturModel extends Model
{
    protected $table = 'detail_retur';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_retur', 'id_produk', 'jumlah', 'harga_satuan', 'subtotal', 'alasan'];
    protected $useTimestamps = true;
}
