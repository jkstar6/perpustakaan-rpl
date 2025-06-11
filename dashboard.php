<?php
    include 'koneksi.php';
    session_start();
    if (empty($_SESSION['username']) || $_SESSION['status'] !== 'login') {
        $_SESSION['eksekusi'] = "<p class='alert' style='color: #f20202;'>Silahkan login terlebih dahulu!</p>";
        header("location: login.php");
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
        </ul>
    </nav>
    <div class="container-buku">
        <h1>Dashboard</h1>
        <form action="" class="form-dashboard">
            <div class="dashboard-text">
                <label for="">Nama</label>
                <input type="text">
                <label for="">Username</label>
                <input type="text">
                <label for="">Password</label>
                <input type="text">
            </div>
            <div class="dashboard-ubah">
                <button>UBAH</button>
            </div>
        </form>
        <div class="line"></div>
        <div class="dash-button">
            <button onclick="window.location.href='tambah_buku.php'">Tambah Buku</button>
            <button onclick="window.location.href='buku-edit.php'">Data Buku</button>
            <button onclick="window.location.href='aktivitas.php'">Aktivitas</button>
            <button onclick="window.location.href='tammbah_petugas.php'">Tambah Petugas</button>
        </div>
    </div>
</body>
</html>