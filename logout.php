<?php
    session_start();
    session_unset();
    session_destroy();

    $lanjut = $_GET['lanjut'] ?? '';
    if ($lanjut === 'user') {
        header("Location: login_user.php");
        exit();
    } elseif ($lanjut === 'petugas') {
        header("Location: login.php");
        exit();
    } else {
        header("Location: index.php");
        exit();
    }
?>