<?php
include "koneksi.php";

session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
    header("Location: konfirmasi_logout.php?lanjut=petugas");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['aksiLogin'] === "login") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil user berdasarkan username
    $stmt = $conn->prepare("SELECT * FROM petugas WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah username ditemukan
    if ($result->num_rows === 1) {
        $petugas = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $petugas['password'])) {
            $_SESSION['username'] = $petugas['username'];
            $_SESSION['nama'] = $petugas['nama']; // opsional: untuk ditampilkan
            $_SESSION['role'] = 'petugas'; // ✅ Ini penting!
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['eksekusi'] = "<p class='alert' style='color:red;'>Password salah!</p>";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['eksekusi'] = "<p class='alert' style='color:red;'>Username tidak ditemukan!</p>";
        header("Location: login.php");
        exit();
    }
}
?>
