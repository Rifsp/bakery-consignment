<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePembelian extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_supplier' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'tanggal_pembelian' => [
                'type' => 'DATE',
            ],
            'total_pembelian' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'status_pembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'belum_lunas',
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
        $this->forge->addForeignKey('id_supplier', 'supplier', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pembelian');
    }

    public function down()
    {
        $this->forge->dropTable('pembelian');
    }
}
