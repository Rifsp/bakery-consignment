<?php
namespace App\Models;

use CodeIgniter\Model;

class DetailPenjualanModel extends Model
{
    protected $table = 'detail_penjualan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_penjualan', 'id_produk', 'id_harga', 'jumlah_terjual', 'harga_satuan', 'hpp_satuan', 'fee_sales_satuan', 'subtotal', 'subtotal_hpp', 'subtotal_fee'];
    protected $useTimestamps = true;
}
