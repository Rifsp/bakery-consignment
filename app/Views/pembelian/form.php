<?= view('layout/header', ['title' => 'Input Pembelian']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Input Pembelian ke Supplier</h4>
        <a href="pembelian" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="pembelian/store" method="POST">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Supplier</label>
                        <select name="id_supplier" class="form-control" required>
                            <option value="">-- Pilih Supplier --</option>
                            <?php foreach ($supplier_list as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= $s['nama_supplier'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal_pembelian" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status Pembayaran</label>
                        <select name="status_pembayaran" class="form-control">
                            <option value="belum_lunas">Belum Lunas</option>
                            <option value="lunas">Lunas</option>
                            <option value="cicil">Cicil</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control">
                </div>
                <h6 class="mt-3 mb-2">Item Produk</h6>
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
                            <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="harga_beli[]" class="form-control" placeholder="Harga Beli" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-item"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm mb-3" id="add-item"><i class="bi bi-plus"></i> Tambah Item</button>
                <br>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Pembelian</button>
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