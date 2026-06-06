<?= view('layout/header', ['title' => 'Detail Pembelian']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Detail Pembelian</h4>
        <a href="pembelian" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"><strong>Supplier:</strong> <?= $pembelian['nama_supplier'] ?></div>
                <div class="col-md-3"><strong>Tanggal:</strong> <?= $pembelian['tanggal_pembelian'] ?></div>
                <div class="col-md-3"><strong>Total:</strong> Rp <?= number_format($pembelian['total_pembelian'], 0, ',', '.') ?></div>
                <div class="col-md-3"><strong>Status:</strong> <span class="badge bg-<?= $pembelian['status_pembayaran'] === 'lunas' ? 'success' : ($pembelian['status_pembayaran'] === 'cicil' ? 'warning' : 'danger') ?>"><?= $pembelian['status_pembayaran'] ?></span></div>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-pencil-square"></i> Update Status Pembayaran</h6></div>
        <div class="card-body">
            <form action="pembelian/update-status/<?= $pembelian['id'] ?>" method="POST" class="row align-items-end g-2">
                <div class="col-md-4">
                    <label class="form-label small mb-1">Status Pembayaran</label>
                    <select name="status_pembayaran" class="form-control form-control-sm">
                        <option value="belum_lunas" <?= $pembelian['status_pembayaran'] === 'belum_lunas' ? 'selected' : '' ?>>Belum Lunas</option>
                        <option value="cicil" <?= $pembelian['status_pembayaran'] === 'cicil' ? 'selected' : '' ?>>Cicil</option>
                        <option value="lunas" <?= $pembelian['status_pembayaran'] === 'lunas' ? 'selected' : '' ?>>Lunas</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-check-circle"></i> Update Status</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-white"><h6 class="mb-0">Item Dibeli</h6></div>
        <div class="card-body">
            <table class="table table-hover">
                <thead><tr><th>No</th><th>Produk</th><th>Jumlah</th><th>Harga Beli</th><th>Subtotal</th></tr></thead>
                <tbody>
                    <?php foreach ($detail as $i => $d): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $d['nama_produk'] ?></td>
                            <td><?= $d['jumlah'] ?></td>
                            <td>Rp <?= number_format($d['harga_beli'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= view('layout/footer') ?>