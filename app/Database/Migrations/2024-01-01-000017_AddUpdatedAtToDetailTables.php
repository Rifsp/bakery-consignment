<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUpdatedAtToDetailTables extends Migration
{
    public function up()
    {
        $tables = ['detail_penjualan', 'detail_pembelian', 'detail_distribusi', 'detail_retur'];

        foreach ($tables as $table) {
            $this->forge->addColumn($table, [
                'updated_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true,
                ],
            ]);
        }
    }

    public function down()
    {
        $tables = ['detail_penjualan', 'detail_pembelian', 'detail_distribusi', 'detail_retur'];

        foreach ($tables as $table) {
            $this->forge->dropColumn($table, 'updated_at');
        }
    }
}
