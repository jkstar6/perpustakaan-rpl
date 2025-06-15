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
        <h1>LOGIN PENGUNJUNG</h1>
        <?php
            //aktifkan session untuk alert
            session_start();
            if (isset($_SESSION['eksekusi'])) {
                echo $_SESSION['eksekusi'];
                unset($_SESSION['eksekusi']);
            }
        ?>
        <form action="auth_user.php" method="POST" class="form-login">
            <label for="username">Username</label>
            <input required type="text" name="username" id="username">
            <label for="password">Password</label>
            <input required type="password" name="password" id="password">
            <button type="submit" value="login">login</button>
            <p>Belum punya akun? <a href="regist.php">SignUp</a></p>
        </form>
    </div>
</body>
</html>