<?= view('layout/header', ['title' => isset($sales) ? 'Edit Sales' : 'Tambah Sales']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><?= isset($sales) ? 'Edit Sales' : 'Tambah Sales' ?></h4>
        <a href="sales" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger"><ul class="mb-0"><?php foreach (session()->getFlashdata('errors') as $error): ?><li><?= $error ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body">
            <form action="<?= isset($sales) ? 'sales/update/' . $sales['id'] : 'sales/store' ?>" method="POST">
                <div class="row">
                    <?php if (isset($sales)): ?>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kode Sales</label>
                        <input type="text" class="form-control" value="<?= $sales['kode_sales'] ?>" readonly disabled>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Sales</label>
                        <input type="text" name="nama_sales" class="form-control" value="<?= $sales['nama_sales'] ?? '' ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telepon" class="form-control" value="<?= $sales['no_telepon'] ?? '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">User Akun (Opsional)</label>
                        <select name="id_user" class="form-control">
                            <option value="">-- Pilih User --</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id'] ?>" <?= (isset($sales) && $sales['id_user'] == $u['id']) ? 'selected' : '' ?>><?= $u['username'] ?> - <?= $u['nama_lengkap'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2"><?= $sales['alamat'] ?? '' ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            </form>
        </div>
    </div>
</div>
<?= view('layout/footer') ?>
