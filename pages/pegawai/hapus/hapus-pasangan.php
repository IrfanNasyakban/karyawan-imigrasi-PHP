<?php
require_once '../../../config/database.php';

// Cek apakah ada parameter id
if (isset($_GET['id'])) {
    $idPasangan = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Ambil data Pasangan untuk validasi
    $checkQuery = "SELECT idPasangan FROM pasangan WHERE idPasangan = '$idPasangan'";
    $checkResult = mysqli_query($conn, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        // Data ditemukan, lakukan penghapusan
        $deleteQuery = "DELETE FROM pasangan WHERE idPasangan = '$idPasangan'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            // Berhasil dihapus
            header("Location: ../list-pasangan.php?message=hapus");
            exit();
        } else {
            // Gagal menghapus
            header("Location: ../list-pasangan.php?error=gagal_hapus");
            exit();
        }
    } else {
        // Data tidak ditemukan
        header("Location: ../list-pasangan.php?error=tidak_ditemukan");
        exit();
    }
} else {
    // Tidak ada parameter id
    header("Location: ../list-pasangan.php?error=invalid_request");
    exit();
}
?>