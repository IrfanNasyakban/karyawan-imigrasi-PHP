<?php
require_once '../../../config/database.php';

// Cek apakah ada parameter id
if (isset($_GET['id'])) {
    $idKepegawaian = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Ambil data kepegawaian untuk validasi
    $checkQuery = "SELECT idKepegawaian FROM kepegawaian WHERE idKepegawaian = '$idKepegawaian'";
    $checkResult = mysqli_query($conn, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        // Data ditemukan, lakukan penghapusan
        $deleteQuery = "DELETE FROM kepegawaian WHERE idKepegawaian = '$idKepegawaian'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            // Berhasil dihapus
            header("Location: ../list-kepegawaian.php?message=hapus");
            exit();
        } else {
            // Gagal menghapus
            header("Location: ../list-kepegawaian.php?error=gagal_hapus");
            exit();
        }
    } else {
        // Data tidak ditemukan
        header("Location: ../list-kepegawaian.php?error=tidak_ditemukan");
        exit();
    }
} else {
    // Tidak ada parameter id
    header("Location: ../list-kepegawaian.php?error=invalid_request");
    exit();
}
?>