<?= view('layout/header', ['title' => 'Detail Stok Sales']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Detail Stok Sales</h4>
        <a href="stok-sales" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><strong>Kode:</strong> <span class="badge bg-primary"><?= $stok['kode_stok'] ?></span></div>
                <div class="col-md-4"><strong>Sales:</strong> <?= $stok['nama_sales'] ?></div>
                <div class="col-md-4"><strong>Produk:</strong> <?= $stok['nama_produk'] ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><strong>Jumlah:</strong> <?= $stok['jumlah'] ?></div>
                <div class="col-md-4"><strong>Tanggal:</strong> <?= $stok['tanggal_input'] ?></div>
                <div class="col-md-4"><strong>Keterangan:</strong> <?= $stok['keterangan'] ?></div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-pencil-square"></i> Update Status</h6></div>
        <div class="card-body">
            <form action="stok-sales/update-status/<?= $stok['id'] ?>" method="POST" class="row align-items-end g-2">
                <div class="col-md-4">
                    <label class="form-label small mb-1">Status</label>
                    <div class="mb-2">
                        <?php
                        $badge = 'bg-info';
                        if ($stok['status'] === 'sudah_distribusi') $badge = 'bg-success';
                        if ($stok['status'] === 'retur') $badge = 'bg-danger';
                        ?>
                        <span class="badge <?= $badge ?>"><?= $stok['status'] ?></span>
                    </div>
                    <select name="status" class="form-control form-control-sm">
                        <option value="di_sales" <?= $stok['status'] === 'di_sales' ? 'selected' : '' ?>>Di Sales</option>
                        <option value="sudah_distribusi" <?= $stok['status'] === 'sudah_distribusi' ? 'selected' : '' ?>>Sudah Distribusi</option>
                        <option value="retur" <?= $stok['status'] === 'retur' ? 'selected' : '' ?>>Retur</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-check-circle"></i> Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= view('layout/footer') ?>
