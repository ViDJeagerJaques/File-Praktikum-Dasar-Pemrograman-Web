<?php
session_start();
require "koneksi.php";
if (isset($_SESSION["admin"])) {
    header("Location: index.php");
    exit;
}
$koneksi->query("CREATE TABLE IF NOT EXISTS `users` (`id` int(11) NOT NULL AUTO_INCREMENT, `username` varchar(100) NOT NULL, `password` varchar(255) NOT NULL, `nama` varchar(150) DEFAULT NULL, `created_at` timestamp NULL DEFAULT current_timestamp(), PRIMARY KEY (`id`), UNIQUE KEY `username` (`username`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
$cek = $koneksi->query("SELECT COUNT(*) AS total FROM `users`");
$jumlah = $cek->fetch_assoc();
if ((int) $jumlah["total"] == 0) {
    $hash = password_hash("admin123", PASSWORD_DEFAULT);
    $stmt = $koneksi->prepare("INSERT INTO `users` (username, password, nama) VALUES (?, ?, ?)");
    $u = "admin";
    $n = "Administrator";
    $stmt->bind_param("sss", $u, $hash, $n);
    $stmt->execute();
    $stmt->close();
}
$pesan = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    if ($username == "" || $password == "") {
        $pesan = "Username dan password wajib diisi.";
    } else {
        $stmt = $koneksi->prepare("SELECT * FROM `users` WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $hasil = $stmt->get_result();
        $user = $hasil->fetch_assoc();
        $stmt->close();
        if ($user && (password_verify($password, $user["password"]) || $password === $user["password"])) {
            $_SESSION["admin"] = $user["username"];
            $_SESSION["nama"] = $user["nama"];
            header("Location: index.php");
            exit;
        } else {
            $pesan = "Username atau password salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin E-Commerce</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body.login-page {
            margin: 0;
            background-color: #f2f5f7;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Helvetica, Arial, sans-serif;
        }
        .login-card {
            width: 420px;
            max-width: 92%;
            background-color: #ffffff;
            border-radius: 10px;
            border-left: 5px solid #05bcd4;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            padding: 28px;
            box-sizing: border-box;
        }
        .login-card h1 {
            margin: 0 0 8px;
            color: #005b72;
            font-size: 24px;
            text-align: center;
        }
        .login-card p {
            margin: 4px 0 20px;
            font-size: 14px;
            color: #65747d;
            text-align: center;
        }
        .login-card label {
            display: block;
            font-size: 14px;
            color: #263238;
            margin: 14px 0 6px;
        }
        .login-card input {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        .login-card button {
            width: 100%;
            background-color: #005b72;
            color: white;
            padding: 11px;
            border: none;
            margin-top: 20px;
            cursor: pointer;
            font-size: 15px;
        }
        .login-pesan {
            background-color: #e8f8fb;
            color: #005b72;
            font-size: 14px;
            padding: 10px;
            margin-bottom: 10px;
            text-align: center;
        }
        .login-info {
            margin-top: 18px;
            font-size: 12px;
            color: #7c8b92;
            text-align: center;
        }
    </style>
</head>
<body class="login-page">
    <div class="login-card">
        <h1>Admin E-Commerce</h1>
        <p>Sistem Administrasi E-Commerce</p>
        <form method="POST" action="login.php">
            <?php if ($pesan != "") { ?>
            <div class="login-pesan"><?php echo htmlspecialchars($pesan); ?></div>
            <?php } ?>
            <label>Username (*)</label>
            <input type="text" name="username" required>
            <label>Password (*)</label>
            <input type="password" name="password" required>
            <button type="submit">[ LOGIN ]</button>
        </form>
        <div class="login-info">Nama: David Atha R | NIM: 250402049 | Kelas: 3A2</div>
    </div>
</body>
</html>
