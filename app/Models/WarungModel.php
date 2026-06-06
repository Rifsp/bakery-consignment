<?php
namespace App\Models;

use CodeIgniter\Model;

class WarungModel extends Model
{
    protected $table = 'warung';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_warung', 'nama_warung', 'alamat', 'nama_pemilik', 'no_telepon', 'status_aktif'];
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'kode_warung' => 'required|max_length[20]|is_unique[warung.kode_warung,id,{id}]',
        'nama_warung' => 'required|max_length[100]',
        'alamat' => 'required',
    ];
}
