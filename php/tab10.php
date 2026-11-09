<?php
require "koneksi.php";
$pesan = "";
$kolom = array("kode_promo", "nama_promo", "jenis_diskon", "nilai_diskon", "minimum_belanja", "maksimum_diskon", "tanggal_mulai", "tanggal_berakhir", "kuota_penggunaan", "status");
$wajib = array("kode_promo", "nama_promo", "jenis_diskon", "nilai_diskon", "tanggal_mulai", "tanggal_berakhir", "status");
$angka = array("nilai_diskon", "minimum_belanja", "maksimum_diskon", "kuota_penggunaan");

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
    if ($pesan == "" && $data["tanggal_berakhir"] < $data["tanggal_mulai"]) {
        $pesan = "Tanggal berakhir harus setelah tanggal mulai.";
    }
    if ($pesan == "") {
        foreach ($data as $nama => $nilai) {
            if ($nilai === "" && !in_array($nama, $wajib)) {
                $data[$nama] = null;
            }
        }
        $daftarKolom = implode(", ", $kolom);
        $tanda = implode(", ", array_fill(0, count($kolom), "?"));
        $sql = "INSERT INTO `9vouchers` ($daftarKolom) VALUES ($tanda)";
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
    <title>Master Voucher / Promo</title>
    <link href="style.css" rel="stylesheet" />
</head>

<body>
    <form action="tab10.php" method="POST">
        <table align="center" border="1" cellpadding="10" cellspacing="0" style="width:95%">
            <tr>
                <th colspan="2">8.9 MASTER VOUCHER / PROMO</th>
            </tr>
            <tr>
                <td colspan="2">Lengkapi data berikut. Field bertanda (*) wajib diisi.</td>
            </tr><?php if ($pesan != "") { ?><tr>
                    <td colspan="2"><?php echo htmlspecialchars($pesan); ?></td>
                </tr><?php } ?>
            <tr>
                <td>Kode Promo (*)</td>
                <td><input name="kode_promo" required="" type="text" value="" /></td>
            </tr>
            <tr>
                <td>Nama Promo (*)</td>
                <td><input name="nama_promo" required="" type="text" value="" /></td>
            </tr>
            <tr>
                <td>Jenis Diskon (*)</td>
                <td>
                    <select name="jenis_diskon" required="">
                        <option>Persentase / Nominal</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Nilai Diskon (*)</td>
                <td><input name="nilai_diskon" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required="" type="number" value="" /></td>
            </tr>
            <tr>
                <td>Minimum Belanja</td>
                <td><input name="minimum_belanja" oninput="this.value = this.value.replace(/[^0-9]/g, '')" type="text" value="" /></td>
            </tr>
            <tr>
                <td>Maksimum Diskon</td>
                <td><input name="maksimum_diskon" oninput="this.value = this.value.replace(/[^0-9]/g, '')" type="text" value="" /></td>
            </tr>
            <tr>
                <td>Tanggal Mulai (*)</td>
                <td><input name="tanggal_mulai" required="" type="date" value="" /></td>
            </tr>
            <tr>
                <td>Tanggal Berakhir (*)</td>
                <td><input name="tanggal_berakhir" required="" type="date" value="" /></td>
            </tr>
            <tr>
                <td>Kuota Penggunaan</td>
                <td><input name="kuota_penggunaan" oninput="this.value = this.value.replace(/[^0-9]/g, '')" type="number" value="" /></td>
            </tr>
            <tr>
                <td>Status (*)</td>
                <td>
                    <select name="status" required="">
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