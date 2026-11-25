<?php
$judul = "Laporan Order";
$namaWebsite = "Sistem Administrasi E-Commerce";
$nama = "David Atha R";
$nim = "250402049";
$kelas = "3A2";

require "auth.php";
require "koneksi.php";

if (isset($_GET["hapus"])) {
    $hapusId = (int) $_GET["hapus"];
    if ($hapusId > 0) {
        $stmt = $koneksi->prepare("DELETE FROM `12order_items` WHERE order_id = ?");
        $stmt->bind_param("i", $hapusId);
        $stmt->execute();
        $stmt->close();
        $stmt = $koneksi->prepare("DELETE FROM `13payments` WHERE order_id = ?");
        $stmt->bind_param("i", $hapusId);
        $stmt->execute();
        $stmt->close();
        $stmt = $koneksi->prepare("DELETE FROM `14shipments` WHERE order_id = ?");
        $stmt->bind_param("i", $hapusId);
        $stmt->execute();
        $stmt->close();
        $stmt = $koneksi->prepare("DELETE FROM `11orders` WHERE id = ?");
        $stmt->bind_param("i", $hapusId);
        $stmt->execute();
        $stmt->close();
        header("Location: tab12.php");
        exit;
    }
}

$editOrder = null;
if (isset($_POST["simpan_edit"])) {
    $editId = (int) ($_POST["order_id"] ?? 0);
    $lama = null;
    if ($editId > 0) {
        $stmt = $koneksi->prepare("SELECT o.order_number, o.total_amount, o.status, p.status AS payment_status, s.status AS shipment_status FROM `11orders` o LEFT JOIN `13payments` p ON p.order_id = o.id LEFT JOIN `14shipments` s ON s.order_id = o.id WHERE o.id = ?");
        $stmt->bind_param("i", $editId);
        $stmt->execute();
        $lama = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    }
    if ($lama) {
        $customerId = (int) ($_POST["customer_id"] ?? 0);
        $orderDate = $_POST["order_date"] ?? "";
        $statusOrder = $_POST["status"] ?? "";
        $totalAmount = (float) ($_POST["total_amount"] ?? 0);
        $paymentStatus = $_POST["payment_status"] ?? "";
        $shipmentStatus = $_POST["shipment_status"] ?? "";
        $trackingNumber = $_POST["tracking_number"] ?? "";
        $service = $_POST["service"] ?? "";
        $stmt = $koneksi->prepare("UPDATE `11orders` SET customer_id = ?, order_date = ?, total_amount = ?, status = ? WHERE id = ?");
        $stmt->bind_param("isdsi", $customerId, $orderDate, $totalAmount, $statusOrder, $editId);
        $stmt->execute();
        $stmt->close();
        $stmt = $koneksi->prepare("UPDATE `13payments` SET status = ? WHERE order_id = ?");
        $stmt->bind_param("si", $paymentStatus, $editId);
        $stmt->execute();
        $stmt->close();
        $stmt = $koneksi->prepare("UPDATE `14shipments` SET status = ?, tracking_number = ?, service = ? WHERE order_id = ?");
        $stmt->bind_param("sssi", $shipmentStatus, $trackingNumber, $service, $editId);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: tab12.php");
    exit;
}
if (isset($_POST["tampil_edit"])) {
    $editId = (int) ($_POST["order_id"] ?? 0);
    if ($editId > 0) {
        $stmt = $koneksi->prepare("SELECT o.id, o.order_number, o.customer_id, o.order_date, o.total_amount, o.status, p.status AS payment_status, s.status AS shipment_status, s.tracking_number, s.service FROM `11orders` o LEFT JOIN `13payments` p ON p.order_id = o.id LEFT JOIN `14shipments` s ON s.order_id = o.id WHERE o.id = ?");
        $stmt->bind_param("i", $editId);
        $stmt->execute();
        $editOrder = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    }
}
$pelanggan = [];
$hasilPelanggan = $koneksi->query("SELECT id, nama_lengkap FROM `5customers` ORDER BY nama_lengkap ASC");
while ($data = $hasilPelanggan->fetch_assoc()) {
    $pelanggan[] = $data;
}

$sql1 = "SELECT
            o.id AS order_id,
            o.order_number AS `order number`,
            c.nama_lengkap AS customer,
            o.order_date AS `order date`,
            pm.nama_metode AS `methode Payment`,
            p.amount AS amount,
            p.status AS status,
            p.paid_at AS `payment at`,
            cr.nama_kurir AS courir,
            s.service AS service,
            s.tracking_number AS `tracking number`,
            s.status AS `status pengiriman`
        FROM `11orders` o
        JOIN `5customers` c ON c.id = o.customer_id
        LEFT JOIN `13payments` p ON p.order_id = o.id
        LEFT JOIN `7payment_methods` pm ON pm.id = p.payment_method_id
        LEFT JOIN `14shipments` s ON s.order_id = o.id
        LEFT JOIN `8couriers` cr ON cr.id = s.courier_id
        ORDER BY o.order_date DESC";
$hasil1 = $koneksi->query($sql1);
$daftarOrder = [];
while ($data = $hasil1->fetch_assoc()) {
    $daftarOrder[] = $data;
}

$sql2 = "SELECT
            pr.kategori AS kategori,
            SUM(oi.qty * oi.unit_price - oi.discount) AS total_order
        FROM `12order_items` oi
        JOIN `2products` pr ON pr.id = oi.product_id
        GROUP BY pr.kategori
        ORDER BY total_order DESC";
$hasil2 = $koneksi->query($sql2);
$totalPerKategori = [];
while ($data = $hasil2->fetch_assoc()) {
    $totalPerKategori[] = $data;
}

$sql3 = "SELECT
            c.nama_lengkap AS customer,
            COUNT(o.id) AS jumlah_order,
            SUM(o.total_amount) AS total_order
        FROM `5customers` c
        LEFT JOIN `11orders` o ON o.customer_id = c.id
        GROUP BY c.id, c.nama_lengkap
        ORDER BY total_order DESC";
$hasil3 = $koneksi->query($sql3);
$totalPerCustomer = [];
while ($data = $hasil3->fetch_assoc()) {
    $totalPerCustomer[] = $data;
}

$koneksi->close();

function tampilUang($angka)
{
    if ($angka === null || $angka === "") {
        return "-";
    }
    return "Rp " . number_format((float) $angka, 0, ",", ".");
}

function tampilTeks($teks)
{
    if ($teks === null || $teks === "") {
        return "-";
    }
    return htmlspecialchars($teks);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($judul); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 20px; }
        .modal-box { background: white; border-radius: 10px; width: 100%; max-width: 700px; max-height: 90vh; overflow: auto; padding: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
        .modal-box h2 { margin-top: 0; }
    </style>
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
            <a href="tab8.php">Metode Pembayaran</a>
            <a href="tab9.php">Kurir / Pengiriman</a>
            <a href="tab10.php">Voucher / Promo</a>
            <a href="tab11.php">Pajak</a>
            <a class="active" href="tab12.php">Laporan Order</a>
            <a href="logout.php">Logout</a>
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

            <?php if ($editOrder) { ?>
            <div class="modal-overlay">
            <div class="modal-box">
            <section class="dashboard-panel">
                <h2>Edit Order <?php echo tampilTeks($editOrder["order_number"]); ?></h2>
                <form method="POST" action="tab12.php">
                    <input type="hidden" name="order_id" value="<?php echo (int) $editOrder["id"]; ?>">
                    <div class="table-container">
                    <table class="stock-table">
                        <tr>
                            <td>Pelanggan (*)</td>
                            <td>
                                <select name="customer_id" required>
                                    <?php foreach ($pelanggan as $p) { ?>
                                    <option value="<?php echo (int) $p["id"]; ?>" <?php echo ((int) $p["id"] == (int) $editOrder["customer_id"]) ? "selected" : ""; ?>><?php echo htmlspecialchars($p["nama_lengkap"]); ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal Order (*)</td>
                            <td><input type="datetime-local" name="order_date" value="<?php echo htmlspecialchars(str_replace(" ", "T", $editOrder["order_date"])); ?>" required></td>
                        </tr>
                        <tr>
                            <td>Total (*)</td>
                            <td><input type="number" name="total_amount" value="<?php echo htmlspecialchars($editOrder["total_amount"]); ?>" required></td>
                        </tr>
                        <tr>
                            <td>Status Order (*)</td>
                            <td>
                                <select name="status" required>
                                    <option value="Diproses" <?php echo ($editOrder["status"] == "Diproses") ? "selected" : ""; ?>>Diproses</option>
                                    <option value="Selesai" <?php echo ($editOrder["status"] == "Selesai") ? "selected" : ""; ?>>Selesai</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Status Pembayaran (*)</td>
                            <td>
                                <select name="payment_status" required>
                                    <option value="Pending" <?php echo ($editOrder["payment_status"] == "Pending") ? "selected" : ""; ?>>Pending</option>
                                    <option value="Lunas" <?php echo ($editOrder["payment_status"] == "Lunas") ? "selected" : ""; ?>>Lunas</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Status Pengiriman (*)</td>
                            <td>
                                <select name="shipment_status" required>
                                    <option value="Diproses" <?php echo ($editOrder["shipment_status"] == "Diproses") ? "selected" : ""; ?>>Diproses</option>
                                    <option value="Dikirim" <?php echo ($editOrder["shipment_status"] == "Dikirim") ? "selected" : ""; ?>>Dikirim</option>
                                    <option value="Terkirim" <?php echo ($editOrder["shipment_status"] == "Terkirim") ? "selected" : ""; ?>>Terkirim</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Layanan</td>
                            <td><input type="text" name="service" value="<?php echo htmlspecialchars($editOrder["service"]); ?>"></td>
                        </tr>
                        <tr>
                            <td>Nomor Resi</td>
                            <td><input type="text" name="tracking_number" value="<?php echo htmlspecialchars($editOrder["tracking_number"]); ?>"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:center">
                                <button type="submit" name="simpan_edit">[ SIMPAN ]</button>
                                <a href="tab12.php">[ BATAL ]</a>
                            </td>
                        </tr>
                    </table>
                    </div>
                </form>
            </section>
            </div>
            </div>
            <?php } ?>

            <section class="dashboard-panel">
                <h2>1. Daftar Order Lengkap</h2>
                <p>Gabungan order, customer, pembayaran, dan pengiriman.</p>

                <div class="table-container">
                    <table class="stock-table">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Customer</th>
                                <th>Order Date</th>
                                <th>Methode Payment</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment At</th>
                                <th>Courir</th>
                                <th>Service</th>
                                <th>Tracking Number</th>
                                <th>Status Pengiriman</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (count($daftarOrder) > 0) { ?>
                                <?php foreach ($daftarOrder as $row) { ?>
                                    <tr>
                                        <td><?php echo tampilTeks($row["order number"]); ?></td>
                                        <td><?php echo tampilTeks($row["customer"]); ?></td>
                                        <td><?php echo tampilTeks($row["order date"]); ?></td>
                                        <td><?php echo tampilTeks($row["methode Payment"]); ?></td>
                                        <td><?php echo tampilUang($row["amount"]); ?></td>
                                        <td><?php echo tampilTeks($row["status"]); ?></td>
                                        <td><?php echo tampilTeks($row["payment at"]); ?></td>
                                        <td><?php echo tampilTeks($row["courir"]); ?></td>
                                        <td><?php echo tampilTeks($row["service"]); ?></td>
                                        <td><?php echo tampilTeks($row["tracking number"]); ?></td>
                                        <td><?php echo tampilTeks($row["status pengiriman"]); ?></td>
                                        <td><div style="display:flex; flex-direction:column; gap:6px; align-items:center"><form method="POST" action="tab12.php" style="display:inline"><input type="hidden" name="order_id" value="<?php echo (int) $row["order_id"]; ?>"><button type="submit" name="tampil_edit">Edit</button></form><button type="button" style="background:#c0392b; color:white; border:none; padding:8px 14px; cursor:pointer" onclick="if(confirm('Yakin hapus order <?php echo htmlspecialchars($row["order number"]); ?>? Barang, pembayaran, dan pengirimannya ikut terhapus.')) window.location.href='tab12.php?hapus=<?php echo (int) $row["order_id"]; ?>'">Hapus</button></div></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="12" class="empty-data">Belum ada data order.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="dashboard-panel">
                <h2>2. Total Order Per Kategori</h2>
                <p>Rumus: qty dikali unit_price dikurang discount, lalu dijumlahkan per kategori.</p>

                <div class="table-container">
                    <table class="stock-table">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Total Order</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (count($totalPerKategori) > 0) { ?>
                                <?php foreach ($totalPerKategori as $row) { ?>
                                    <tr>
                                        <td><?php echo tampilTeks($row["kategori"]); ?></td>
                                        <td><?php echo tampilUang($row["total_order"]); ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="2" class="empty-data">Belum ada data order.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="dashboard-panel">
                <h2>3. Total Order Per Customer</h2>
                <p>Jumlah order dan total uang tiap customer.</p>

                <div class="table-container">
                    <table class="stock-table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Jumlah Order</th>
                                <th>Total Order</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (count($totalPerCustomer) > 0) { ?>
                                <?php foreach ($totalPerCustomer as $row) { ?>
                                    <tr>
                                        <td><?php echo tampilTeks($row["customer"]); ?></td>
                                        <td><?php echo (int) $row["jumlah_order"]; ?></td>
                                        <td><?php echo tampilUang($row["total_order"]); ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="3" class="empty-data">Belum ada data customer.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
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
