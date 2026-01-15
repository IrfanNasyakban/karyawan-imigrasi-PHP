<?php
require_once '../../../config/database.php';

// Cek apakah ada parameter id
if (isset($_GET['id'])) {
    $idAnak = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Ambil data Anak untuk validasi
    $checkQuery = "SELECT idAnak FROM anak WHERE idAnak = '$idAnak'";
    $checkResult = mysqli_query($conn, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        // Data ditemukan, lakukan penghapusan
        $deleteQuery = "DELETE FROM anak WHERE idAnak = '$idAnak'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            // Berhasil dihapus
            header("Location: ../list-anak.php?message=hapus");
            exit();
        } else {
            // Gagal menghapus
            header("Location: ../list-anak.php?error=gagal_hapus");
            exit();
        }
    } else {
        // Data tidak ditemukan
        header("Location: ../list-anak.php?error=tidak_ditemukan");
        exit();
    }
} else {
    // Tidak ada parameter id
    header("Location: ../list-anak.php?error=invalid_request");
    exit();
}
?>