<?php
require_once '../../../config/database.php';

// Cek apakah ada parameter id
if (isset($_GET['id'])) {
    $idFisik = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Ambil data fisik untuk validasi
    $checkQuery = "SELECT idFisik FROM fisik WHERE idFisik = '$idFisik'";
    $checkResult = mysqli_query($conn, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        // Data ditemukan, lakukan penghapusan
        $deleteQuery = "DELETE FROM fisik WHERE idFisik = '$idFisik'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            // Berhasil dihapus
            header("Location: ../list-fisik.php?message=hapus");
            exit();
        } else {
            // Gagal menghapus
            header("Location: ../list-fisik.php?error=gagal_hapus");
            exit();
        }
    } else {
        // Data tidak ditemukan
        header("Location: ../list-fisik.php?error=tidak_ditemukan");
        exit();
    }
} else {
    // Tidak ada parameter id
    header("Location: ../list-fisik.php?error=invalid_request");
    exit();
}
?>