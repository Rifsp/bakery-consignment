<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetailPenjualan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_penjualan' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'id_produk' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'id_harga' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'jumlah_terjual' => [
                'type' => 'INT',
            ],
            'harga_satuan' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'hpp_satuan' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'fee_sales_satuan' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'subtotal_hpp' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'subtotal_fee' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_penjualan', 'penjualan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_produk', 'produk', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_harga', 'harga_jual', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_penjualan');
    }

    public function down()
    {
        $this->forge->dropTable('detail_penjualan');
    }
}
