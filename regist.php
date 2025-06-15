<?php
    session_start();
    include "koneksi.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['aksiLogin'] === "login") {
        $name = $_POST['name'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $cekUser = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
        if (mysqli_num_rows($cekUser) > 0) {
            $_SESSION['eksekusi'] = "<p class='alert' style='color:red;'>Username sudah digunakan!</p>";
            header("Location: regist.php");
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO user (nama, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $username, $password);
        
        if ($stmt->execute()) {
            $_SESSION['eksekusi'] = "<p class='alert' style='color:rgb(0, 120, 116);'>Silahkan login dengan akun yang baru anda buat!</p>";
            header("Location: login_user.php");
            exit();
        } else {
            echo "Gagal menyimpan ke database";
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
        <h1>BUAT AKUN BARU</h1>
        <?php
            //aktifkan session untuk alert
            if (isset($_SESSION['eksekusi'])) {
                echo $_SESSION['eksekusi'];
                unset($_SESSION['eksekusi']);
            }
        ?>
        <form action="" method="POST" class="form-login">
            <label for="name">Nama</label>
            <input required type="text" name="name" id="name">
            <label for="username">Username</label>
            <input required type="text" name="username" id="username">
            <label for="password">Password</label>
            <input required type="password" name="password" id="password">
            <button type="submit" name="aksiLogin" value="login">Buat</button>
        </form>
    </div>
</body>
</html>