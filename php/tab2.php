<?php
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
<body>

    <form method="POST" action="tab2.php">
        <table border="1" style="width:95%" align="center" cellspacing="0" cellpadding="10">
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
                <td colspan="2" bgcolor="#05e2ff">
                    <button type="submit">[ SIMPAN ]</button>
                    <button type="reset">[ RESET ]</button>
                    <button type="button" onclick="window.location.href='index.php'">
                        [ KEMBALI ]
                    </button>
                </td>
            </tr>
        </table>
    </form>

</body>
</html>
