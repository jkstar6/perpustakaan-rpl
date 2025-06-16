<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'petugas') {
    $_SESSION['eksekusi'] = "<p class='alert' style='color:red;'>Silakan login sebagai petugas.</p>";
    header("Location: login.php");
    exit();
}

if (isset($_POST['terima'])) {
    $id_peminjaman = $_POST['id_peminjaman'];

    // Ambil ID petugas dari session
    $username_session = $_SESSION['username'];
    $query_petugas = "SELECT ID_petugas FROM petugas WHERE username = ?";
    $stmt_petugas = mysqli_prepare($conn, $query_petugas);
    mysqli_stmt_bind_param($stmt_petugas, "s", $username_session);
    mysqli_stmt_execute($stmt_petugas);
    $result_petugas = mysqli_stmt_get_result($stmt_petugas);
    $petugas = mysqli_fetch_assoc($result_petugas);

    if ($petugas) {
        $id_petugas = $petugas['ID_petugas'];

        // Update status peminjaman dan isi id_petugas
        $query_update = "UPDATE peminjaman SET status_peminjaman = 'dipinjam', ID_petugas = ? WHERE ID_peminjaman = ? AND status_peminjaman = 'request'";
        $stmt_update = mysqli_prepare($conn, $query_update);
        mysqli_stmt_bind_param($stmt_update, "ii", $id_petugas, $id_peminjaman);
        mysqli_stmt_execute($stmt_update);

        if (mysqli_stmt_affected_rows($stmt_update) > 0) {
            $_SESSION['eksekusi'] = "<p class='alert' style='color:green;'>Peminjaman telah diterima.</p>";
        } else {
            $_SESSION['eksekusi'] = "<p class='alert' style='color:red;'>Gagal menerima peminjaman atau sudah diproses.</p>";
        }
    }
}

header("Location: peminjaman.php");
exit();
