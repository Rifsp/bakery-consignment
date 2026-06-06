<?= view('layout/header', ['title' => 'Stok di Warung']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Stok di Warung</h4>
    </div>

    <!-- Summary per Warung -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h6 class="mb-0"><i class="bi bi-shop-window text-primary"></i> Ringkasan Stok per Warung</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <?php if (empty($summary_warung)): ?>
                    <div class="col-12 text-center text-muted">Tidak ada stok di warung</div>
                <?php else: ?>
                    <?php foreach ($summary_warung as $sw): ?>
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-body text-center py-3">
                                    <i class="bi bi-shop text-primary" style="font-size: 1.5rem;"></i>
                                    <h6 class="mt-1 mb-0"><?= $sw['nama_warung'] ?></h6>
                                    <span class="badge bg-primary fs-6"><?= $sw['total_stok'] ?> pcs</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Detail Stok per Warung -->
    <div class="card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="bi bi-box text-info"></i> Detail Stok per Warung per Produk</h6>
            <input type="text" id="liveSearch" class="form-control form-control-sm" style="width: 250px;" placeholder="Cari warung atau produk...">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Warung</th>
                            <th>Produk</th>
                            <th class="text-end">Distribusi</th>
                            <th class="text-end">Terjual</th>
                            <th class="text-end">Retur</th>
                            <th class="text-end">Sisa Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stok_warung)): ?>
                            <tr><td colspan="7" class="text-center text-muted">Tidak ada stok di warung</td></tr>
                        <?php else: ?>
                            <?php foreach ($stok_warung as $i => $s): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= $s['nama_warung'] ?></td>
                                    <td><?= $s['nama_produk'] ?></td>
                                    <td class="text-end"><?= $s['total_distribusi'] ?></td>
                                    <td class="text-end text-success"><?= $s['total_terjual'] ?></td>
                                    <td class="text-end text-danger"><?= $s['total_retur'] ?></td>
                                    <td class="text-end fw-bold <?= $s['sisa_stok'] > 0 ? 'text-primary' : 'text-danger' ?>"><?= $s['sisa_stok'] ?></td>
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
