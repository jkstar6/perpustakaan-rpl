<?php
session_start();
$lanjut = $_GET['lanjut'] ?? 'user';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Logout</title>
</head>
<body>
    <h2>Anda masih login sebagai <?= $_SESSION['role'] ?>.</h2>
    <p>Apakah Anda ingin logout untuk melanjutkan login sebagai <strong><?= $lanjut ?></strong>?</p>
    <a href="logout.php?lanjut=<?= $lanjut ?>">Logout dan Lanjutkan</a> |
    <a href="<?= $_SESSION['role'] === 'petugas' ? 'dashboard.php' : 'daftar_buku.php' ?>">Batal</a>
</body>
</html>
