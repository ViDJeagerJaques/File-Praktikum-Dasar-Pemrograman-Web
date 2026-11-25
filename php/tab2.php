<?php
require "auth.php";
require "koneksi.php";

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_kategori = trim($_POST["kode_kategori"]);
    $nama_kategori = trim($_POST["nama_kategori"]);
    $kategori_induk = $_POST["kategori_induk"];
    $deskripsi = trim($_POST["deskripsi"]);
    $status = $_POST["status"];

    if ($kode_kategori == "" || $nama_kategori == "") {
        $pesan = "Kode kategori dan nama kategori wajib diisi.";
    } else {
        $sql = "INSERT INTO `1categories`
                (kode_kategori, nama_kategori, kategori_induk, deskripsi, status)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param(
            "sssss",
            $kode_kategori,
            $nama_kategori,
            $kategori_induk,
            $deskripsi,
            $status
        );

        if ($stmt->execute()) {
            $pesan = "Data kategori berhasil disimpan.";
        } else {
            $pesan = "Data gagal disimpan. Periksa kode kategori.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Kategori Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard">
    <aside class="sidebar">
        <h2>Admin E-Commerce</h2>
        <nav>
            <a href="index.php">Dashboard</a>
            <a class="active" href="tab2.php">Kategori Produk</a>
            <a href="tab3.php">Produk / Barang</a>
            <a href="tab4.php">Merek</a>
            <a href="tab5.php">Satuan</a>
            <a href="tab6.php">Pelanggan</a>
            <a href="tab7.php">Alamat Pengiriman</a>
            <a href="tab8.php">Metode Pembayaran</a>
            <a href="tab9.php">Kurir / Pengiriman</a>
            <a href="tab10.php">Voucher / Promo</a>
            <a href="tab11.php">Pajak</a>
            <a href="tab12.php">Laporan Order</a>
            <a href="logout.php">Logout</a>
        </nav>
    </aside>
    <div class="dashboard-wrapper">
        <header class="dashboard-header">
            <h1>8.1 MASTER KATEGORI PRODUK</h1>
            <p>Sistem Administrasi E-Commerce</p>
            <p class="student-info">
                Nama: David Atha R |
                NIM: 250402049 |
                Kelas: 3A2
            </p>
        </header>
        <main class="dashboard-content">
            <section class="dashboard-panel">
    <form method="POST" action="tab2.php">
        <div class="table-container">
        <table class="stock-table">
            <tr>
                <th colspan="2">8.1 MASTER KATEGORI PRODUK</th>
            </tr>
            <tr>
                <td colspan="2">Lengkapi data berikut. Field bertanda (*) wajib diisi.</td>
            </tr>

            <?php if ($pesan != "") { ?>
            <tr>
                <td colspan="2">
                    <?php echo htmlspecialchars($pesan); ?>
                </td>
            </tr>
            <?php } ?>

            <tr>
                <td>Kode Kategori (*)</td>
                <td>
                    <input type="text" name="kode_kategori" required>
                </td>
            </tr>
            <tr>
                <td>Nama Kategori (*)</td>
                <td>
                    <input type="text" name="nama_kategori" required>
                </td>
            </tr>
            <tr>
                <td>Kategori Induk</td>
                <td>
                    <select name="kategori_induk">
                        <option>Elektronik</option>
                        <option>Lainnya...</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Deskripsi</td>
                <td>
                    <textarea name="deskripsi" rows="2"></textarea>
                </td>
            </tr>
            <tr>
                <td>Status (*)</td>
                <td>
                    <select name="status" required>
                        <option>Aktif</option>
                        <option>Tidak Aktif</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center;">
                    <button type="submit">[ SIMPAN ]</button>
                    <button type="reset">[ RESET ]</button>
                    <button type="button" onclick="window.location.href='index.php'">
                        [ KEMBALI ]
                    </button>
                </td>
            </tr>
        </table>
        </div>
    </form>
    <p style="text-align:center;"><a href="tab12.php">Lihat Laporan Order</a> | <a href="index.php">Kembali ke Dashboard</a></p>
            </section>
            
        </main>
    </div>
</body>
</html>
