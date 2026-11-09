<?php
require "koneksi.php";
$pesan = "";
$kolom = array("kode_pelanggan", "nama_lengkap", "email", "no_telepon", "password", "alamat_utama", "status");
$wajib = array("kode_pelanggan", "nama_lengkap", "email", "no_telepon", "status");
$angka = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = array();
    foreach ($kolom as $nama) {
        $data[$nama] = trim($_POST[$nama] ?? "");
    }
    if ($data["password"] != "") {
        $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
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
    if ($pesan == "" && !filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
        $pesan = "Format email tidak valid.";
    }
    if ($pesan == "") {
        foreach ($data as $nama => $nilai) {
            if ($nilai === "" && !in_array($nama, $wajib)) {
                $data[$nama] = null;
            }
        }
        $daftarKolom = implode(", ", $kolom);
        $tanda = implode(", ", array_fill(0, count($kolom), "?"));
        $sql = "INSERT INTO `5customers` ($daftarKolom) VALUES ($tanda)";
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
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Master Pelanggan</title>
    <link href="style.css" rel="stylesheet" />
</head>

<body>
    <form action="tab6.php" method="POST">
        <table align="center" border="1" cellpadding="10" cellspacing="0" style="width:95%">
            <tr>
                <th colspan="2">8.5 MASTER PELANGGAN</th>
            </tr>
            <tr>
                <td colspan="2">Lengkapi data berikut. Field bertanda (*) wajib diisi.</td>
            </tr><?php if ($pesan != "") { ?><tr>
                    <td colspan="2"><?php echo htmlspecialchars($pesan); ?></td>
                </tr><?php } ?>
            <tr>
                <td>Kode Pelanggan (*)</td>
                <td><input name="kode_pelanggan" required="" type="text" value="" /></td>
            </tr>
            <tr>
                <td>Nama Lengkap (*)</td>
                <td><input name="nama_lengkap" required="" type="text" value="" /></td>
            </tr>
            <tr>
                <td>Email (*)</td>
                <td><input name="email" required="" type="email" value="" /></td>
            </tr>
            <tr>
                <td>Nomor Telepon (*)</td>
                <td><input name="no_telepon" required="" type="text" value="" /></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input name="password" type="password" value="" /></td>
            </tr>
            <tr>
                <td>Alamat Utama</td>
                <td><textarea name="alamat_utama" rows="2"></textarea></td>
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
                <td bgcolor="#05e2ff" colspan="2">
                    <button type="submit">[ SIMPAN ]</button>
                    <button type="reset">[ RESET ]</button>
                    <button onclick="window.location.href='index.php'" type="button">[ KEMBALI ]</button>
                </td>
            </tr>
        </table>
    </form>

</body>

</html>