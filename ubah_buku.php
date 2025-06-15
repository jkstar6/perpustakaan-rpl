<?php
    include 'koneksi.php';

    // Validasi ID_buku
    if (!isset($_GET['ID_buku']) || empty($_GET['ID_buku']) || !is_numeric($_GET['ID_buku'])) {
        header("Location: detail_edit.php?error=invalid_id");
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
        header("Location: detail_edit.php?error=book_not_found");
        exit();
    }

    $buku = mysqli_fetch_assoc($result);

    $gambar = htmlspecialchars($buku['gambar']);
    if (isset($_POST['aksiBuku'])) { //tambah lab
        if ($_POST['aksiBuku'] == "ubah") {

            $judul = $_POST['judul'];
            $deskripsi = $_POST['deskripsi'];
            $status = $_POST['status'];
            $query = "UPDATE buku SET judul = ?, deskripsi = ?, status = ?, gambar = ? WHERE ID_buku = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssssi", $judul, $deskripsi, $status, $gambar, $ID_buku);
            $sql = mysqli_stmt_execute($stmt);

            if ($sql) {
                header("location: detail_edit.php?ID_buku=$ID_buku");
                exit();
            } else {
                echo "Gagal menyimpan ke database";
            }

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

        <h1>Masukkan Data Buku</h1>
        <form action="" method="POST" enctype="multipart/form-data" class="input-buku">
            <label for="">Judul</label>
            <input required type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($buku['judul']); ?>">
            <label for="">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi"><?php echo htmlspecialchars($buku['deskripsi']); ?></textarea>

            <label for="">Status</label>
            <select id="status" name="status">
                <option <?php if(htmlspecialchars($buku['status']) == 'TERSEDIA') {echo "selected"; } ?> value="TERSEDIA">
                    Tersedia
                </option>
                <option <?php if(htmlspecialchars($buku['status']) == 'KOSONG') {echo "selected"; } ?> value="KOSONG">
                    Kosong
                </option>
            </select>
            <button class="btnBuku" type="submit" name="aksiBuku" value="ubah">UBAH</button>
        </form>
    </div>
</body>

</html>