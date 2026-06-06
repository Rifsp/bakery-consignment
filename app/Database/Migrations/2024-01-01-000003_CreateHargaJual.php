<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHargaJual extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_produk' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'nama_harga' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'harga_jual' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'fee_sales' => [
                'type' => 'DECIMAL',
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
        $this->forge->addForeignKey('id_produk', 'produk', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('harga_jual');
    }

    public function down()
    {
        $this->forge->dropTable('harga_jual');
    }
}
