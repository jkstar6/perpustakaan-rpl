<?php
session_start();
$lanjut = $_GET['lanjut'] ?? 'user';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Konfirmasi Logout</title>
</head>
<body>
    <div class="container-konfirmasi">
        <h2>Anda masih login sebagai <?= $_SESSION['role'] ?>.</h2>
        <p>Apakah Anda ingin logout untuk melanjutkan login sebagai <strong><?= $lanjut ?></strong>?</p>
        <a class="a-yes" href="logout.php?lanjut=<?= $lanjut ?>">Logout dan Lanjutkan</a> 
        <a class="a-no" href="<?= $_SESSION['role'] === 'petugas' ? 'dashboard.php' : 'daftar_buku.php' ?>">Batal</a>
        
    </div>
</body>
</html>
