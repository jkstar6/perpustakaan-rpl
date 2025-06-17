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
                // Ambil ID_buku yang berkaitan
                $query_buku = "SELECT ID_buku FROM peminjaman WHERE ID_peminjaman = ?";
                $stmt_buku = mysqli_prepare($conn, $query_buku);
                mysqli_stmt_bind_param($stmt_buku, "i", $id_peminjaman);
                mysqli_stmt_execute($stmt_buku);
                $result_buku = mysqli_stmt_get_result($stmt_buku);
                $data_buku = mysqli_fetch_assoc($result_buku);

                if ($data_buku) {
                    $id_buku = $data_buku['ID_buku'];

                    // Update status buku menjadi DIPINJAM
                    $update_buku = "UPDATE buku SET status = 'DIPINJAM' WHERE ID_buku = ?";
                    $stmt_update_buku = mysqli_prepare($conn, $update_buku);
                    mysqli_stmt_bind_param($stmt_update_buku, "i", $id_buku);
                    mysqli_stmt_execute($stmt_update_buku);
                }
                $_SESSION['eksekusi'] = "<p class='alert' style='color:green;'>Peminjaman telah diterima.</p>";
                header("Location: peminjaman.php");
                exit();
            } else {
                $_SESSION['eksekusi'] = "<p class='alert' style='color:red;'>Gagal menerima peminjaman atau sudah diproses.</p>";
                header("Location: peminjaman.php");
                exit();
            }
        }
    }

    if (isset($_POST['tolak'])) {
        $id = $_POST['id_peminjaman'];
        $query = "DELETE FROM peminjaman WHERE ID_peminjaman = '$id'";

        if (mysqli_query($conn, $query)) {
            $_SESSION['eksekusi'] = "<p class='alert' style='color: green;'>Peminjaman ditolak dan dihapus.</p>";
        } else {
            $_SESSION['eksekusi'] = "<p class='alert' style='color: red;'>Gagal menolak peminjaman.</p>";
        }

        header("Location: peminjaman.php");
        exit();
    }

    // Dikembalikan
    if (isset($_POST['dikembalikan'])) {
        $id_peminjaman = $_POST['id_peminjaman'];
        $username_petugas = $_SESSION['username'];

        // Ambil ID_petugas berdasarkan username
        $query_petugas = "SELECT ID_petugas FROM petugas WHERE username = ?";
        $stmt_petugas = mysqli_prepare($conn, $query_petugas);
        mysqli_stmt_bind_param($stmt_petugas, "s", $username_petugas);
        mysqli_stmt_execute($stmt_petugas);
        $result_petugas = mysqli_stmt_get_result($stmt_petugas);
        $data_petugas = mysqli_fetch_assoc($result_petugas);

        if (!$data_petugas) {
            echo "ID petugas tidak ditemukan.";
            exit();
        }

        $id_petugas = $data_petugas['ID_petugas'];

        // Ambil ID_user dari tabel peminjaman
        $query_user = "SELECT ID_user FROM peminjaman WHERE ID_peminjaman = ?";
        $stmt_user = mysqli_prepare($conn, $query_user);
        mysqli_stmt_bind_param($stmt_user, "i", $id_peminjaman);
        mysqli_stmt_execute($stmt_user);
        $result_user = mysqli_stmt_get_result($stmt_user);
        $data_user = mysqli_fetch_assoc($result_user);

        if (!$data_user) {
            echo "ID user tidak ditemukan.";
            exit();
        }

        $id_user = $data_user['ID_user'];
        $tanggal_kembali = date("Y-m-d");

        // Insert ke tabel pengembalian (dengan ID_user)
        $insert = "INSERT INTO pengembalian (ID_peminjaman, ID_user, ID_petugas, tanggal_kembali) 
                VALUES (?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $insert);
        mysqli_stmt_bind_param($stmt_insert, "iiis", $id_peminjaman, $id_user, $id_petugas, $tanggal_kembali);
        mysqli_stmt_execute($stmt_insert);

        // Update status peminjaman
        $update = "UPDATE peminjaman SET status_peminjaman = 'dikembalikan' WHERE ID_peminjaman = ?";
        $stmt_update = mysqli_prepare($conn, $update);
        mysqli_stmt_bind_param($stmt_update, "i", $id_peminjaman);
        mysqli_stmt_execute($stmt_update);

        // Ambil ID_buku dari tabel peminjaman
        $query_buku = "SELECT ID_buku FROM peminjaman WHERE ID_peminjaman = ?";
        $stmt_buku = mysqli_prepare($conn, $query_buku);
        mysqli_stmt_bind_param($stmt_buku, "i", $id_peminjaman);
        mysqli_stmt_execute($stmt_buku);
        $result_buku = mysqli_stmt_get_result($stmt_buku);
        $data_buku = mysqli_fetch_assoc($result_buku);

        if ($data_buku) {
            $id_buku = $data_buku['ID_buku'];

            // Update status buku menjadi TERSEDIA
            $update_buku = "UPDATE buku SET status = 'TERSEDIA' WHERE ID_buku = ?";
            $stmt_update_buku = mysqli_prepare($conn, $update_buku);
            mysqli_stmt_bind_param($stmt_update_buku, "i", $id_buku);
            mysqli_stmt_execute($stmt_update_buku);
        }

        header("Location: peminjaman.php?kembali=success");
        exit();
    }

?>
