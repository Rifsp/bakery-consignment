<?= view('layout/header', ['title' => 'Detail Retur']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Detail Retur</h4>
        <a href="retur" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"><strong>Warung:</strong> <?= $retur['nama_warung'] ?></div>
                <div class="col-md-3"><strong>Sales:</strong> <?= $retur['nama_sales'] ?></div>
                <div class="col-md-3"><strong>Tanggal:</strong> <?= $retur['tanggal_retur'] ?></div>
                <div class="col-md-3"><strong>Total:</strong> Rp <?= number_format($retur['total_retur'], 0, ',', '.') ?></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6"><strong>Alasan:</strong> <?= $retur['alasan_retur'] ?></div>
                <div class="col-md-6"><strong>Keterangan:</strong> <?= $retur['keterangan'] ?></div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-white"><h6 class="mb-0">Item Retur</h6></div>
        <div class="card-body">
            <table class="table table-hover">
                <thead><tr><th>No</th><th>Produk</th><th>Jumlah</th><th>Harga</th><th>Subtotal</th></tr></thead>
                <tbody>
                    <?php foreach ($detail as $i => $d): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $d['nama_produk'] ?></td>
                            <td><?= $d['jumlah'] ?></td>
                            <td>Rp <?= number_format($d['harga_satuan'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= view('layout/footer') ?>
