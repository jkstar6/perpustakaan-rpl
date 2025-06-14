<?php
    session_start(); // mengaktifkan session
    session_destroy(); // menghapus semua session
    // mengalihkan halaman sambil mengirim pesan logout
    session_start();
    //alert
    $_SESSION['eksekusi'] = "<p class='alert' style='color: #9DBC98;'>Anda telah berhasil log out!</p>";
    header("location: login.php");
?>