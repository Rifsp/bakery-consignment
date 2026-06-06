<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenjualan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_distribusi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_sales' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_warung' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tanggal_penjualan' => [
                'type' => 'DATE',
            ],
            'total_penjualan' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'total_hpp' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'total_fee_sales' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'total_profit' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_distribusi', 'distribusi', 'id');
        $this->forge->addForeignKey('id_sales', 'sales', 'id');
        $this->forge->addForeignKey('id_warung', 'warung', 'id');
        $this->forge->createTable('penjualan');
    }

    public function down()
    {
        $this->forge->dropTable('penjualan');
    }
}
