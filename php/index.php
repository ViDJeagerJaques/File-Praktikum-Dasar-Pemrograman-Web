<?php
$judul = "Dashboard E-Commerce";
$namaWebsite = "Sistem Administrasi E-Commerce";
$nama = "David Atha R";
$nim = "250402049";
$kelas = "3A2";

require "koneksi.php";

function hitungData($koneksi, $tabel)
{
    try {
        $hasil = $koneksi->query("SELECT COUNT(*) AS total FROM $tabel");
        $data = $hasil->fetch_assoc();
        return (int) $data["total"];
    } catch (mysqli_sql_exception $e) {
        return null;
    }
}

function tampilJumlah($jumlah)
{
    return $jumlah === null ? "–" : $jumlah;
}

$jumlahKategori = hitungData($koneksi, "categories");
$jumlahProduk = hitungData($koneksi, "products");
$jumlahPelanggan = hitungData($koneksi, "customers");
$jumlahMerek = hitungData($koneksi, "brands");
$jumlahSatuan = hitungData($koneksi, "units");
$jumlahKurir = hitungData($koneksi, "couriers");
$jumlahPembayaran = hitungData($koneksi, "payment_methods");
$jumlahVoucher = hitungData($koneksi, "vouchers");
$jumlahPajak = hitungData($koneksi, "taxes");

$jumlahStokRendah = null;
$produkStokRendah = [];

try {
    $sql = "SELECT nama_barang, stok_awal, batas_stok
            FROM products
            WHERE stok_awal < batas_stok
            ORDER BY stok_awal ASC";

    $hasil = $koneksi->query($sql);

    while ($data = $hasil->fetch_assoc()) {
        $produkStokRendah[] = $data;
    }

    $jumlahStokRendah = count($produkStokRendah);
} catch (mysqli_sql_exception $e) {
    $jumlahStokRendah = null;
}

$koneksi->close();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($judul); ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="dashboard">

    <aside class="sidebar">
        <h2>Admin E-Commerce</h2>

        <nav>
            <a class="active" href="index.php">Dashboard</a>
            <a href="tab2.php">Kategori Produk</a>
            <a href="tab3.php">Produk / Barang</a>
            <a href="tab4.php">Merek</a>
            <a href="tab5.php">Satuan</a>
            <a href="tab6.php">Pelanggan</a>
            <a href="tab7.php">Alamat Pengiriman</a>
            <a href="tab8.php">Metode Pembayaran</a>
            <a href="tab9.php">Kurir / Pengiriman</a>
            <a href="tab10.php">Voucher / Promo</a>
            <a href="tab11.php">Pajak</a>
        </nav>
    </aside>

    <div class="dashboard-wrapper">

        <header class="dashboard-header">
            <h1><?php echo htmlspecialchars($judul); ?></h1>
            <p><?php echo htmlspecialchars($namaWebsite); ?></p>

            <p class="student-info">
                Nama: <?php echo htmlspecialchars($nama); ?> |
                NIM: <?php echo htmlspecialchars($nim); ?> |
                Kelas: <?php echo htmlspecialchars($kelas); ?>
            </p>
        </header>

        <main class="dashboard-content">

            <section class="dashboard-cards">

                <div class="dashboard-card">
                    <div class="card-heading">
                        <h3>Kategori</h3>
                        <span></span>
                    </div>

                    <div class="card-number">
                        <?php echo tampilJumlah($jumlahKategori); ?>
                    </div>

                    <a href="tab2.php">Kelola Kategori</a>
                </div>

                <div class="dashboard-card">
                    <div class="card-heading">
                        <h3>Produk</h3>
                        <span></span>
                    </div>

                    <div class="card-number">
                        <?php echo tampilJumlah($jumlahProduk); ?>
                    </div>

                    <a href="tab3.php">Kelola Produk</a>
                </div>

                <div class="dashboard-card">
                    <div class="card-heading">
                        <h3>Pelanggan</h3>
                        <span></span>
                    </div>

                    <div class="card-number">
                        <?php echo tampilJumlah($jumlahPelanggan); ?>
                    </div>

                    <a href="tab6.php">Kelola Pelanggan</a>
                </div>

                <div class="dashboard-card">
                    <div class="card-heading">
                        <h3>Stok Rendah</h3>
                        <span></span>
                    </div>

                    <div class="card-number">
                        <?php echo tampilJumlah($jumlahStokRendah); ?>
                    </div>

                    <span class="card-note">
                        Produk di bawah batas minimum
                    </span>
                </div>

            </section>

            <section class="dashboard-panel">
                <h2>Status Stok Produk</h2>

                <p>
                    Daftar produk dengan stok di bawah batas minimum
                    akan ditampilkan di sini.
                </p>

                <div class="table-container">
                    <table class="stock-table">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Stok</th>
                                <th>Batas Minimum</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (count($produkStokRendah) > 0) { ?>

                                <?php foreach ($produkStokRendah as $produk) { ?>
                                    <tr>
                                        <td>
                                            <?php echo htmlspecialchars($produk["nama_barang"]); ?>
                                        </td>

                                        <td>
                                            <?php echo (int) $produk["stok_awal"]; ?>
                                        </td>

                                        <td>
                                            <?php echo (int) $produk["batas_stok"]; ?>
                                        </td>

                                        <td>Stok Rendah</td>
                                    </tr>
                                <?php } ?>

                            <?php } else { ?>

                                <tr>
                                    <td colspan="4" class="empty-data">
                                        <?php
                                        echo $jumlahStokRendah === null
                                            ? "Data stok belum dapat dimuat."
                                            : "Tidak ada produk dengan stok rendah.";
                                        ?>
                                    </td>
                                </tr>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="dashboard-panel">
                <h2>Ringkasan Master Lainnya</h2>

                <div class="master-grid">

                    <a class="master-item" href="tab4.php">
                        <span>Merek</span>
                        <strong><?php echo tampilJumlah($jumlahMerek); ?></strong>
                    </a>

                    <a class="master-item" href="tab5.php">
                        <span>Satuan</span>
                        <strong><?php echo tampilJumlah($jumlahSatuan); ?></strong>
                    </a>

                    <a class="master-item" href="tab9.php">
                        <span>Kurir</span>
                        <strong><?php echo tampilJumlah($jumlahKurir); ?></strong>
                    </a>

                    <a class="master-item" href="tab8.php">
                        <span>Pembayaran</span>
                        <strong><?php echo tampilJumlah($jumlahPembayaran); ?></strong>
                    </a>

                    <a class="master-item" href="tab10.php">
                        <span>Voucher</span>
                        <strong><?php echo tampilJumlah($jumlahVoucher); ?></strong>
                    </a>

                    <a class="master-item" href="tab11.php">
                        <span>Pajak</span>
                        <strong><?php echo tampilJumlah($jumlahPajak); ?></strong>
                    </a>

                </div>
            </section>

            <footer class="dashboard-footer">
                Sistem Administrasi E-Commerce |
                <?php echo htmlspecialchars($nama); ?> |
                <?php echo htmlspecialchars($nim); ?>
            </footer>

        </main>
    </div>

</body>

</html>