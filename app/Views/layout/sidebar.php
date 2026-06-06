<?php $role = session()->get('role'); ?>
<div class="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-shop"></i> Bakery Consignment
    </div>
    <nav class="mt-3">
        <a href="dashboard" class="nav-link <?= uri_string() === 'dashboard' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        
        <?php if (in_array($role, ['admin', 'gudang'])): ?>
        <div class="text-white-50 small px-3 mt-3 mb-1">MASTER DATA</div>
        <a href="produk" class="nav-link <?= strpos(uri_string(), 'produk') === 0 ? 'active' : '' ?>">
            <i class="bi bi-box-seam"></i> Produk
        </a>
        <a href="warung" class="nav-link <?= strpos(uri_string(), 'warung') === 0 ? 'active' : '' ?>">
            <i class="bi bi-shop-window"></i> Warung
        </a>
        <a href="sales" class="nav-link <?= strpos(uri_string(), 'sales') === 0 ? 'active' : '' ?>">
            <i class="bi bi-people"></i> Sales
        </a>
        <a href="supplier" class="nav-link <?= strpos(uri_string(), 'supplier') === 0 ? 'active' : '' ?>">
            <i class="bi bi-truck"></i> Supplier
        </a>
        <?php endif; ?>

        <div class="text-white-50 small px-3 mt-3 mb-1">TRANSAKSI</div>
        <?php if (in_array($role, ['admin', 'gudang'])): ?>
        <a href="stok-sales" class="nav-link <?= strpos(uri_string(), 'stok-sales') === 0 ? 'active' : '' ?>">
            <i class="bi bi-arrow-down-circle"></i> Stok ke Sales
        </a>
        <?php endif; ?>
        <a href="distribusi" class="nav-link <?= strpos(uri_string(), 'distribusi') === 0 ? 'active' : '' ?>">
            <i class="bi bi-arrow-right-circle"></i> Distribusi ke Warung
        </a>
        <a href="penjualan" class="nav-link <?= strpos(uri_string(), 'penjualan') === 0 ? 'active' : '' ?>">
            <i class="bi bi-cart-check"></i> Penjualan
        </a>
        <?php if (in_array($role, ['admin', 'gudang'])): ?>
        <a href="pembelian" class="nav-link <?= strpos(uri_string(), 'pembelian') === 0 ? 'active' : '' ?>">
            <i class="bi bi-cart-plus"></i> Pembelian ke Supplier
        </a>
        <?php endif; ?>
        <a href="retur" class="nav-link <?= strpos(uri_string(), 'retur') === 0 ? 'active' : '' ?>">
            <i class="bi bi-arrow-return-left"></i> Retur
        </a>

        <div class="text-white-50 small px-3 mt-3 mb-1">LAPORAN</div>
        <a href="laporan/penjualan" class="nav-link <?= strpos(uri_string(), 'laporan/penjualan') === 0 ? 'active' : '' ?>">
            <i class="bi bi-graph-up"></i> Laporan Penjualan
        </a>
        <a href="laporan/stok" class="nav-link <?= strpos(uri_string(), 'laporan/stok') === 0 ? 'active' : '' ?>">
            <i class="bi bi-clipboard-data"></i> Stok Pusat & Sales
        </a>
        <a href="laporan/stok-warung" class="nav-link <?= strpos(uri_string(), 'laporan/stok-warung') === 0 ? 'active' : '' ?>">
            <i class="bi bi-shop"></i> Stok di Warung
        </a>

        <?php if ($role === 'admin'): ?>
        <div class="text-white-50 small px-3 mt-3 mb-1">ADMIN</div>
        <a href="users" class="nav-link <?= strpos(uri_string(), 'users') === 0 ? 'active' : '' ?>">
            <i class="bi bi-person-gear"></i> Kelola User
        </a>
        <?php endif; ?>

        <div class="text-white-50 small px-3 mt-3 mb-1">AKUN</div>
        <a href="logout" class="nav-link">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </nav>
</div>
