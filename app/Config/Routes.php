<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/do-login', 'Auth::doLogin');
$routes->get('/logout', 'Auth::logout');

// Dashboard
$routes->get('/dashboard', 'Dashboard::index');

// Produk & Harga
$routes->get('/produk', 'Produk::index');
$routes->get('/produk/create', 'Produk::create');
$routes->post('/produk/store', 'Produk::store');
$routes->get('/produk/edit/(:num)', 'Produk::edit/$1');
$routes->post('/produk/update/(:num)', 'Produk::update/$1');
$routes->get('/produk/delete/(:num)', 'Produk::delete/$1');
$routes->get('/produk/harga/(:num)', 'Produk::harga/$1');
$routes->post('/produk/store-harga/(:num)', 'Produk::storeHarga/$1');
$routes->get('/produk/delete-harga/(:num)', 'Produk::deleteHarga/$1');

// Warung
$routes->get('/warung', 'Warung::index');
$routes->get('/warung/create', 'Warung::create');
$routes->post('/warung/store', 'Warung::store');
$routes->get('/warung/edit/(:num)', 'Warung::edit/$1');
$routes->post('/warung/update/(:num)', 'Warung::update/$1');
$routes->get('/warung/delete/(:num)', 'Warung::delete/$1');

// Sales
$routes->get('/sales', 'Sales::index');
$routes->get('/sales/create', 'Sales::create');
$routes->post('/sales/store', 'Sales::store');
$routes->get('/sales/edit/(:num)', 'Sales::edit/$1');
$routes->post('/sales/update/(:num)', 'Sales::update/$1');
$routes->get('/sales/delete/(:num)', 'Sales::delete/$1');

// Supplier
$routes->get('/supplier', 'Supplier::index');
$routes->get('/supplier/create', 'Supplier::create');
$routes->post('/supplier/store', 'Supplier::store');
$routes->get('/supplier/edit/(:num)', 'Supplier::edit/$1');
$routes->post('/supplier/update/(:num)', 'Supplier::update/$1');
$routes->get('/supplier/delete/(:num)', 'Supplier::delete/$1');

// Stok Sales
$routes->get('/stok-sales', 'StokSales::index');
$routes->get('/stok-sales/create', 'StokSales::create');
$routes->post('/stok-sales/store', 'StokSales::store');
$routes->get('/stok-sales/detail/(:num)', 'StokSales::detail/$1');
$routes->post('/stok-sales/update-status/(:num)', 'StokSales::updateStatus/$1');

// Distribusi
$routes->get('/distribusi', 'Distribusi::index');
$routes->get('/distribusi/create', 'Distribusi::create');
$routes->post('/distribusi/store', 'Distribusi::store');
$routes->get('/distribusi/detail/(:num)', 'Distribusi::detail/$1');

// Penjualan
$routes->get('/penjualan', 'Penjualan::index');
$routes->get('/penjualan/create', 'Penjualan::create');
$routes->post('/penjualan/store', 'Penjualan::store');
$routes->get('/penjualan/detail/(:num)', 'Penjualan::detail/$1');

// Pembelian
$routes->get('/pembelian', 'Pembelian::index');
$routes->get('/pembelian/create', 'Pembelian::create');
$routes->post('/pembelian/store', 'Pembelian::store');
$routes->get('/pembelian/detail/(:num)', 'Pembelian::detail/$1');
$routes->post('/pembelian/update-status/(:num)', 'Pembelian::updateStatus/$1');

// Retur
$routes->get('/retur', 'Retur::index');
$routes->get('/retur/create', 'Retur::create');
$routes->post('/retur/store', 'Retur::store');
$routes->get('/retur/detail/(:num)', 'Retur::detail/$1');

// Laporan
$routes->get('/laporan/penjualan', 'Laporan::penjualan');
$routes->get('/laporan/stok', 'Laporan::stok');
$routes->get('/laporan/export-penjualan', 'Laporan::exportPenjualan');
$routes->get('/laporan/export-stok', 'Laporan::exportStok');
