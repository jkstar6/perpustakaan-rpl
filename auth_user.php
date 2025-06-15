<?php
session_start();
include "koneksi.php";

session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'petugas') {
    header("Location: konfirmasi_logout.php?lanjut=user");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['aksiLogin'] === "login") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil user berdasarkan username
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah username ditemukan
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama']; // opsional: untuk ditampilkan
            $_SESSION['role'] = 'user'; // ✅ Ini penting!
            header("Location: daftar_buku.php");
            exit();
        } else {
            $_SESSION['eksekusi'] = "<p class='alert' style='color:red;'>Password salah!</p>";
            header("Location: login_user.php");
            exit();
        }
    } else {
        $_SESSION['eksekusi'] = "<p class='alert' style='color:red;'>Username tidak ditemukan!</p>";
        header("Location: login_user.php");
        exit();
    }
}
?>