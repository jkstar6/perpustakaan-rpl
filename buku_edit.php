<?php
    include 'koneksi.php';


    if (isset($_GET['delete'])) {
        $ID_buku = $_GET['delete'];

        // Cek status buku dulu
        $cekStmt = $conn->prepare("SELECT status FROM buku WHERE ID_buku = ?");
        $cekStmt->bind_param("i", $ID_buku);
        $cekStmt->execute();
        $cekResult = $cekStmt->get_result();

        if ($cekResult->num_rows > 0) {
            $data = $cekResult->fetch_assoc();

            if (strtolower($data['status']) === 'dipinjam') {
                // Tidak boleh hapus jika status dipinjam
                header("Location: buku_edit.php?error=dipinjam");
                exit();
            } else {
                // Lanjut hapus
                $stmt = $conn->prepare("DELETE FROM buku WHERE ID_buku = ?");
                $stmt->bind_param("i", $ID_buku);
                $stmt->execute();

                header("Location: buku_edit.php?success=deleted");
                exit();
            }
        }
    }

    // Ambil data buku untuk ditampilkan
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
        <h1 class="buku-edit-h1">Manajemen Buku</h1>

        <div class="container-list">
            <?php
              while($result = mysqli_fetch_assoc($sql)) {
            ?>
            <div class="box">
                <?php if (strtolower($result['status']) !== 'dipinjam'): ?>
                    <a href="buku_edit.php?delete=<?= $result['ID_buku']; ?>" onclick="return confirm('Yakin ingin menghapus buku berjudul: <?= addslashes($result['judul']); ?>?');">
                        <button class="btnDelete">
                            <img src="img/delete.png" alt="">
                        </button>
                    </a>
                <?php else: ?>
                    <span class="btnDelete" style="opacity: 0.5; cursor: not-allowed;" title="Tidak bisa dihapus saat sedang dipinjam">
                        <img src="img/delete.png" alt="">
                    </span>
                <?php endif; ?>
                <div class="frame" onclick="window.location.href='detail_edit.php?ID_buku=<?php echo $result['ID_buku'] ?>'">
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