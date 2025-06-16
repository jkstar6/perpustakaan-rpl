<?php
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
        <div class="container-detail">
            
            <h2><?php echo htmlspecialchars($buku['judul']); ?></h2>
            <div class="frame-detail">
                <img src="data/<?php echo htmlspecialchars($buku['gambar']); ?>" alt="">
            </div>
            <div class="deskripsi-detail">
                <button class="status-button">
                    <?php echo htmlspecialchars($buku['status']); ?>
                </button>
                <a class="detail_edit" href="ubah_buku.php?ID_buku=<?php echo urlencode($buku['ID_buku']); ?>">
                    EDIT🖍
                </a>
                <p><?php echo htmlspecialchars($buku['deskripsi']); ?></p>
            </div>
            
        </div>
    </div>
</body>
</html>