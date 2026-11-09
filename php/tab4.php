<?php
require "koneksi.php";

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_merek = trim($_POST["kode_merek"] ?? "");
    $nama_merek = trim($_POST["nama_merek"] ?? "");
    $keterangan = trim($_POST["keterangan"] ?? "");
    $status = $_POST["status"] ?? "Aktif";

    if ($kode_merek == "" || $nama_merek == "") {
        $pesan = "Kode merek dan nama merek wajib diisi.";
    } elseif (!in_array($status, ["Aktif", "Tidak Aktif"])) {
        $pesan = "Status tidak valid.";
    } else {
        $sql = "INSERT INTO `3brands`
                (kode_merek, nama_merek, keterangan, status)
                VALUES (?, ?, ?, ?)";

        try {
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param(
                "ssss",
                $kode_merek,
                $nama_merek,
                $keterangan,
                $status
            );

            if ($stmt->execute()) {
                $pesan = "Data merek berhasil disimpan.";
            } else {
                $pesan = "Data merek gagal disimpan.";
            }

            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $pesan = "Data gagal disimpan. Periksa apakah kode merek sudah digunakan.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Merek</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <form method="POST" action="tab4.php">
        <table border="1" style="width:95%" align="center" cellspacing="0" cellpadding="10">
            <tr>
                <th colspan="2">8.3 MASTER MEREK</th>
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
                <td>Kode Merek (*)</td>
                <td>
                    <input type="text" name="kode_merek" required>
                </td>
            </tr>
            <tr>
                <td>Nama Merek (*)</td>
                <td>
                    <input type="text" name="nama_merek" required>
                </td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>
                    <textarea name="keterangan" rows="2"></textarea>
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
