<?php
// Include file ini di setiap halaman yang butuh login
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php?error=not_logged_in");
    exit();
}
?>