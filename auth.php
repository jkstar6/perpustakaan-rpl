<?php
session_start();
include "koneksi.php";

session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
    header("Location: konfirmasi_logout.php?lanjut=petugas");
    exit();
}


// Cek jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // ✅ Gunakan prepared statement
    $stmt = $conn->prepare("SELECT * FROM petugas WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // ✅ Login berhasil
    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'petugas';
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['eksekusi'] = "<p class='alert' style='background:red; color:white;'>Login gagal!<br>Periksa kembali username atau password!</p>";
        header("Location: login.php");
        exit();
    }
}
?>
