<?php

    session_start();

    // Cek apakah login sebagai USER (bukan petugas)
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
        // Redirect ke halaman login jika tidak sesuai
        $_SESSION['eksekusi'] = "<p class='alert' style='color: #f20202;'>Silahkan login terlebih dahulu!</p>";
        header("Location: login_user.php");
        exit();
    }
    
    include 'koneksi.php';

    // Validasi ID_buku
    if (!isset($_GET['ID_buku']) || empty($_GET['ID_buku']) || !is_numeric($_GET['ID_buku'])) {
        header("Location: daftar_buku.php?error=invalid_id");
        exit();
    }

    $ID_buku = (int)$_GET['ID_buku']; // Konversi ke integer untuk keamanan

    // Query dengan prepared statement
    $query = "SELECT * FROM buku WHERE ID_buku = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $ID_buku);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        header("Location: daftar_buku.php?error=book_not_found");
        exit();
    }

    $buku = mysqli_fetch_assoc($result);

    $username_session = $_SESSION['username'];
    $query = "SELECT * FROM user WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username_session);
    mysqli_stmt_execute($stmt);
    $result_user = mysqli_stmt_get_result($stmt);
    $user_data = mysqli_fetch_assoc($result_user);

    $tanggal_sekarang = date('Y-m-d');

    if (isset($_POST['aksiReq'])) {
        $ID_buku = $buku['ID_buku'];
        $ID_user = $user_data['ID_user']; // Fix
        $tanggal_pinjam = $tanggal_sekarang;
        $status_peminjaman = "request";

        // 1. Cek apakah status buku TERSEDIA
        if ($buku['status'] !== 'TERSEDIA') {
            echo "<script>alert('Buku tidak tersedia untuk dipinjam');window.location.href='daftar_buku.php';</script>";
            exit();
        }
        
        // 2. Cek apakah user sudah punya peminjaman aktif untuk buku ini
        $cek_query = "SELECT * FROM peminjaman 
                    WHERE ID_buku = ? AND ID_user = ? 
                    AND status_peminjaman IN ('request', 'dipinjam')";
        $cek_stmt = mysqli_prepare($conn, $cek_query);
        mysqli_stmt_bind_param($cek_stmt, "ii", $ID_buku, $ID_user);
        mysqli_stmt_execute($cek_stmt);
        $cek_result = mysqli_stmt_get_result($cek_stmt);

        if (mysqli_num_rows($cek_result) > 0) {
            echo "<script>alert('Anda sudah mengajukan atau sedang meminjam buku ini');window.location.href='daftar_buku.php';</script>";
            exit();
        }

        // 3. Lanjutkan INSERT jika lolos semua validasi
        $query = "INSERT INTO peminjaman (ID_petugas, ID_buku, ID_user, tanggal_pinjam, status_peminjaman)
                VALUES (NULL, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iiss", $ID_buku, $ID_user, $tanggal_pinjam, $status_peminjaman);
        $sql = mysqli_stmt_execute($stmt);

        if ($sql) {
            echo "<script>alert('Permintaan peminjaman berhasil dikirim');window.location.href='daftar_buku.php';</script>";
            exit();
        } else {
            echo "Gagal menyimpan ke database: " . mysqli_error($conn);
        }
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
                <button class="btn-1" onclick="window.location.href='index.php'">
                    <img src="img/home.png" alt="">
                    Home
                </button>
            </li>
            <li>
                <button class="btn-2" onclick="window.location.href='daftar_buku.php'">
                    <img src="img/books-dark.png" alt="">
                    Buku
                </button>
            </li>
            <li>
                <button class="btn-3" onclick="window.location.href='dashboard.php'">
                    <img src="img/user-light.png" alt="">
                    Petugas
                </button>
            </li>
        </ul>
    </nav>

    <div class="container-buku">
        <button class="back-button" onclick="window.history.back()">
            <img src="img/back.png" alt="">
        </button>
        <div class="container-detail">
            
            <h2><?php echo htmlspecialchars($buku['judul']); ?></h2>
            <div class="frame-detail">
                <img src="data/<?php echo htmlspecialchars($buku['gambar']); ?>" alt="">
            </div>
            <div class="deskripsi-detail">
                <button class="status-button"><?php echo htmlspecialchars($buku['status']); ?></button>
                <form method="post">
                    <button type="submit" name="aksiReq" class="req-button">PINJAM</button>
                </form>
                <p><?php echo htmlspecialchars($buku['deskripsi']); ?></p>
            </div>
            
        </div>
    </div>
</body>
</html>