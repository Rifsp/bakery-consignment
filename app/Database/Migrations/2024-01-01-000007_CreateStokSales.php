<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStokSales extends Migration
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
            'id_sales' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_produk' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'jumlah' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'tanggal_input' => [
                'type' => 'DATE',
            ],
            'status' => [
                'type'    => 'VARCHAR',
                'constraint' => 20,
                'default' => 'di_sales',
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
        $this->forge->addForeignKey('id_sales', 'sales', 'id');
        $this->forge->addForeignKey('id_produk', 'produk', 'id');
        $this->forge->addForeignKey('created_by', 'users', 'id');
        $this->forge->createTable('stok_sales');
    }

    public function down()
    {
        $this->forge->dropTable('stok_sales');
    }
}
