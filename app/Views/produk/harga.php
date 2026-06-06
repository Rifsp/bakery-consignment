<?= view('layout/header', ['title' => 'Harga Jual - ' . $produk['nama_produk']]) ?>
<?= view('layout/sidebar') ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Harga Jual: <?= $produk['nama_produk'] ?></h4>
        <a href="produk" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Tambah Harga Jual</h6>
                </div>
                <div class="card-body">
                    <form action="produk/store-harga/<?= $produk['id'] ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Harga</label>
                            <input type="text" name="nama_harga" class="form-control" placeholder="Contoh: Harga A" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga Jual</label>
                            <input type="number" name="harga_jual" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fee Sales</label>
                            <input type="number" name="fee_sales" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <input type="text" name="keterangan" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Daftar Harga Jual</h6>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Harga</th>
                                <th>Harga Jual</th>
                                <th>Fee Sales</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($harga_jual)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada harga jual</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($harga_jual as $h): ?>
                                    <tr>
                                        <td><?= $h['nama_harga'] ?></td>
                                        <td>Rp <?= number_format($h['harga_jual'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format($h['fee_sales'], 0, ',', '.') ?></td>
                                        <td><?= $h['keterangan'] ?></td>
                                        <td>
                                            <a href="produk/delete-harga/<?= $h['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('layout/footer') ?>
