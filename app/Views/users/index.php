<?= view('layout/header', ['title' => 'Kelola User']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Kelola User</h4>
        <a href="users/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah User</a>
    </div>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr><th>No</th><th>Username</th><th>Nama Lengkap</th><th>Role</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $i => $u): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $u['username'] ?></td>
                                <td><?= $u['nama_lengkap'] ?></td>
                                <td><span class="badge bg-<?= $u['role'] === 'admin' ? 'danger' : ($u['role'] === 'sales' ? 'info' : 'secondary') ?>"><?= $u['role'] ?></span></td>
                                <td><span class="badge bg-<?= $u['status_aktif'] ? 'success' : 'secondary' ?>"><?= $u['status_aktif'] ? 'Aktif' : 'Nonaktif' ?></span></td>
                                <td>
                                    <a href="users/edit/<?= $u['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                    <?php if ($u['id'] != $user['id']): ?>
                                        <a href="users/delete/<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')"><i class="bi bi-trash"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= view('layout/footer') ?>
