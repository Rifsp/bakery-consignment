<?= view('layout/header', ['title' => 'Detail Distribusi']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Detail Distribusi</h4>
        <a href="distribusi" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"><strong>Sales:</strong> <?= $distribusi['nama_sales'] ?></div>
                <div class="col-md-4"><strong>Warung:</strong> <?= $distribusi['nama_warung'] ?></div>
                <div class="col-md-4"><strong>Tanggal:</strong> <?= $distribusi['tanggal_distribusi'] ?></div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-white"><h6 class="mb-0">Item yang Didistribusikan</h6></div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr><th>No</th><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($detail as $i => $d): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $d['nama_produk'] ?></td>
                            <td>Rp <?= number_format($d['harga_satuan'], 0, ',', '.') ?></td>
                            <td><?= $d['jumlah'] ?></td>
                            <td>Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= view('layout/footer') ?>
