<?php
require_once '../../../config/database.php';

// Cek apakah ada parameter id
if (isset($_GET['id'])) {
    $idAlamat = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Ambil data alamat untuk validasi
    $checkQuery = "SELECT idAlamat FROM alamat WHERE idAlamat = '$idAlamat'";
    $checkResult = mysqli_query($conn, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        // Data ditemukan, lakukan penghapusan
        $deleteQuery = "DELETE FROM alamat WHERE idAlamat = '$idAlamat'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            // Berhasil dihapus
            header("Location: ../list-alamat.php?message=hapus");
            exit();
        } else {
            // Gagal menghapus
            header("Location: ../list-alamat.php?error=gagal_hapus");
            exit();
        }
    } else {
        // Data tidak ditemukan
        header("Location: ../list-alamat.php?error=tidak_ditemukan");
        exit();
    }
} else {
    // Tidak ada parameter id
    header("Location: ../list-alamat.php?error=invalid_request");
    exit();
}
?>