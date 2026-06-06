<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetailRetur extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_retur' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'id_produk' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'jumlah' => [
                'type' => 'INT',
            ],
            'harga_satuan' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'alasan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_retur', 'retur', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_produk', 'produk', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_retur');
    }

    public function down()
    {
        $this->forge->dropTable('detail_retur');
    }
}
