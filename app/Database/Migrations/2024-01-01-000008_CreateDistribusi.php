<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDistribusi extends Migration
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
            'id_stok_sales' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_warung' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_sales' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tanggal_distribusi' => [
                'type' => 'DATE',
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
        $this->forge->addForeignKey('id_stok_sales', 'stok_sales', 'id');
        $this->forge->addForeignKey('id_warung', 'warung', 'id');
        $this->forge->addForeignKey('id_sales', 'sales', 'id');
        $this->forge->createTable('distribusi');
    }

    public function down()
    {
        $this->forge->dropTable('distribusi');
    }
}
