<?= view('layout/header', ['title' => 'Data Warung']) ?>
<?= view('layout/sidebar') ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Data Warung</h4>
        <a href="warung/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Warung
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
                            <th>Kode Warung</th>
                            <th>Nama Warung</th>
                            <th>Alamat</th>
                            <th>Nama Pemilik</th>
                            <th>No Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($warung)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada data warung</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($warung as $i => $w): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= $w['kode_warung'] ?></td>
                                    <td><?= $w['nama_warung'] ?></td>
                                    <td><?= $w['alamat'] ?></td>
                                    <td><?= $w['nama_pemilik'] ?></td>
                                    <td><?= $w['no_telepon'] ?></td>
                                    <td>
                                        <a href="warung/edit/<?= $w['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="warung/delete/<?= $w['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
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