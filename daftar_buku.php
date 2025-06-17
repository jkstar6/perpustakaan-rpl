<?php
    session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
        $_SESSION['eksekusi'] = "<p class='alert' style='color: #f20202;'>Silahkan login terlebih dahulu!</p>";
        header("Location: login_user.php");
        exit();
    }

    include 'koneksi.php';

    if (isset($_GET['keyword']) && !empty(trim($_GET['keyword']))) {
        $keyword = "%" . mysqli_real_escape_string($conn, $_GET['keyword']) . "%";
        $stmt = $conn->prepare("SELECT * FROM buku WHERE judul LIKE ?");
        $stmt->bind_param("s", $keyword);
        $stmt->execute();
        $sql = $stmt->get_result();
    } else {
        $query = "SELECT * FROM buku";
        $sql = mysqli_query($conn, $query);
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
        <form class="search" method="GET" action="daftar_buku.php">
            <input type="text" name="keyword" placeholder="Cari judul buku..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
            <button type="submit"><img src="img/search.png" alt=""></button>

            <?php if (isset($_GET['keyword']) && $_GET['keyword'] !== ''): ?>
                <a href="daftar_buku.php" style="margin-left: 10px;">
                    <button class="clear-button" type="button"><img src="img/clear.png" alt=""></button>
                </a>
            <?php endif; ?>
        </form>

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
        </div>
    </div>
</body>
</html>