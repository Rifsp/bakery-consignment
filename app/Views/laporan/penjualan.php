<?= view('layout/header', ['title' => 'Laporan Penjualan']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Laporan Penjualan</h4>
        <a href="laporan/export-penjualan?tanggal_mulai=<?= $tanggal_mulai ?>&tanggal_akhir=<?= $tanggal_akhir ?>&id_sales=<?= $id_sales ?>" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="laporan/penjualan">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="<?= $tanggal_mulai ?>">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" class="form-control" value="<?= $tanggal_akhir ?>">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Sales</label>
                        <select name="id_sales" class="form-control">
                            <option value="">Semua Sales</option>
                            <?php foreach ($sales_list as $s): ?>
                                <option value="<?= $s['id'] ?>" <?= $id_sales == $s['id'] ? 'selected' : '' ?>><?= $s['nama_sales'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <p class="text-muted mb-1">Total Pendapatan</p>
                    <h5 class="text-primary">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <p class="text-muted mb-1">Total HPP</p>
                    <h5 class="text-danger">Rp <?= number_format($total_hpp, 0, ',', '.') ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <p class="text-muted mb-1">Total Fee Sales</p>
                    <h5 class="text-warning">Rp <?= number_format($total_fee, 0, ',', '.') ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <p class="text-muted mb-1">Total Profit</p>
                    <h5 class="text-success">Rp <?= number_format($total_profit, 0, ',', '.') ?></h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white"><h6 class="mb-0">Detail Transaksi</h6></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr><th>No</th><th>Tanggal</th><th>Sales</th><th>Warung</th><th>Total</th><th>HPP</th><th>Fee</th><th>Profit</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($penjualan)): ?>
                            <tr><td colspan="8" class="text-center text-muted">Tidak ada data</td></tr>
                        <?php else: ?>
                            <?php foreach ($penjualan as $i => $p): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= $p['tanggal_penjualan'] ?></td>
                                    <td><?= $p['nama_sales'] ?></td>
                                    <td><?= $p['nama_warung'] ?></td>
                                    <td>Rp <?= number_format($p['total_penjualan'], 0, ',', '.') ?></td>
                                    <td class="text-danger">Rp <?= number_format($p['total_hpp'], 0, ',', '.') ?></td>
                                    <td class="text-warning">Rp <?= number_format($p['total_fee_sales'], 0, ',', '.') ?></td>
                                    <td class="text-success fw-bold">Rp <?= number_format($p['total_profit'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= view('layout/footer') ?>
