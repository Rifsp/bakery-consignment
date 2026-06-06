<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'nama_lengkap', 'role', 'status_aktif'];
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'password' => 'required|min_length[6]',
        'nama_lengkap' => 'required|max_length[100]',
        'role' => 'required|in_list[admin,gudang,sales,owner]',
    ];
}
