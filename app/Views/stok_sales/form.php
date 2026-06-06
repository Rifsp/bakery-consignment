<?= view('layout/header', ['title' => 'Input Stok ke Sales']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Input Stok ke Sales</h4>
        <a href="stok-sales" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger"><ul class="mb-0"><?php foreach (session()->getFlashdata('errors') as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body">
            <form action="stok-sales/store" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sales</label>
                        <select name="id_sales" class="form-control" required>
                            <option value="">-- Pilih Sales --</option>
                            <?php foreach ($sales_list as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= $s['nama_sales'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Produk</label>
                        <select name="id_produk" class="form-control" required>
                            <option value="">-- Pilih Produk --</option>
                            <?php foreach ($produk_list as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= $p['nama_produk'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal_input" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            </form>
        </div>
    </div>
</div>
<?= view('layout/footer') ?>
