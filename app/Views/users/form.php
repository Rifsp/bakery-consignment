<?= view('layout/header', ['title' => isset($userData) ? 'Edit User' : 'Tambah User']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><?= isset($userData) ? 'Edit User' : 'Tambah User' ?></h4>
        <a href="users" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger"><ul class="mb-0"><?php foreach (session()->getFlashdata('errors') as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body">
            <form action="<?= isset($userData) ? 'users/update/' . $userData['id'] : 'users/store' ?>" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= $userData['username'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password <?= isset($userData) ? '(kosongkan jika tidak diubah)' : '' ?></label>
                        <input type="password" name="password" class="form-control" <?= isset($userData) ? '' : 'required' ?>>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?= $userData['nama_lengkap'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control" required>
                            <option value="admin" <?= (isset($userData) && $userData['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                            <option value="sales" <?= (isset($userData) && $userData['role'] === 'sales') ? 'selected' : '' ?>>Sales</option>
                            <option value="gudang" <?= (isset($userData) && $userData['role'] === 'gudang') ? 'selected' : '' ?>>Gudang</option>
                            <option value="owner" <?= (isset($userData) && $userData['role'] === 'owner') ? 'selected' : '' ?>>Owner</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="status_aktif" value="1" <?= (!isset($userData) || $userData['status_aktif']) ? 'checked' : '' ?>>
                            <label class="form-check-label">Aktif</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            </form>
        </div>
    </div>
</div>
<?= view('layout/footer') ?>
