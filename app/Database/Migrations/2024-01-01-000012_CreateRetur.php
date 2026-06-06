<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRetur extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_warung' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'id_sales' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'tanggal_retur' => [
                'type' => 'DATE',
            ],
            'alasan_retur' => [
                'type' => 'TEXT',
            ],
            'total_retur' => [
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
        $this->forge->addForeignKey('id_warung', 'warung', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_sales', 'sales', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('retur');
    }

    public function down()
    {
        $this->forge->dropTable('retur');
    }
}
