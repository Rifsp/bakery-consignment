<?= view('layout/header', ['title' => 'Laporan Stok']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Laporan Stok</h4>
        <a href="laporan/export-stok" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card stat-card border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-house-door text-primary" style="font-size: 2rem;"></i>
                    <h5 class="mt-2 mb-0"><?= array_sum(array_column($stok_pusat, 'stok_pusat')) ?></h5>
                    <p class="text-muted mb-0">Total Stok di Pusat</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card border-success">
                <div class="card-body text-center">
                    <i class="bi bi-people text-success" style="font-size: 2rem;"></i>
                    <h5 class="mt-2 mb-0"><?= array_sum(array_column($summary_sales, 'total_stok')) ?></h5>
                    <p class="text-muted mb-0">Total Stok di Sales</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stok di Pusat -->
    <div class="card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="bi bi-house-door text-primary"></i> Stok di Pusat (Gudang)</h6>
            <span class="badge bg-primary"><?= count($stok_pusat) ?> produk</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th class="text-end">Total Beli</th>
                            <th class="text-end">Kirim ke Sales</th>
                            <th class="text-end">Stok Pusat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stok_pusat)): ?>
                            <tr><td colspan="7" class="text-center text-muted">Tidak ada data</td></tr>
                        <?php else: ?>
                            <?php foreach ($stok_pusat as $i => $s): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><span class="badge bg-secondary"><?= $s['kode_produk'] ?></span></td>
                                    <td><?= $s['nama_produk'] ?></td>
                                    <td><?= $s['kategori'] ?></td>
                                    <td class="text-end"><?= $s['total_beli'] ?></td>
                                    <td class="text-end"><?= $s['total_kirim_sales'] ?></td>
                                    <td class="text-end fw-bold <?= $s['stok_pusat'] > 0 ? 'text-success' : 'text-danger' ?>"><?= $s['stok_pusat'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($stok_pusat)): ?>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="4" class="text-end">Total</td>
                            <td class="text-end"><?= array_sum(array_column($stok_pusat, 'total_beli')) ?></td>
                            <td class="text-end"><?= array_sum(array_column($stok_pusat, 'total_kirim_sales')) ?></td>
                            <td class="text-end text-primary"><?= array_sum(array_column($stok_pusat, 'stok_pusat')) ?></td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <!-- Summary per Sales -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h6 class="mb-0"><i class="bi bi-people text-success"></i> Ringkasan Stok per Sales</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <?php if (empty($summary_sales)): ?>
                    <div class="col-12 text-center text-muted">Tidak ada stok di sales</div>
                <?php else: ?>
                    <?php foreach ($summary_sales as $ss): ?>
                        <div class="col-md-3">
                            <div class="card border-success">
                                <div class="card-body text-center py-3">
                                    <i class="bi bi-person-circle text-success" style="font-size: 1.5rem;"></i>
                                    <h6 class="mt-1 mb-0"><?= $ss['nama_sales'] ?></h6>
                                    <span class="badge bg-success fs-6"><?= $ss['total_stok'] ?> pcs</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Detail Stok di Sales -->
    <div class="card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="bi bi-box text-info"></i> Detail Stok di Sales (per Produk)</h6>
            <input type="text" id="liveSearch" class="form-control form-control-sm" style="width: 250px;" placeholder="Cari sales atau produk...">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Sales</th>
                            <th>Produk</th>
                            <th class="text-end">Diterima</th>
                            <th class="text-end">Distribusi</th>
                            <th class="text-end">Retur</th>
                            <th class="text-end">Sisa Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stok_di_sales)): ?>
                            <tr><td colspan="7" class="text-center text-muted">Tidak ada stok di sales</td></tr>
                        <?php else: ?>
                            <?php foreach ($stok_di_sales as $i => $s): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= $s['nama_sales'] ?></td>
                                    <td><?= $s['nama_produk'] ?></td>
                                    <td class="text-end"><?= $s['total_terima'] ?></td>
                                    <td class="text-end"><?= $s['total_distribusi'] ?></td>
                                    <td class="text-end text-danger"><?= $s['total_retur'] ?></td>
                                    <td class="text-end fw-bold <?= $s['stok_sales'] > 0 ? 'text-success' : 'text-danger' ?>"><?= $s['stok_sales'] ?></td>
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
