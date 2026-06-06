<?= view('layout/header', ['title' => 'Input Penjualan']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Input Penjualan</h4>
        <a href="penjualan" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="penjualan/store" method="POST">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sales</label>
                        <select name="id_sales" class="form-control" required>
                            <option value="">-- Pilih Sales --</option>
                            <?php foreach ($sales_list as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= $s['nama_sales'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Warung</label>
                        <select name="id_warung" class="form-control" required>
                            <option value="">-- Pilih Warung --</option>
                            <?php foreach ($warung_list as $w): ?>
                                <option value="<?= $w['id'] ?>"><?= $w['nama_warung'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal_penjualan" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Distribusi (Opsional)</label>
                    <select name="id_distribusi" class="form-control">
                        <option value="">-- Tidak Terkait Distribusi --</option>
                        <?php foreach ($distribusi_list as $d): ?>
                            <option value="<?= $d['id'] ?>"><?= $d['nama_sales'] ?> → <?= $d['nama_warung'] ?> (<?= $d['tanggal_distribusi'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control">
                </div>
                <h6 class="mt-3 mb-2">Item Produk Terjual</h6>
                <div id="item-container">
                    <div class="item-row row mb-2">
                        <div class="col-md-4">
                            <select name="id_produk[]" class="form-control" required>
                                <option value="">Pilih Produk</option>
                                <?php foreach ($produk_list as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= $p['nama_produk'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="id_harga[]" class="form-control" required>
                                <option value="">Pilih Harga</option>
                                <?php foreach ($harga_list as $h): ?>
                                    <option value="<?= $h['id'] ?>"><?= $h['nama_harga'] ?> - Rp <?= number_format($h['harga_jual'], 0, ',', '.') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="jumlah_terjual[]" class="form-control" placeholder="Jumlah" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-item"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm mb-3" id="add-item"><i class="bi bi-plus"></i> Tambah Item</button>
                <br>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Penjualan</button>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('add-item').addEventListener('click', function() {
    var container = document.getElementById('item-container');
    var newRow = container.querySelector('.item-row').cloneNode(true);
    newRow.querySelectorAll('select, input').forEach(function(el) { el.value = ''; });
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