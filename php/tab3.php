<?php
require "koneksi.php";

$pesan = "";
$berhasil = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_barang = trim($_POST["kode_barang"] ?? "");
    $nama_barang = trim($_POST["nama_barang"] ?? "");
    $kategori = trim($_POST["kategori"] ?? "");
    $merek = trim($_POST["merek"] ?? "");
    $harga_jual = trim($_POST["harga_jual"] ?? "");
    $harga_modal = trim($_POST["harga_modal"] ?? "");
    $stok_awal = trim($_POST["stok_awal"] ?? "");
    $batas_stok = trim($_POST["batas_stok"] ?? "");
    $berat = trim($_POST["berat"] ?? "");
    $deskripsi = trim($_POST["deskripsi"] ?? "");
    $status = $_POST["status"] ?? "Aktif";
    $foto_barang = "";

    if ($kode_barang == "" || $nama_barang == "" || $kategori == "" ||
        $harga_jual == "" || $stok_awal == "") {
        $pesan = "Field wajib harus diisi.";
    } elseif (
        !is_numeric($harga_jual) ||
        ($harga_modal != "" && !is_numeric($harga_modal)) ||
        filter_var($stok_awal, FILTER_VALIDATE_INT) === false ||
        ($batas_stok != "" && filter_var($batas_stok, FILTER_VALIDATE_INT) === false) ||
        ($berat != "" && filter_var($berat, FILTER_VALIDATE_INT) === false)
    ) {
        $pesan = "Harga, stok, dan berat harus berupa angka.";
    } elseif (
        $harga_jual < 0 || $harga_modal < 0 ||
        $stok_awal < 0 || $batas_stok < 0 || $berat < 0
    ) {
        $pesan = "Harga, stok, dan berat tidak boleh negatif.";
    } elseif (!in_array($status, ["Aktif", "Tidak Aktif"])) {
        $pesan = "Status tidak valid.";
    } else {
        $harga_jual = (float) $harga_jual;
        $harga_modal = $harga_modal === "" ? null : (float) $harga_modal;
        $stok_awal = (int) $stok_awal;
        $batas_stok = $batas_stok === "" ? 0 : (int) $batas_stok;
        $berat = $berat === "" ? null : (int) $berat;

        if (isset($_FILES["foto_barang"]) &&
            $_FILES["foto_barang"]["error"] != UPLOAD_ERR_NO_FILE) {

            if ($_FILES["foto_barang"]["error"] != UPLOAD_ERR_OK) {
                $pesan = "Upload foto gagal.";
            } elseif ($_FILES["foto_barang"]["size"] > 2097152) {
                $pesan = "Ukuran foto maksimal 2 MB.";
            } else {
                $tipe = mime_content_type($_FILES["foto_barang"]["tmp_name"]);

                $ekstensi = [
                    "image/jpeg" => "jpg",
                    "image/png" => "png",
                    "image/webp" => "webp"
                ];

                if (!isset($ekstensi[$tipe])) {
                    $pesan = "Foto harus berformat JPG, PNG, atau WEBP.";
                } else {
                    $folder = __DIR__ . "/uploads";

                    if (!is_dir($folder)) {
                        mkdir($folder, 0755, true);
                    }

                    $nama_foto = bin2hex(random_bytes(12)) . "." . $ekstensi[$tipe];
                    $tujuan = $folder . "/" . $nama_foto;

                    if (move_uploaded_file($_FILES["foto_barang"]["tmp_name"], $tujuan)) {
                        $foto_barang = "uploads/" . $nama_foto;
                    } else {
                        $pesan = "Foto gagal disimpan.";
                    }
                }
            }
        }

        if ($pesan == "") {
            $sql = "INSERT INTO `2products`
                    (kode_barang, nama_barang, kategori, merek,
                     harga_jual, harga_modal, stok_awal, batas_stok,
                     berat, foto_barang, deskripsi, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            try {
                $stmt = $koneksi->prepare($sql);

                $stmt->bind_param(
                    "ssssddiiisss",
                    $kode_barang,
                    $nama_barang,
                    $kategori,
                    $merek,
                    $harga_jual,
                    $harga_modal,
                    $stok_awal,
                    $batas_stok,
                    $berat,
                    $foto_barang,
                    $deskripsi,
                    $status
                );

                if ($stmt->execute()) {
                    $pesan = "Data produk berhasil disimpan.";
                    $berhasil = true;
                } else {
                    $pesan = "Data produk gagal disimpan.";
                }

                $stmt->close();
            } catch (mysqli_sql_exception $e) {
                $pesan = "Data gagal disimpan. Periksa apakah SKU sudah digunakan.";
            }

            if (!$berhasil && $foto_barang != "") {
                unlink(__DIR__ . "/" . $foto_barang);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Produk / Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <form method="POST" action="tab3.php" enctype="multipart/form-data">
        <table border="1" style="width:95%" align="center" cellspacing="0" cellpadding="10">
            <tr>
                <th colspan="2">8.2 MASTER PRODUK / BARANG</th>
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
                <td>Kode Barang / SKU (*)</td>
                <td><input type="text" name="kode_barang" required></td>
            </tr>
            <tr>
                <td>Nama Barang (*)</td>
                <td><input type="text" name="nama_barang" required></td>
            </tr>
            <tr>
                <td>Kategori (*)</td>
                <td>
                    <select name="kategori" required>
                        <option>Aksesori Komputer</option>
                        <option>Lainnya..</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Merek</td>
                <td><input type="text" name="merek"></td>
            </tr>
            <tr>
                <td>Harga Jual (*)</td>
                <td><input type="text" name="harga_jual" required></td>
            </tr>
            <tr>
                <td>Harga Modal</td>
                <td><input type="text" name="harga_modal"></td>
            </tr>
            <tr>
                <td>Stok Awal (*)</td>
                <td><input type="number" name="stok_awal" min="0" required></td>
            </tr>
            <tr>
                <td>Batas Stok Minimum</td>
                <td><input type="number" name="batas_stok" min="0"></td>
            </tr>
            <tr>
                <td>Berat (gram)</td>
                <td><input type="number" name="berat" min="0"></td>
            </tr>
            <tr>
                <td>Foto Barang</td>
                <td>
                    <input type="file" name="foto_barang" accept=".jpg,.jpeg,.png,.webp">
                </td>
            </tr>
            <tr>
                <td>Deskripsi</td>
                <td><textarea name="deskripsi" rows="2"></textarea></td>
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
