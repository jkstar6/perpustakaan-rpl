<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'petugas') {
    $_SESSION['eksekusi'] = "<p class='alert' style='color: #f20202;'>Silahkan login terlebih dahulu!</p>";
    header("Location: login.php");
    exit();
}

$username_session = $_SESSION['username'];

// Tangani update data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksiLogin']) && $_POST['aksiLogin'] === 'ubah') {
    $namaBaru = trim($_POST['nama']);
    $usernameBaru = trim($_POST['username']);
    $passwordBaru = trim($_POST['password']);

    if (empty($namaBaru) || empty($usernameBaru)) {
        $_SESSION['eksekusi'] = "<p class='alert' style='color:red;'>Nama dan Username tidak boleh kosong!</p>";
    } else {
        if (!empty($passwordBaru)) {
            $hashPassword = password_hash($passwordBaru, PASSWORD_DEFAULT);
            $stmtUpdate = $conn->prepare("UPDATE petugas SET nama = ?, username = ?, password = ? WHERE username = ?");
            $stmtUpdate->bind_param("ssss", $namaBaru, $usernameBaru, $hashPassword, $username_session);
        } else {
            $stmtUpdate = $conn->prepare("UPDATE petugas SET nama = ?, username = ? WHERE username = ?");
            $stmtUpdate->bind_param("sss", $namaBaru, $usernameBaru, $username_session);
        }

        if ($stmtUpdate->execute()) {
            $_SESSION['username'] = $usernameBaru; // perbarui sesi
            $_SESSION['eksekusi'] = "<p class='alert' style='color:green;'>Data berhasil diubah!</p>";
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['eksekusi'] = "<p class='alert' style='color:red;'>Gagal mengubah data.</p>";
        }
    }
}

// Ambil data petugas
$stmt = $conn->prepare("SELECT * FROM petugas WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$petugas = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Perpustakaan</title>
</head>
<body>
    <nav class="nav-side">
        <i><img src="img/logo-nav.png" alt=""></i>
        <ul>
            <li><button class="btn2-1" onclick="window.location.href='index.php'"><img src="img/home.png"> Home</button></li>
            <li><button class="btn2-2" onclick="window.location.href='daftar_buku.php'"><img src="img/books-light.png"> Buku</button></li>
            <li><button class="btn2-3" onclick="window.location.href='dashboard.php'"><img src="img/user-dark.png"> Petugas</button></li>
            <a href="logout.php">↩ Logout</a>
        </ul>
    </nav>

    <div class="container-buku">
        <h1>Dashboard Petugas</h1>

        <?php if (isset($_SESSION['eksekusi'])): ?>
            <?= $_SESSION['eksekusi']; unset($_SESSION['eksekusi']); ?>
        <?php endif; ?>

        <form action="" method="POST" class="form-dashboard">
            <div class="dashboard-text">
                <label for="">Nama</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($petugas['nama']) ?>" required>
                <label for="">Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($petugas['username']) ?>" required>
                <label for="">Password Baru <small>(Kosongkan jika tidak ingin mengubah)</small></label>
                <input type="password" name="password">
            </div>
            <div class="dashboard-ubah">
                <button type="submit" name="aksiLogin" value="ubah">UBAH</button>
            </div>
        </form>

        <div class="line"></div>
        <div class="dash-button">
            <button onclick="window.location.href='tambah_buku.php'">Tambah Buku</button>
            <button onclick="window.location.href='buku_edit.php'">Data Buku</button>
            <button onclick="window.location.href='peminjaman.php'">Peminjaman</button>
            <button onclick="window.location.href='aktivitas.php'">Aktivitas</button>
            <button onclick="window.location.href='tambah_petugas.php'">Tambah Petugas</button>
        </div>
    </div>
</body>
</html>