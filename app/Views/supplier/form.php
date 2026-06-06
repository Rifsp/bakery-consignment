<?= view('layout/header', ['title' => isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><?= isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier' ?></h4>
        <a href="supplier" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger"><ul class="mb-0"><?php foreach (session()->getFlashdata('errors') as $error): ?><li><?= $error ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body">
            <form action="<?= isset($supplier) ? 'supplier/update/' . $supplier['id'] : 'supplier/store' ?>" method="POST">
                <div class="row">
                    <?php if (isset($supplier)): ?>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kode Supplier</label>
                        <input type="text" class="form-control" value="<?= $supplier['kode_supplier'] ?>" readonly disabled>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Supplier</label>
                        <input type="text" name="nama_supplier" class="form-control" value="<?= $supplier['nama_supplier'] ?? '' ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Kontak</label>
                        <input type="text" name="nama_kontak" class="form-control" value="<?= $supplier['nama_kontak'] ?? '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telepon" class="form-control" value="<?= $supplier['no_telepon'] ?? '' ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2"><?= $supplier['alamat'] ?? '' ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            </form>
        </div>
    </div>
</div>
<?= view('layout/footer') ?>
