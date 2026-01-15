<?php
require_once '../../../config/database.php';

// Cek apakah ada parameter id
if (isset($_GET['id'])) {
    $idRekening = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Ambil data Rekening untuk validasi
    $checkQuery = "SELECT idRekening FROM rekening WHERE idRekening = '$idRekening'";
    $checkResult = mysqli_query($conn, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        // Data ditemukan, lakukan penghapusan
        $deleteQuery = "DELETE FROM rekening WHERE idRekening = '$idRekening'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            // Berhasil dihapus
            header("Location: ../list-rekening.php?message=hapus");
            exit();
        } else {
            // Gagal menghapus
            header("Location: ../list-rekening.php?error=gagal_hapus");
            exit();
        }
    } else {
        // Data tidak ditemukan
        header("Location: ../list-rekening.php?error=tidak_ditemukan");
        exit();
    }
} else {
    // Tidak ada parameter id
    header("Location: ../list-rekening.php?error=invalid_request");
    exit();
}
?>