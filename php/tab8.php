<?php
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
        $sql = "INSERT INTO payment_methods ($daftarKolom) VALUES ($tanda)";
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
    <title>Master Metode Pembayaran</title>
    <link href="style.css" rel="stylesheet" />
</head>

<body>
    <form action="tab8.php" method="POST">
        <table align="center" border="1" cellpadding="10" cellspacing="0" style="width:95%">
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