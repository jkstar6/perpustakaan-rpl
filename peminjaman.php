<?php
    include 'koneksi.php';
    session_start();
    
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'petugas') {
        // Redirect ke halaman login jika tidak sesuai
        $_SESSION['eksekusi'] = "<p class='alert' style='color: #f20202;'>Silahkan login terlebih dahulu!</p>";
        header("Location: login.php");
        exit();
    }

    
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
        <i>
            <img src="img/logo-nav.png" alt="">
        </i>
        <ul>
            <li>
                <button class="btn2-1" onclick="window.location.href='index.php'">
                    <img src="img/home.png" alt="">
                    Home
                </button>
            </li>
            <li>
                <button class="btn2-2" onclick="window.location.href='daftar_buku.php'">
                    <img src="img/books-light.png" alt="">
                    Buku
                </button>
            </li>
            <li>
                <button class="btn2-3" onclick="window.location.href='dashboard.php'">
                    <img src="img/user-dark.png" alt="">
                    Petugas
                </button>
            </li>
            <a href="logout.php">↩ Logout</a>
        </ul>
    </nav>
    <div class="container-buku">
        <button class="back-button" onclick="window.history.back()">
            <img src="img/back.png" alt="">
        </button>

        <h1>Request Peminjaman</h1>
        
</body>
</html>