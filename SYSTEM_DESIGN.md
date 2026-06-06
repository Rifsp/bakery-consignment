# Sistem Informasi Penjualan Roti Konsinyasi

## Gambaran Umum

Sistem ini dirancang untuk mengelola usaha roti dengan sistem penjualan konsinyasi, di mana roti ditempatkan di warung-warung dan pembayaran dilakukan setelah roti terjual.

---

## Fitur Utama

### 1. Manajemen Stok Realtime
- Pencatatan stok masuk (produksi/pembelian dari supplier)
- Pencatatan stok keluar (distribusi ke warung)
- Pencatatan stok retur (roti kadaluarsa/tidak laku)
- Monitoring stok real-time di setiap warung
- Notifikasi stok menipis

### 2. Manajemen Produk & Harga
- Input produk roti (nama, deskripsi, kategori)
- Input HPP (Harga Pokok Penjualan) per produk (fix)
- Input harga jual per produk (bisa multiple harga)
- Input fee sales per produk per harga jual

### 3. Manajemen Stok Sales
- Gudang pusat input produk ke sales
- Monitoring stok yang dipegang sales
- Sales titipkan stok ke warung
- Tracking perpindahan stok (Gudang → Sales → Warung)

### 4. Distribusi ke Warung
- Pencatatan penitipan roti dari sales ke warung
- Monitoring stok di masing-masing warung
- Pencatatan roti kadaluarsa/retur dari warung

### 5. Penjualan & Pendapatan
- Pencatatan penjualan harian per sales
- Total pendapatan sales per hari
- Total pendapatan keseluruhan
- Rekap penjualan per periode

### 6. Analisis Penjualan
- Produk paling laku (best seller)
- Produk kurang peminat (slow moving)
- Performa sales
- Analisis per warung

### 7. Pembelian ke Supplier
- Pencatatan pembelian roti ke supplier
- Riwayat pembelian
- Monitoring hutang ke supplier

---

## Struktur Database

### Tabel Produk
```
- id_produk (PK)
- nama_produk
- kategori
- deskripsi
- hpp (Harga Pokok Penjualan)
- stok_tersedia
- created_at
- updated_at
```

### Tabel Harga Jual
```
- id_harga (PK)
- id_produk (FK)
- nama_harga (misal: Harga A, Harga B)
- harga_jual
- fee_sales
- keterangan
```

### Tabel Warung
```
- id_warung (PK)
- nama_warung
- alamat
- nama_pemilik
- no_telepon
- created_at
```

### Tabel Sales
```
- id_sales (PK)
- nama_sales
- no_telepon
- alamat
- status_aktif
- created_at
```

### Tabel Stok Sales (Penitipan dari Gudang ke Sales)
```
- id_stok_sales (PK)
- id_sales (FK)
- id_produk (FK)
- jumlah
- tanggal_input
- status (di_sales / sudah_distribusi / retur)
- created_by (admin/gudang)
- created_at
```

### Tabel Distribusi Sales ke Warung
```
- id_distribusi (PK)
- id_stok_sales (FK)
- id_warung (FK)
- id_sales (FK)
- tanggal_distribusi
- created_at
```

### Tabel Detail Distribusi
```
- id_detail_distribusi (PK)
- id_distribusi (FK)
- id_produk (FK)
- id_harga (FK)
- jumlah
- harga_satuan
- subtotal
```

### Tabel Penjualan
```
- id_penjualan (PK)
- id_distribusi (FK)
- id_sales (FK)
- id_warung (FK)
- tanggal_penjualan
- total_penjualan
- created_at
```

### Tabel Detail Penjualan
```
- id_detail_penjualan (PK)
- id_penjualan (FK)
- id_produk (FK)
- id_harga (FK)
- jumlah_terjual
- harga_satuan
- subtotal
- fee_sales_per_item
```

### Tabel Retur
```
- id_retur (PK)
- id_warung (FK)
- id_sales (FK)
- tanggal_retur
- alasan_retur
- created_at
```

### Tabel Detail Retur
```
- id_detail_retur (PK)
- id_retur (FK)
- id_produk (FK)
- jumlah
- alasan
```

### Tabel Pembelian Supplier
```
- id_pembelian (PK)
- id_supplier (FK)
- tanggal_pembelian
- total_pembelian
- status_pembayaran
- created_at
```

### Tabel Detail Pembelian
```
- id_detail_pembelian (PK)
- id_pembelian (FK)
- id_produk (FK)
- jumlah
- harga_beli
- subtotal
```

### Tabel Supplier
```
- id_supplier (PK)
- nama_supplier
- alamat
- no_telepon
- nama_kontak
```

---

## Alur Kerja Sistem

### Alur Distribusi
```
Produksi/Pembelian dari Supplier
         ↓
    Stok Gudang Pusat
         ↓
   Input ke Sales (Admin/Gudang)
         ↓
    Stok di Sales
         ↓
  Sales Titip ke Warung
         ↓
    Stok di Warung
         ↓
     Penjualan
         ↓
  Laporan Pendapatan
```

### Alur Penitipan ke Sales
```
1. Admin/Gudang pilih sales
2. Pilih produk dan jumlah
3. Input ke sistem → stok berpindah dari gudang ke sales
4. Sales bisa lihat stok yang dipegangnya
5. Sales distribusikan ke warung-warung
```

### Alur Distribusi Sales ke Warung
```
1. Sales melihat stok yang dipegang
2. Sales kunjungi warung
3. Pilih produk dan jumlah yang dititipkan
4. Input ke sistem → stok berpindah dari sales ke warung
5. Warung menerima roti untuk dijual
```

### Alur Penjualan Harian
```
1. Sales mengunjungi warung
2. Mencatat roti yang terjual
3. Input ke sistem (jumlah, harga yang dipakai)
4. Sistem hitung otomatis:
   - Subtotal per item
   - Fee sales per item
   - Total penjualan
5. Generate laporan harian
```

### Alur Pembelian ke Supplier
```
1. Pilih supplier
2. Pilih produk dan jumlah
3. Input harga beli
4. Sistem update stok otomatis
5. Catat status pembayaran
```

---

## Perhitungan Keuangan

### HPP (Harga Pokok Penjualan)
- Diinput manual per produk
- Bersifat fix, tidak berubah kecuali diupdate manual
- Digunakan untuk menghitung profit kotor

### Fee Sales
- Diinput manual per produk per harga jual
- Fee berbeda tergantung harga yang dipakai sales
- Contoh:
  - Roti A Harga 1: Fee Rp 500
  - Roti A Harga 2: Fee Rp 700

### Harga Jual
- Setiap produk bisa memiliki beberapa harga
- Sales memilih harga saat menjual
- Fee menyesuaikan harga yang dipilih

---

## Laporan yang Dibutuhkan

### Harian
- Total pendapatan sales hari ini
- Total pendapatan keseluruhan hari ini
- Stok terjual per warung
- Produk terjual hari ini

### Mingguan/Bulanan
- Rekap pendapatan per sales
- Produk best seller
- Produk slow moving
- Stok di warung-warung
- Analisis profit per produk

### Khusus
- Daftar roti kadaluarsa per warung
- Performa sales ranking
- Stok minimum per produk
- Hutang ke supplier
- Stok dipegang per sales

---

## Dashboard Utama

### Ringkasan Hari Ini
- Total Penjualan
- Total Pendapatan
- Total Fee Sales
- Jumlah Roti Terjual

### Monitoring Stok
- Stok di Gudang Pusat
- Stok dipegang Sales
- Stok per Warung
- Roti Mendekati Kadaluarsa

### Grafik
- Penjualan harian (line chart)
- Produk terlaris (bar chart)
- Perbandingan warung (pie chart)

---

## Keamanan & Akses

### Role Pengguna
1. **Admin**: Akses penuh ke semua fitur, input stok ke sales
2. **Gudang**: Input stok ke sales, kelola distribusi, retur
3. **Sales**: Lihat stok dipegang, distribusi ke warung, input penjualan
4. **Owner**: View laporan dan dashboard

---

## Teknologi yang Direkomendasikan

### Arsitektur: **Fullstack Monolitik (Tanpa API)**

Sistem ini menggunakan arsitektur server-side rendering langsung, **tidak perlu API terpisah**. Semua proses (view, logic, database) dalam satu aplikasi.

```
Browser → Request → CI4 Controller → Model → PostgreSQL → View → Response
```

**Keuntungan tanpa API:**
- Lebih simpel, tidak perlu manage token/auth API
- Development lebih cepat
- Tidak perlu CORS handling
- Cocok untuk sistem internal/bisnis

---

### Stack yang Digunakan

| Komponen | Teknologi | Alasan |
|----------|-----------|--------|
| **Framework** | CodeIgniter 4 | Ringan, cepat, mudah dipelajari |
| **Database** | PostgreSQL | Relational, stabil, support JSON, gratis |
| **View Engine** | CI4 View + Blade-like syntax | Template bawaan CI4 |
| **CSS** | Tailwind CSS / Bootstrap 5 | Styling cepat & responsif |
| **JS** | jQuery + Alpine.js | Interaksi UI tanpa SPA |
| **Chart** | Chart.js / ApexCharts | Laporan & dashboard |
| **Export** | DomPDF / PhpSpreadsheet | Export laporan PDF/Excel |

---

### Mengapa CodeIgniter 4?

| Aspek | Alasan |
|-------|--------|
| **Ringan** | Tidak se-berat Laravel, cocok untuk sistem bisnis kecil-menengah |
| **Cepat Development** | Scaffolding, migration, seeder sudah ada |
| **Mudah Dipelajari** | Dokumentasi jelas, struktur sederhana |
| **Support PostgreSQL** | Built-in database driver, tidak perlu config ribet |
| **Hosting Murah** | PHP shared hosting murah, tidak perlu VPS |
| **Tidak Over-Engineering** | Tidak banyak fitur yang tidak dipakai |

---

### Mengapa PostgreSQL?

| Aspek | Alasan |
|-------|--------|
| **Gratis & Open Source** | Tidak ada biaya lisensi |
| **Stabil** | Sudah terbukti untuk enterprise |
| **Fitur Lengkap** | Support JSON, Full-text search, CTE |
| **Performa Bagus** | Handling query kompleks lebih baik dari MySQL |
| **Data Integrity** | ACID compliant, referential integrity kuat |

---

### Alternatif yang Ditolak

| Stack | Alasan Ditolak |
|-------|----------------|
| **Laravel** | Terlalu berat untuk sistem ini, overkill |
| **Next.js + API** | Tidak perlu SPA, ribet setup API |
| **Django** | Python, kurang populer untuk web Indonesia |
| **MySQL** | PostgreSQL lebih powerful untuk query kompleks

### Hosting yang Direkomendasikan

| Opsi | Harga | Keterangan |
|------|-------|------------|
| **VPS DigitalOcean** | $6/bulan | Sudah termasuk PostgreSQL, full control |
| **VPS Niagahoster** | 80rb/bulan | Lokasi Indonesia, support lokal |
| **Shared Hosting** | 50rb/bulan | Murah tapi perlu pastikan support PostgreSQL |
| **Local (XAMPP/Laragon)** | Gratis | Untuk development/testing |

**Rekomendasi:** VPS DigitalOcean/Niagahoster karena PostgreSQL lebih stabil di VPS daripada shared hosting.

---

### Struktur Aplikasi CI4

```
app/
├── Config/
│   ├── Database.php        (konfigurasi PostgreSQL)
│   ├── Routes.php          (routing)
│   └── ...
├── Controllers/
│   ├── Produk.php          (CRUD produk)
│   ├── Harga.php           (CRUD harga jual)
│   ├── Sales.php           (manajemen sales)
│   ├── StokSales.php       (input stok ke sales)
│   ├── Distribusi.php      (sales ke warung)
│   ├── Penjualan.php       (input penjualan)
│   ├── Warung.php          (CRUD warung)
│   ├── Supplier.php        (CRUD supplier)
│   ├── Pembelian.php       (pembelian ke supplier)
│   ├── Retur.php           (retur dari warung)
│   ├── Laporan.php         (semua laporan)
│   └── Dashboard.php       (dashboard utama)
├── Models/
│   ├── ProdukModel.php
│   ├── HargaModel.php
│   ├── SalesModel.php
│   ├── StokSalesModel.php
│   ├── DistribusiModel.php
│   ├── PenjualanModel.php
│   ├── WarungModel.php
│   ├── SupplierModel.php
│   ├── PembelianModel.php
│   └── ReturModel.php
├── Views/
│   ├── layout/
│   │   ├── header.php
│   │   ├── sidebar.php
│   │   └── footer.php
│   ├── dashboard/
│   ├── produk/
│   ├── sales/
│   ├── distribusi/
│   ├── penjualan/
│   ├── laporan/
│   └── ...
├── Database/
│   ├── Migrations/        (struktur tabel)
│   └── Seeds/             (data awal)
└── public/
    ├── css/
    ├── js/
    └── images/
```

---

## Pengembangan Tahap
- Manajemen produk & harga
- Input HPP & fee sales
- Distribusi ke warung
- Input penjualan dasar

### Tahap 2
- Laporan harian & mingguan
- Dashboard monitoring
- Analisis produk

### Tahap 3
- Mobile app untuk sales
- Notifikasi otomatis
- Analisis lanjutan
- Integrasi pembayaran

---

## Catatan Penting

1. **HPP bersifat fix** per produk, diinput manual
2. **Fee sales bervariasi** tergantung harga jual yang dipilih
3. **Stok harus realtime** agar tidak terjadi kesalahan distribusi
4. **Sistem konsinyasi**: warung hanya bayar setelah roti terjual
5. **Roti kadaluarsa** harus dicatat sebagai retur/rugi
