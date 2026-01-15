<?php
require_once '../../../config/database.php';

// Cek apakah ada parameter id
if (isset($_GET['id'])) {
    $idPendidikan = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Ambil data Pendidikan untuk validasi
    $checkQuery = "SELECT idPendidikan FROM pendidikan WHERE idPendidikan = '$idPendidikan'";
    $checkResult = mysqli_query($conn, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        // Data ditemukan, lakukan penghapusan
        $deleteQuery = "DELETE FROM pendidikan WHERE idPendidikan = '$idPendidikan'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            // Berhasil dihapus
            header("Location: ../list-pendidikan.php?message=hapus");
            exit();
        } else {
            // Gagal menghapus
            header("Location: ../list-pendidikan.php?error=gagal_hapus");
            exit();
        }
    } else {
        // Data tidak ditemukan
        header("Location: ../list-pendidikan.php?error=tidak_ditemukan");
        exit();
    }
} else {
    // Tidak ada parameter id
    header("Location: ../list-pendidikan.php?error=invalid_request");
    exit();
}
?>