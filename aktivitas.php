<?php
    include 'koneksi.php';
    session_start();
    
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'petugas') {
        // Redirect ke halaman login jika tidak sesuai
        $_SESSION['eksekusi'] = "<p class='alert' style='color: #f20202;'>Silahkan login terlebih dahulu!</p>";
        header("Location: login.php");
        exit();
    }

    $query = "SELECT 
            peminjaman.ID_peminjaman, 
            user.nama AS nama_user, 
            buku.judul AS judul_buku, 
            peminjaman.tanggal_pinjam,
            peminjaman.status_peminjaman
        FROM peminjaman
        JOIN user ON peminjaman.ID_user = user.ID_user
        JOIN buku ON peminjaman.ID_buku = buku.ID_buku
        WHERE peminjaman.status_peminjaman = 'dipinjam'";

    $sql = mysqli_query($conn, $query);

    $query_pengembalian = "SELECT 
                        pengembalian.ID_pengembalian, 
                        user.nama AS nama_user, 
                        buku.judul AS judul_buku, 
                        peminjaman.status_peminjaman AS status_buku,
                        pengembalian.tanggal_kembali 
                    FROM pengembalian 
                    JOIN user ON pengembalian.ID_user = user.ID_user 
                    JOIN peminjaman ON pengembalian.ID_peminjaman = peminjaman.ID_peminjaman
                    JOIN buku ON peminjaman.ID_buku = buku.ID_buku";
    
    $sql_pengembalian = mysqli_query($conn, $query_pengembalian);
    
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
        <button class="back-button" onclick="window.history.back()">
            <img src="img/back.png" alt="">
        </button>

        <h1>Aktivitas</h1>
        <h2>Riwayat Peminjaman</h2>
        <div class="table-container">
            <table border="0" cellpadding="100" cellspacing="10" width="900">
                <thead>
                    <tr>
                        <th style="font-size: 20px;">No</th>
                        <th style="font-size: 20px;">Judul Buku</th>
                        <th style="font-size: 20px;">Nama Peminjam</th>
                        <th style="font-size: 20px;">Tanggal Peminjaman</th>
                        <th style="font-size: 20px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($sql)) { ?>
                        <tr>
                            <td style="text-align: center;"><?= $no++; ?></td>
                            <td style="text-align: center;"><?= htmlspecialchars($row['judul_buku']); ?></td>
                            <td style="text-align: center;"><?= htmlspecialchars($row['nama_user']); ?></td>
                            <td style="text-align: center;"><?= htmlspecialchars($row['tanggal_pinjam']); ?></td>
                            <td style="text-align: center; ">
                                <?= htmlspecialchars($row['status_peminjaman']); ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (mysqli_num_rows($sql) === 0): ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">Tidak ada request peminjaman.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h2>Riwayat Pengembalian</h2>
        <div class="table-container">
            <table border="0" cellpadding="100" cellspacing="10" width="900">
                <thead>
                    <tr>
                        <th style="font-size: 20px;">No</th>
                        <th style="font-size: 20px;">Judul Buku</th>
                        <th style="font-size: 20px;">Nama Peminjam</th>
                        <th style="font-size: 20px;">Tanggal Pengembalian</th>
                        <th style="font-size: 20px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($sql_pengembalian)) { ?>
                        <tr>
                            <td style="text-align: center;"><?= $no++; ?></td>
                            <td style="text-align: center;"><?= htmlspecialchars($row['judul_buku']); ?></td>
                            <td style="text-align: center;"><?= htmlspecialchars($row['nama_user']); ?></td>
                            <td style="text-align: center;"><?= htmlspecialchars($row['tanggal_kembali']); ?></td>
                            <td style="text-align: center; ">
                                <?= htmlspecialchars($row['status_buku']); ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (mysqli_num_rows($sql_pengembalian) === 0): ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">Tidak ada request peminjaman.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>