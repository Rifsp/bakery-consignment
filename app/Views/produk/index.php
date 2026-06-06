<?= view('layout/header', ['title' => 'Data Produk']) ?>
<?= view('layout/sidebar') ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Data Produk</h4>
        <a href="produk/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Produk
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>HPP</th>
                            <th>Stok Minimum</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($produk)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada data produk</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($produk as $i => $p): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= $p['kode_produk'] ?></td>
                                    <td><?= $p['nama_produk'] ?></td>
                                    <td><?= $p['kategori'] ?></td>
                                    <td>Rp <?= number_format($p['hpp'], 0, ',', '.') ?></td>
                                    <td><?= $p['stok_minimum'] ?></td>
                                    <td>
                                        <a href="produk/edit/<?= $p['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="produk/harga/<?= $p['id'] ?>" class="btn btn-sm btn-info">
                                            <i class="bi bi-tag"></i>
                                        </a>
                                        <a href="produk/delete/<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
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

<?= view('layout/footer') ?>
