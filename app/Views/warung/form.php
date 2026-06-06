<?= view('layout/header', ['title' => isset($data) ? 'Edit Warung' : 'Tambah Warung']) ?>
<?= view('layout/sidebar') ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><?= isset($data) ? 'Edit Warung' : 'Tambah Warung' ?></h4>
        <a href="warung" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form action="<?= isset($data) ? 'warung/update/' . $data['id'] : 'warung/store' ?>" method="post">
                <?php if (isset($data)): ?>
                <div class="mb-3">
                    <label for="kode_warung" class="form-label">Kode Warung</label>
                    <input type="text" class="form-control" id="kode_warung" value="<?= $data['kode_warung'] ?>" readonly disabled>
                </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="nama_warung" class="form-label">Nama Warung</label>
                    <input type="text" class="form-control" id="nama_warung" name="nama_warung" 
                           value="<?= isset($data) ? $data['nama_warung'] : '' ?>" required>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= isset($data) ? $data['alamat'] : '' ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="nama_pemilik" class="form-label">Nama Pemilik</label>
                    <input type="text" class="form-control" id="nama_pemilik" name="nama_pemilik" 
                           value="<?= isset($data) ? $data['nama_pemilik'] : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="no_telepon" class="form-label">No Telepon</label>
                    <input type="text" class="form-control" id="no_telepon" name="no_telepon" 
                           value="<?= isset($data) ? $data['no_telepon'] : '' ?>">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
            </form>
        </div>
    </div>
</div>

<?= view('layout/footer') ?>