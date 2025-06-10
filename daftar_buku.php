<?php
    include 'koneksi.php';

    $query = "SELECT * FROM buku";
    $sql = mysqli_query($conn, $query);
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
        <div class="search">
            <img src="img/search.png" alt="">
            <input type="text">
            <button>SEARCH</button>
        </div>

        <div class="container-list">
            <?php
              while($result = mysqli_fetch_assoc($sql)) {
            ?>
            <div class="box">
                <div class="frame" onclick="window.location.href='detail_buku.php?ID_buku=<?php echo $result['ID_buku'] ?>'">
                    <img src="data/<?php echo $result['gambar']; ?>" alt="">
                </div>
                <p class="title"><?php echo $result['judul']; ?></p>
                <p class="status"><?php echo $result['status']; ?></p>
            </div>
            <?php
              }
            ?>
            <!-- <div class="box">
                <div class="frame">
                    <img src="img/buku.jpg" alt="">
                </div>
                <p class="title">Book Title</p>
                <p class="status">STATUS</p>
            </div>
            <div class="box">
                <div class="frame">
                    <img src="img/buku.jpg" alt="">
                </div>
                <p class="title">Book Title</p>
                <p class="status">STATUS</p>
            </div>
            <div class="box">
                <div class="frame">
                    <img src="img/buku.jpg" alt="">
                </div>
                <p class="title">Book Title</p>
                <p class="status">STATUS</p>
            </div>
            <div class="box">
                <div class="frame">
                    <img src="img/buku.jpg" alt="">
                </div>
                <p class="title">Book Title</p>
                <p class="status">STATUS</p>
            </div>
            <div class="box">
                <div class="frame">
                    <img src="img/buku.jpg" alt="">
                </div>
                <p class="title">Book Title</p>
                <p class="status">STATUS</p>
            </div>
            <div class="box">
                <div class="frame">
                    <img src="img/buku.jpg" alt="">
                </div>
                <p class="title">Book Title</p>
                <p class="status">STATUS</p>
            </div>
            <div class="box">
                <div class="frame">
                    <img src="img/buku.jpg" alt="">
                </div>
                <p class="title">Book Title</p>
                <p class="status">STATUS</p>
            </div>
            <div class="box">
                <div class="frame">
                    <img src="img/buku.jpg" alt="">
                </div>
                <p class="title">Book Title</p>
                <p class="status">STATUS</p>
            </div>
            <div class="box">
                <div class="frame">
                    <img src="img/buku.jpg" alt="">
                </div>
                <p class="title">Book Title</p>
                <p class="status">STATUS</p>
            </div>
            <div class="box">
                <div class="frame">
                    <img src="img/buku.jpg" alt="">
                </div>
                <p class="title">Book Title</p>
                <p class="status">STATUS</p>
            </div>
            <div class="box">
                <div class="frame">
                    <img src="img/buku.jpg" alt="">
                </div>
                <p class="title">Book Title</p>
                <p class="status">STATUS</p>
            </div>
            <div class="box">
                <div class="frame">
                    <img src="img/buku.jpg" alt="">
                </div>
                <p class="title">Book Title</p>
                <p class="status">STATUS</p>
            </div>
            <div class="box">
                <div class="frame">
                    <img src="img/buku.jpg" alt="">
                </div>
                <p class="title">Book Title</p>
                <p class="status">STATUS</p>
            </div> -->
        </div>
    </div>
</body>
</html>