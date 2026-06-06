<?= view('layout/header', ['title' => 'Data Sales']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Data Sales</h4>
        <a href="sales/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Sales</a>
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
                            <th>Nama Sales</th>
                            <th>No. Telepon</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($sales)): ?>
                            <tr><td colspan="7" class="text-center text-muted">Belum ada data</td></tr>
                        <?php else: ?>
                            <?php foreach ($sales as $i => $s): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= $s['kode_sales'] ?></td>
                                    <td><?= $s['nama_sales'] ?></td>
                                    <td><?= $s['no_telepon'] ?></td>
                                    <td><?= $s['alamat'] ?></td>
                                    <td><span class="badge bg-<?= $s['status_aktif'] ? 'success' : 'secondary' ?>"><?= $s['status_aktif'] ? 'Aktif' : 'Nonaktif' ?></span></td>
                                    <td>
                                        <a href="sales/edit/<?= $s['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        <a href="sales/delete/<?= $s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')"><i class="bi bi-trash"></i></a>
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
