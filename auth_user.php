<?php
    session_start();

    $conn = new mysqli('localhost', 'root', '', 'perpustakaan_rpl');

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Gunakan prepared statement
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        $_SESSION['status'] = "login";
        header("location: dashboard.php");
        exit();
    } else {
        $_SESSION['eksekusi'] = "<p class='alert' style='background:red; color:white;'>Login gagal!<br>Periksa kembali username atau password!</p>";
        header("location: login.php");
        exit();
    }
?>