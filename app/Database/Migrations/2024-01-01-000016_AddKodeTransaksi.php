<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKodeTransaksi extends Migration
{
    public function up()
    {
        // Penjualan
        $this->forge->addColumn('penjualan', [
            'kode_penjualan' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
        ]);
        $this->db->query('CREATE UNIQUE INDEX idx_kode_penjualan ON penjualan(kode_penjualan) WHERE kode_penjualan IS NOT NULL');

        // Distribusi
        $this->forge->addColumn('distribusi', [
            'kode_distribusi' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
        ]);
        $this->db->query('CREATE UNIQUE INDEX idx_kode_distribusi ON distribusi(kode_distribusi) WHERE kode_distribusi IS NOT NULL');

        // Pembelian
        $this->forge->addColumn('pembelian', [
            'kode_pembelian' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
        ]);
        $this->db->query('CREATE UNIQUE INDEX idx_kode_pembelian ON pembelian(kode_pembelian) WHERE kode_pembelian IS NOT NULL');

        // Retur
        $this->forge->addColumn('retur', [
            'kode_retur' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
        ]);
        $this->db->query('CREATE UNIQUE INDEX idx_kode_retur ON retur(kode_retur) WHERE kode_retur IS NOT NULL');

        // Stok Sales
        $this->forge->addColumn('stok_sales', [
            'kode_stok' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
        ]);
        $this->db->query('CREATE UNIQUE INDEX idx_kode_stok ON stok_sales(kode_stok) WHERE kode_stok IS NOT NULL');
    }

    public function down()
    {
        $this->forge->dropColumn('penjualan', 'kode_penjualan');
        $this->forge->dropColumn('distribusi', 'kode_distribusi');
        $this->forge->dropColumn('pembelian', 'kode_pembelian');
        $this->forge->dropColumn('retur', 'kode_retur');
        $this->forge->dropColumn('stok_sales', 'kode_stok');
    }
}
