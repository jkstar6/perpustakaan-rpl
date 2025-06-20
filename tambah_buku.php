<?php
include 'koneksi.php';
session_start();
$username_session = $_SESSION['username'];

if (isset($_POST['aksiBuku'])) {
    if ($_POST['aksiBuku'] == "add") {

        $judul = $_POST['judul'];
        $deskripsi = $_POST['deskripsi'];
        $status = $_POST['status'];

        // Periksa apakah file gambar diupload
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
            //memisahkan nama dan format foto dgn '.'
            $split = explode('.', $_FILES['gambar']['name']);
            //mengambil array terakhir yaitu format
            $ekstensi = strtolower(end($split));
            //menggunakan judul.png utk menghindari nama file sama
            $gambar = $judul . '.' . $ekstensi; //variabel untuk foto

            //direktori penyimpanan
            $dir = "data/";
            //ambil file temporary
            $tmpFile = $_FILES['gambar']['tmp_name'];

            //validasi ekstensi file
            $ekstensiValid = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($ekstensi, $ekstensiValid)) {
                //memindah dari temporary ke directory
                if (move_uploaded_file($tmpFile, $dir . $gambar)) {

                    // Ambil ID_petugas berdasarkan username
                    $queryPetugas = "SELECT ID_petugas FROM petugas WHERE username = ?";
                    $stmtPetugas = mysqli_prepare($conn, $queryPetugas);
                    mysqli_stmt_bind_param($stmtPetugas, "s", $username_session);
                    mysqli_stmt_execute($stmtPetugas);
                    $resultPetugas = mysqli_stmt_get_result($stmtPetugas);

                    if ($rowPetugas = mysqli_fetch_assoc($resultPetugas)) {
                        $ID_petugas = $rowPetugas['ID_petugas'];

                        // Insert data buku dengan ID_petugas
                        $query = "INSERT INTO buku VALUES(NULL, ?, ?, ?, ?, ?)";
                        $stmt = mysqli_prepare($conn, $query);
                        mysqli_stmt_bind_param($stmt, "issss", $ID_petugas, $judul, $deskripsi, $status, $gambar);
                        $sql = mysqli_stmt_execute($stmt);

                        if ($sql) {
                            header("location: tambah_buku.php");
                            exit();
                        } else {
                            echo "Gagal menyimpan ke database.";
                        }
                    } else {
                        echo "Petugas dengan username ini tidak ditemukan.";
                    }
                } else {
                    echo "Gagal upload gambar";
                }
            } else {
                echo "Format file tidak didukung";
            }
        } else {
            echo "Silakan pilih gambar";
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
            <input required type="text" id="judul" name="judul">
            <label for="">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi"></textarea>

            <label for="">Gambar</label>
            <input class="file-input" type="file" id="gambar" name="gambar" accept="image/*">

            <label for="">Status</label>
            <select id="status" name="status">
                <option value="TERSEDIA">Tersedia</option>
                <option value="KOSONG">Kosong</option>
            </select>
            <button class="btnBuku" type="submit" name="aksiBuku" value="add">INPUT</button>
        </form>
    </div>
</body>

</html>