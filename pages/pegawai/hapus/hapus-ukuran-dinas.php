<?php
require_once '../../../config/database.php';

// Cek apakah ada parameter id
if (isset($_GET['id'])) {
    $idUkuran = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Ambil data Ukuran untuk validasi
    $checkQuery = "SELECT idUkuran FROM ukuran WHERE idUkuran = '$idUkuran'";
    $checkResult = mysqli_query($conn, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        // Data ditemukan, lakukan penghapusan
        $deleteQuery = "DELETE FROM ukuran WHERE idUkuran = '$idUkuran'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            // Berhasil dihapus
            header("Location: ../list-ukuran-dinas.php?message=hapus");
            exit();
        } else {
            // Gagal menghapus
            header("Location: ../list-ukuran-dinas.php?error=gagal_hapus");
            exit();
        }
    } else {
        // Data tidak ditemukan
        header("Location: ../list-ukuran-dinas.php?error=tidak_ditemukan");
        exit();
    }
} else {
    // Tidak ada parameter id
    header("Location: ../list-ukuran-dinas.php?error=invalid_request");
    exit();
}
?>