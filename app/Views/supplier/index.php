<?= view('layout/header', ['title' => 'Data Supplier']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Data Supplier</h4>
        <a href="supplier/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Supplier</a>
    </div>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
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
                            <th>Nama Supplier</th>
                            <th>Kontak</th>
                            <th>No. Telepon</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($supplier)): ?>
                            <tr><td colspan="7" class="text-center text-muted">Belum ada data</td></tr>
                        <?php else: ?>
                            <?php foreach ($supplier as $i => $s): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= $s['kode_supplier'] ?></td>
                                    <td><?= $s['nama_supplier'] ?></td>
                                    <td><?= $s['nama_kontak'] ?></td>
                                    <td><?= $s['no_telepon'] ?></td>
                                    <td><?= $s['alamat'] ?></td>
                                    <td>
                                        <a href="supplier/edit/<?= $s['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        <a href="supplier/delete/<?= $s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')"><i class="bi bi-trash"></i></a>
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
