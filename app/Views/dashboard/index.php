<?= view('layout/header', ['title' => 'Dashboard']) ?>
<?= view('layout/sidebar') ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Dashboard</h4>
        <span class="text-muted">Selamat datang, <?= $user['nama_lengkap'] ?></span>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Pendapatan Hari Ini</p>
                            <h4 class="mb-0">Rp <?= number_format($total_pendapatan_hari_ini, 0, ',', '.') ?></h4>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-cash-stack" style="font-size: 2rem; color: #28a745;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Profit Hari Ini</p>
                            <h4 class="mb-0">Rp <?= number_format($total_profit_hari_ini, 0, ',', '.') ?></h4>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-graph-up-arrow" style="font-size: 2rem; color: #007bff;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Penjualan Hari Ini</p>
                            <h4 class="mb-0"><?= $penjualan_hari_ini ?> transaksi</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-cart-check" style="font-size: 2rem; color: #ffc107;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Stok di Sales</p>
                            <h4 class="mb-0"><?= $stok_sales_pending ?> item</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-box" style="font-size: 2rem; color: #dc3545;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam" style="font-size: 2rem; color: #667eea;"></i>
                    <h5 class="mt-2 mb-0"><?= $total_produk ?></h5>
                    <p class="text-muted mb-0">Total Produk</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <i class="bi bi-shop-window" style="font-size: 2rem; color: #667eea;"></i>
                    <h5 class="mt-2 mb-0"><?= $total_warung ?></h5>
                    <p class="text-muted mb-0">Total Warung</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <i class="bi bi-people" style="font-size: 2rem; color: #667eea;"></i>
                    <h5 class="mt-2 mb-0"><?= $total_sales ?></h5>
                    <p class="text-muted mb-0">Total Sales</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Hari Ini -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Detail Keuangan Hari Ini</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td>Total Pendapatan</td>
                            <td class="text-end fw-bold">Rp <?= number_format($total_pendapatan_hari_ini, 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td>Total HPP</td>
                            <td class="text-end text-danger">Rp <?= number_format($total_hpp_hari_ini, 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td>Total Fee Sales</td>
                            <td class="text-end text-warning">Rp <?= number_format($total_fee_hari_ini, 0, ',', '.') ?></td>
                        </tr>
                        <tr class="border-top">
                            <td class="fw-bold">Profit Bersih</td>
                            <td class="text-end fw-bold text-success">Rp <?= number_format($total_profit_hari_ini, 0, ',', '.') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-trophy"></i> Produk Terlaris (7 Hari Terakhir)</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($produk_terlaris)): ?>
                        <p class="text-muted text-center mb-0">Belum ada data penjualan</p>
                    <?php else: ?>
                        <table class="table table-borderless mb-0">
                            <?php foreach ($produk_terlaris as $i => $p): ?>
                                <tr>
                                    <td><?= $i + 1 ?>. <?= $p['nama_produk'] ?></td>
                                    <td class="text-end"><?= $p['total_terjual'] ?> pcs</td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('layout/footer') ?>
