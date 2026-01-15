<?php
require_once '../../../config/database.php';

// Cek apakah ada parameter id
if (isset($_GET['id'])) {
    $idPangkat = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Ambil data Pangkat untuk validasi
    $checkQuery = "SELECT idPangkat FROM pangkat WHERE idPangkat = '$idPangkat'";
    $checkResult = mysqli_query($conn, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        // Data ditemukan, lakukan penghapusan
        $deleteQuery = "DELETE FROM pangkat WHERE idPangkat = '$idPangkat'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            // Berhasil dihapus
            header("Location: ../list-pangkat.php?message=hapus");
            exit();
        } else {
            // Gagal menghapus
            header("Location: ../list-pangkat.php?error=gagal_hapus");
            exit();
        }
    } else {
        // Data tidak ditemukan
        header("Location: ../list-pangkat.php?error=tidak_ditemukan");
        exit();
    }
} else {
    // Tidak ada parameter id
    header("Location: ../list-pangkat.php?error=invalid_request");
    exit();
}
?>