<?= view('layout/header', ['title' => 'Edit Penjualan']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Edit Penjualan: <?= $penjualan['kode_penjualan'] ?></h4>
        <a href="penjualan/detail/<?= $penjualan['id'] ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body">
            <form action="penjualan/update/<?= $penjualan['id'] ?>" method="POST">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sales</label>
                        <select name="id_sales" class="form-control" required>
                            <?php foreach ($sales_list as $s): ?>
                                <option value="<?= $s['id'] ?>" <?= $penjualan['id_sales'] == $s['id'] ? 'selected' : '' ?>><?= $s['nama_sales'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Warung</label>
                        <select name="id_warung" class="form-control" required>
                            <?php foreach ($warung_list as $w): ?>
                                <option value="<?= $w['id'] ?>" <?= $penjualan['id_warung'] == $w['id'] ? 'selected' : '' ?>><?= $w['nama_warung'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal_penjualan" class="form-control" value="<?= $penjualan['tanggal_penjualan'] ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" value="<?= $penjualan['keterangan'] ?>">
                </div>
                <h6 class="mt-3 mb-2">Item Produk Terjual</h6>
                <div id="item-container">
                    <?php foreach ($detail as $d): ?>
                    <div class="item-row row mb-2">
                        <div class="col-md-4">
                            <select name="id_produk[]" class="form-control" required>
                                <option value="">Pilih Produk</option>
                                <?php foreach ($produk_list as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= $d['id_produk'] == $p['id'] ? 'selected' : '' ?>><?= $p['nama_produk'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="id_harga[]" class="form-control" required>
                                <option value="">Pilih Harga</option>
                                <?php foreach ($harga_list as $h): ?>
                                    <option value="<?= $h['id'] ?>" <?= $d['id_harga'] == $h['id'] ? 'selected' : '' ?>><?= $h['nama_harga'] ?> - Rp <?= number_format($h['harga_jual'], 0, ',', '.') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="jumlah_terjual[]" class="form-control" value="<?= $d['jumlah_terjual'] ?>" placeholder="Jumlah" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-item"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-success btn-sm mb-3" id="add-item"><i class="bi bi-plus"></i> Tambah Item</button>
                <br>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('add-item').addEventListener('click', function() {
    var container = document.getElementById('item-container');
    var newRow = container.querySelector('.item-row').cloneNode(true);
    newRow.querySelectorAll('select, input').forEach(function(el) { if(el.type !== 'button') el.value = ''; });
    container.appendChild(newRow);
});
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
        var rows = document.querySelectorAll('.item-row');
        if (rows.length > 1) { e.target.closest('.item-row').remove(); }
    }
});
</script>
<?= view('layout/footer') ?>
