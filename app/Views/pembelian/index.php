<?= view('layout/header', ['title' => 'Pembelian']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Pembelian ke Supplier</h4>
        <a href="pembelian/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Input Pembelian</a>
    </div>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="card mb-3">
        <div class="card-body py-3">
            <form method="GET" action="pembelian" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small mb-1">Dari Tanggal</label>
                    <input type="date" name="tanggal_mulai" class="form-control form-control-sm" value="<?= $tanggal_mulai ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1">Sampai Tanggal</label>
                    <input type="date" name="tanggal_akhir" class="form-control form-control-sm" value="<?= $tanggal_akhir ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Filter</button>
                    <a href="pembelian" class="btn btn-sm btn-secondary">Reset</a>
                </div>
                <div class="col-md-4">
                    <input type="text" id="liveSearch" class="form-control form-control-sm" placeholder="Cari kode, supplier...">
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable">
                    <thead>
                        <tr><th>No</th><th>Kode</th><th>Tanggal</th><th>Supplier</th><th>Total</th><th>Status Bayar</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pembelian)): ?>
                            <tr><td colspan="7" class="text-center text-muted">Tidak ada data</td></tr>
                        <?php else: ?>
                            <?php foreach ($pembelian as $i => $p): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><span class="badge bg-primary"><?= $p['kode_pembelian'] ?></span></td>
                                    <td><?= $p['tanggal_pembelian'] ?></td>
                                    <td><?= $p['nama_supplier'] ?></td>
                                    <td>Rp <?= number_format($p['total_pembelian'], 0, ',', '.') ?></td>
                                    <td><?php $badge = $p['status_pembayaran'] === 'lunas' ? 'bg-success' : ($p['status_pembayaran'] === 'cicil' ? 'bg-warning' : 'bg-danger'); ?><span class="badge <?= $badge ?>"><?= $p['status_pembayaran'] ?></span></td>
                                    <td><a href="pembelian/detail/<?= $p['id'] ?>" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('liveSearch').addEventListener('keyup', function() {
    var search = this.value.toLowerCase();
    var rows = document.querySelectorAll('#dataTable tbody tr');
    rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        row.style.display = text.includes(search) ? '' : 'none';
    });
});
</script>
<?= view('layout/footer') ?>
