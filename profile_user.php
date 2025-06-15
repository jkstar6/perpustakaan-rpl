<?php
session_start();
include "koneksi.php";

// Cek login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    $_SESSION['eksekusi'] = "<p class='alert' style='color:red;'>Silakan login sebagai user terlebih dahulu!</p>";
    header("Location: login_user.php");
    exit();
}

$username_session = $_SESSION['username'];

// Tangani update profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksiLogin']) && $_POST['aksiLogin'] === 'ubah') {
    $namaBaru = trim($_POST['nama']);
    $usernameBaru = trim($_POST['username']);
    $passwordBaru = trim($_POST['password']);

    // Validasi sederhana
    if (empty($namaBaru) || empty($usernameBaru)) {
        $_SESSION['eksekusi'] = "<p class='alert' style='color:red;'>Nama dan Username tidak boleh kosong!</p>";
    } else {
        // Cek apakah password mau diubah
        if (!empty($passwordBaru)) {
            $hashPassword = password_hash($passwordBaru, PASSWORD_DEFAULT);
            $stmtUpdate = $conn->prepare("UPDATE user SET nama = ?, username = ?, password = ? WHERE username = ?");
            $stmtUpdate->bind_param("ssss", $namaBaru, $usernameBaru, $hashPassword, $username_session);
        } else {
            $stmtUpdate = $conn->prepare("UPDATE user SET nama = ?, username = ? WHERE username = ?");
            $stmtUpdate->bind_param("sss", $namaBaru, $usernameBaru, $username_session);
        }

        if ($stmtUpdate->execute()) {
            $_SESSION['username'] = $usernameBaru; // Update session
            $_SESSION['eksekusi'] = "<p class='alert' style='color:green;'>Data berhasil diubah!</p>";
            header("Location: profile_user.php");
            exit();
        } else {
            $_SESSION['eksekusi'] = "<p class='alert' style='color:red;'>Gagal mengubah data.</p>";
        }
    }
}

// Ambil data user untuk ditampilkan
$stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


    <div class="container-profile">
        <button class="back-button" onclick="window.history.back()">
            <img src="img/back.png" alt="">
        </button>
        
        <h1>Profil Pengguna</h1>

        <?php if (isset($_SESSION['eksekusi'])): ?>
            <?= $_SESSION['eksekusi']; unset($_SESSION['eksekusi']); ?>
        <?php endif; ?>

        <form action="" method="POST" class="form-dashboard">
            <div class="dashboard-text">
                <label for="">Nama</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>
                <label for="">Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                <label for="">Password Baru <small>(Kosongkan jika tidak ingin mengubah)</small></label>
                <input type="password" name="password">
            </div>
            <div class="dashboard-ubah">
                <button type="submit" name="aksiLogin" value="ubah">UBAH</button>
            </div>
        </form>

        <div class="line"></div>
        <div class="dash-button">
            <button onclick="window.location.href='buku_edit.php'">Peminjaman</button>
            <button onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </div>
</body>
</html>
