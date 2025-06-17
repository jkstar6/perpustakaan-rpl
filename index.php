<?php
    include 'koneksi.php';

    $query = "SELECT * FROM buku LIMIT 5";
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
    <nav class="nav-main">
        <a href="index.php">
            <img src="img/logo-nav.png" alt="">
        </a>
        <ul>
            <li class="li-main">
                <a class="home" href="index.php">Home</a>
            </li>
            <li class="li-main">
                <a href="daftar_buku.php">Buku</a>
            </li>
            <li class="li-main">
                <a href="dashboard.php">Petugas</a>
            </li>
            <li class="li-main">
                <a class="profile" href="profile_user.php"><img src="img/user-dark.png" alt=""></a>
            </li>
        </ul>
    </nav>

    <header>
        <div class="header-left">
            <h1>Perpustakaan<br>Kampoeng Galia</h1>
            <p>Platform informasi dan pengelolaan buku untuk mendukung aktivitas literasi komunitas.</p>
            <button onclick="window.location.href='daftar_buku.php'">
                jelajahi
            </button>
        </div>
        <div class="header-right">
            <img src="img/header-image.png" alt="">
        </div>

    </header>

    <main>
        <h2>Pencarian Buku</h2>
        <form class="search" method="GET" action="daftar_buku.php">
            <input type="text" name="keyword" placeholder="Cari judul buku..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
            <button type="submit"><img src="img/search.png" alt=""></button>

            <?php if (isset($_GET['keyword']) && $_GET['keyword'] !== ''): ?>
                <a href="daftar_buku.php" style="margin-left: 10px;">
                    <button class="clear-button" type="button"><img src="img/clear.png" alt=""></button>
                </a>
            <?php endif; ?>
        </form>

        <div class="container-main">
            <?php
              while($result = mysqli_fetch_assoc($sql)) {
            ?>
            <div class="box">
                <div class="frame" onclick="window.location.href='detail_buku.php?ID_buku=<?php echo $result['ID_buku']; ?>'">
                    <img src="data/<?php echo $result['gambar']; ?>" alt="">
                </div>
                <p class="title"><?php echo $result['judul']; ?></p>
                <p class="status"><?php echo $result['status']; ?></p>
            </div>
            <?php
              }
            ?>
        </div>
        
        <button onclick="window.location.href='daftar_buku.php'" class="selengkapnya">
            Selengkapnya
        </button>
    </main>

</body>

</html>