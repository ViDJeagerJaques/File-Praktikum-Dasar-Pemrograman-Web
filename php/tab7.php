<?php
require "koneksi.php";
$pesan = "";
$kolom = array("pelanggan", "label_alamat", "nama_penerima", "no_telepon", "provinsi", "kota", "kecamatan", "alamat_lengkap", "kode_pos", "alamat_utama");
$wajib = array("pelanggan", "label_alamat", "nama_penerima", "no_telepon", "provinsi", "kota", "alamat_lengkap");
$angka = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = array();
    foreach ($kolom as $nama) {
        $data[$nama] = trim($_POST[$nama] ?? "");
    }
    $data["alamat_utama"] = isset($_POST["alamat_utama"]) ? "1" : "0";
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
        $sql = "INSERT INTO shipping_addresses ($daftarKolom) VALUES ($tanda)";
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
    <title>Master Alamat Pengiriman</title>
    <link href="style.css" rel="stylesheet" />
</head>

<body>
    <form action="tab7.php" method="POST">
        <table align="center" border="1" cellpadding="10" cellspacing="0" style="width:95%">
            <tr>
                <th colspan="2">8.6 MASTER ALAMAT PENGIRIMAN</th>
            </tr>
            <tr>
                <td colspan="2">Lengkapi data berikut. Field bertanda (*) wajib diisi.</td>
            </tr><?php if ($pesan != "") { ?><tr>
                    <td colspan="2"><?php echo htmlspecialchars($pesan); ?></td>
                </tr><?php } ?>
            <tr>
                <td>Pelanggan (*)</td>
                <td>
                    <select name="pelanggan">
                        <option>Andi Saputra</option>
                        <option>Syafroni</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Label Alamat (*)</td>
                <td><input name="label_alamat" required="" type="text" value="" /></td>
            </tr>
            <tr>
                <td>Nama Penerima (*)</td>
                <td><input name="nama_penerima" required="" type="text" value="" /></td>
            </tr>
            <tr>
                <td>Nomor Telepon (*)</td>
                <td><input name="no_telepon" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required="" type="text" value="" /></td>
            </tr>
            <tr>
                <td>Provinsi (*)</td>
                <td>
                    <select name="provinsi">
                        <option>Riau</option>
                        <option>Maluku</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Kota/Kabupaten (*)</td>
                <td>
                    <select name="kota">
                        <option>Pekanbaru</option>
                        <option>Padang</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Kecamatan</td>
                <td>
                    <select name="kecamatan">
                        <option>Marpoyan Damai</option>
                        <option>Tangerang Barat</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Alamat Lengkap (*)</td>
                <td><textarea name="alamat_lengkap" required="" rows="2"></textarea></td>
            </tr>
            <tr>
                <td>Kode Pos</td>
                <td><input name="kode_pos" oninput="this.value = this.value.replace(/[^0-9]/g, '')" type="text" value="" /></td>
            </tr>
            <tr>
                <td>Alamat Utama</td>
                <td><input name="alamat_utama" type="checkbox" value="1" /> Ya</td>
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