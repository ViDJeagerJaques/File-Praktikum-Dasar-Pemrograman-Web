<?php
require "auth.php";
require "koneksi.php";
$pesan = "";
$kolom = array("kode_metode", "nama_metode", "jenis", "instruksi_pembayaran", "biaya_layanan", "status");
$wajib = array("kode_metode", "nama_metode", "jenis", "status");
$angka = array("biaya_layanan");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = array();
    foreach ($kolom as $nama) {
        $data[$nama] = trim($_POST[$nama] ?? "");
    }
    foreach ($wajib as $nama) {
        if ($data[$nama] === "") {
            $pesan = "Field wajib harus diisi.";
            break;
        }
    }
    if ($pesan == "") {
        foreach ($angka as $nama) {
            if ($data[$nama] !== "" && (!is_numeric($data[$nama]) || $data[$nama] < 0)) {
                $pesan = "Nilai angka harus valid dan tidak boleh negatif.";
                break;
            }
        }
    }
    if ($pesan == "") {
        foreach ($data as $nama => $nilai) {
            if ($nilai === "" && !in_array($nama, $wajib)) {
                $data[$nama] = null;
            }
        }
        $daftarKolom = implode(", ", $kolom);
        $tanda = implode(", ", array_fill(0, count($kolom), "?"));
        $sql = "INSERT INTO `7payment_methods` ($daftarKolom) VALUES ($tanda)";
        try {
            $stmt = $koneksi->prepare($sql);
            $nilai = array_values($data);
            $stmt->bind_param(str_repeat("s", count($nilai)), ...$nilai);
            $stmt->execute();
            $pesan = "Data berhasil disimpan.";
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $pesan = "Data gagal disimpan. Periksa kode unik atau database.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Metode Pembayaran</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard">
    <aside class="sidebar">
        <h2>Admin E-Commerce</h2>
        <nav>
            <a href="index.php">Dashboard</a>
            <a href="tab2.php">Kategori Produk</a>
            <a href="tab3.php">Produk / Barang</a>
            <a href="tab4.php">Merek</a>
            <a href="tab5.php">Satuan</a>
            <a href="tab6.php">Pelanggan</a>
            <a href="tab7.php">Alamat Pengiriman</a>
            <a class="active" href="tab8.php">Metode Pembayaran</a>
            <a href="tab9.php">Kurir / Pengiriman</a>
            <a href="tab10.php">Voucher / Promo</a>
            <a href="tab11.php">Pajak</a>
            <a href="tab12.php">Laporan Order</a>
            <a href="logout.php">Logout</a>
        </nav>
    </aside>
    <div class="dashboard-wrapper">
        <header class="dashboard-header">
            <h1>8.7 MASTER METODE PEMBAYARAN</h1>
            <p>Sistem Administrasi E-Commerce</p>
            <p class="student-info">
                Nama: David Atha R |
                NIM: 250402049 |
                Kelas: 3A2
            </p>
        </header>
        <main class="dashboard-content">
            <section class="dashboard-panel">
    <form action="tab8.php" method="POST">
        <div class="table-container">
        <table class="stock-table">
            <tr>
                <th colspan="2">8.7 MASTER METODE PEMBAYARAN</th>
            </tr>
            <tr>
                <td colspan="2">Lengkapi data berikut. Field bertanda (*) wajib diisi.</td>
            </tr><?php if ($pesan != "") { ?><tr>
                    <td colspan="2"><?php echo htmlspecialchars($pesan); ?></td>
                </tr><?php } ?>
            <tr>
                <td>Kode Metode (*)</td>
                <td><input name="kode_metode" required="" type="text" value="" /></td>
            </tr>
            <tr>
                <td>Nama Metode (*)</td>
                <td><input name="nama_metode" required="" type="text" value="" /></td>
            </tr>
            <tr>
                <td>Jenis (*)</td>
                <td>
                    <select name="jenis">
                        <option>Transfer / VA / E-Wallet</option>
                        <option>DANA</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Instruksi Pembayaran</td>
                <td><textarea name="instruksi_pembayaran" rows="2"></textarea></td>
            </tr>
            <tr>
                <td>Biaya Layanan</td>
                <td><input name="biaya_layanan" oninput="this.value = this.value.replace(/[^0-9]/g, '')" type="text" value="" /></td>
            </tr>
            <tr>
                <td>Status (*)</td>
                <td>
                    <select name="status">
                        <option>Aktif</option>
                        <option>Tidak Aktif</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td  colspan="2" style="text-align:center;">
                    <button type="submit">[ SIMPAN ]</button>
                    <button type="reset">[ RESET ]</button>
                    <button onclick="window.location.href='index.php'" type="button">[ KEMBALI ]</button>
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
