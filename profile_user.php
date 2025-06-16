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

$query_pinjam = "SELECT 
        peminjaman.ID_peminjaman, 
        user.username AS username,
        buku.judul AS judul_buku, 
        peminjaman.tanggal_pinjam,
        peminjaman.status_peminjaman
    FROM peminjaman
    JOIN user ON peminjaman.ID_user = user.ID_user
    JOIN buku ON peminjaman.ID_buku = buku.ID_buku
    WHERE user.username = '$username_session' ORDER BY ID_peminjaman ASC";

$sql_pinjam = mysqli_query($conn, $query_pinjam);
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
            <button onclick="window.location.href='logout.php'">Logout</button>
        </div>

        <div class="line"></div>

        <h2>History</h2>
        <div class="table-container">
            <table border="0" cellspacing="0" width="900">
                <thead>
                    <tr>
                        <th style="font-size: 20px;">No</th>
                        <th style="font-size: 20px;">Judul Buku</th>
                        <th style="font-size: 20px;">Tanggal Peminjaman</th>
                        <th width="100" style="font-size: 20px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($sql_pinjam)) { ?>
                        <tr>
                            <td style="text-align: center;"><?= $no++; ?></td>
                            <td style="text-align: center;"><?= htmlspecialchars($row['judul_buku']); ?></td>
                            <td style="text-align: center;"><?= htmlspecialchars($row['tanggal_pinjam']); ?></td>
                            <td style="text-align: center;"><?= htmlspecialchars($row['status_peminjaman']); ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (mysqli_num_rows($sql_pinjam) === 0): ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">Tidak ada peminjaman buku.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
