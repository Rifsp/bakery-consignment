<?= view('layout/header', ['title' => 'Detail Penjualan']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Detail Penjualan</h4>
        <a href="penjualan" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"><strong>Sales:</strong> <?= $penjualan['nama_sales'] ?></div>
                <div class="col-md-3"><strong>Warung:</strong> <?= $penjualan['nama_warung'] ?></div>
                <div class="col-md-3"><strong>Tanggal:</strong> <?= $penjualan['tanggal_penjualan'] ?></div>
                <div class="col-md-3"><strong>Total:</strong> Rp <?= number_format($penjualan['total_penjualan'], 0, ',', '.') ?></div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header bg-white"><h6 class="mb-0">Item Terjual</h6></div>
        <div class="card-body">
            <table class="table table-hover">
                <thead><tr><th>No</th><th>Produk</th><th>Harga</th><th>HPP</th><th>Fee</th><th>Jumlah</th><th>Subtotal</th></tr></thead>
                <tbody>
                    <?php foreach ($detail as $i => $d): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $d['nama_produk'] ?></td>
                            <td>Rp <?= number_format($d['harga_satuan'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($d['hpp_satuan'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($d['fee_sales_satuan'], 0, ',', '.') ?></td>
                            <td><?= $d['jumlah_terjual'] ?></td>
                            <td>Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold"><td colspan="6" class="text-end">Total Penjualan</td><td>Rp <?= number_format($penjualan['total_penjualan'], 0, ',', '.') ?></td></tr>
                    <tr><td colspan="6" class="text-end">Total HPP</td><td class="text-danger">Rp <?= number_format($penjualan['total_hpp'], 0, ',', '.') ?></td></tr>
                    <tr><td colspan="6" class="text-end">Total Fee Sales</td><td class="text-warning">Rp <?= number_format($penjualan['total_fee_sales'], 0, ',', '.') ?></td></tr>
                    <tr class="fw-bold"><td colspan="6" class="text-end">Profit Bersih</td><td class="text-success">Rp <?= number_format($penjualan['total_profit'], 0, ',', '.') ?></td></tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?= view('layout/footer') ?>