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
            <li class="li-main"><a class="home" href="index.php">Home</a></li>
            <li class="li-main"><a href="daftar_buku.php">Buku</a></li>
            <li class="li-main"><a href="dashboard.php">Petugas</a></li>
        </ul>
    </nav>

    <header>
        <div class="header-left">
            <h1>Perpustakaan<br>Kampoeng Galia</h1>
            <p>your text here</p>
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
        <div class="search">
            <img src="img/search.png" alt="">
            <input type="text">
            <button>SEARCH</button>
        </div>

        <div class="container-main">
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
        </div>
        
        <button onclick="window.location.href='daftar_buku.php'" class="selengkapnya">
            Selengkapnya
        </button>
    </main>

</body>

</html>