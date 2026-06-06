<?php $role = session()->get('role'); ?>
<?= view('layout/header', ['title' => 'Distribusi']) ?>
<?= view('layout/sidebar') ?>
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Distribusi dari Sales ke Warung</h4>
        <a href="distribusi/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Input Distribusi</a>
    </div>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="card mb-3">
        <div class="card-body py-3">
            <form method="GET" action="distribusi" class="row g-2 align-items-end">
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
                    <a href="distribusi" class="btn btn-sm btn-secondary">Reset</a>
                </div>
                <div class="col-md-4">
                    <input type="text" id="liveSearch" class="form-control form-control-sm" placeholder="Cari kode, sales, warung...">
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable">
                    <thead>
                        <tr><th>No</th><th>Kode</th><th>Tanggal</th><th>Sales</th><th>Warung</th><th>Keterangan</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($distribusi)): ?>
                            <tr><td colspan="7" class="text-center text-muted">Tidak ada data</td></tr>
                        <?php else: ?>
                            <?php foreach ($distribusi as $i => $d): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><span class="badge bg-primary"><?= $d['kode_distribusi'] ?></span></td>
                                    <td><?= $d['tanggal_distribusi'] ?></td>
                                    <td><?= $d['nama_sales'] ?></td>
                                    <td><?= $d['nama_warung'] ?></td>
                                    <td><?= $d['keterangan'] ?></td>
                                    <td>
                                        <a href="distribusi/detail/<?= $d['id'] ?>" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                        <?php if ($role === 'admin'): ?>
                                            <a href="distribusi/edit/<?= $d['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                            <a href="distribusi/delete/<?= $d['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')"><i class="bi bi-trash"></i></a>
                                        <?php endif; ?>
                                    </td>
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
