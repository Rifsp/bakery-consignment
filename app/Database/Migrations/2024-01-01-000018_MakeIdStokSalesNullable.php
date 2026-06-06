<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeIdStokSalesNullable extends Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE distribusi ALTER COLUMN id_stok_sales DROP NOT NULL');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE distribusi ALTER COLUMN id_stok_sales SET NOT NULL');
    }
}
