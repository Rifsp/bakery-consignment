<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'nama_lengkap' => 'Administrator',
                'role' => 'admin',
                'status_aktif' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'sales1',
                'password' => password_hash('sales123', PASSWORD_DEFAULT),
                'nama_lengkap' => 'Sales 1',
                'role' => 'sales',
                'status_aktif' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);

        // Get sales1 user id and create sales record
        $user = $this->db->table('users')->where('username', 'sales1')->get()->getRowArray();
        if ($user) {
            $this->db->table('sales')->insert([
                'id_user' => $user['id'],
                'kode_sales' => 'SLS-0001',
                'nama_sales' => 'Sales 1',
                'no_telepon' => '08123456789',
                'alamat' => 'Jl. Sales No. 1',
                'status_aktif' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
