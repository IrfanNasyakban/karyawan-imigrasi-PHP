<?php
require_once '../../config/database.php';

$page_title = 'Data Anak';

// Ambil data pegawai yang memiliki anak, beserta jumlah anaknya
$query = "SELECT 
            p.idPegawai,
            p.namaDenganGelar,
            p.nip,
            COUNT(a.idAnak) as jumlahAnak
          FROM pegawai p
          INNER JOIN anak a ON p.idPegawai = a.idPegawai
          GROUP BY p.idPegawai, p.namaDenganGelar, p.nip
          ORDER BY p.namaDenganGelar ASC";

$result = mysqli_query($conn, $query);

// Cek jika query gagal
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}

include '../../includes/sidebar.php';
?>

<link rel="stylesheet" href="../../assets/css/style-tables.css">
<link rel="stylesheet" href="../../assets/css/style-list-anak.css">

<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h2><i class="fas fa-child me-2"></i>Data Anak</h2>
            <p>Sistem Informasi Anak Pegawai - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-users page-header-icon d-none d-md-block"></i>
    </div>

    <div class="action-bar">
        <div class="action-bar-left">
            <a href="tambah-anak.php" class="btn-add">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
        </div>
    </div>

    <!-- Alert -->
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            <?php 
                if ($_GET['message'] == 'tambah') echo 'Data anak berhasil ditambahkan!';
                if ($_GET['message'] == 'edit') echo 'Data anak berhasil diubah!';
                if ($_GET['message'] == 'hapus') echo 'Data anak berhasil dihapus!';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Data Card -->
    <div class="data-card">
        <div class="card-body-custom">
            <?php 
            if (mysqli_num_rows($result) > 0) {
                $no = 1;
                while ($pegawai = mysqli_fetch_assoc($result)):
                    // Query untuk ambil semua anak dari pegawai ini
                    $idPegawai = mysqli_real_escape_string($conn, $pegawai['idPegawai']);
                    
                    $queryAnak = "SELECT 
                                    a.idAnak,
                                    a.idPegawai,
                                    a.namaAnak
                                  FROM anak a
                                  WHERE a.idPegawai = '$idPegawai'
                                  ORDER BY a.idAnak ASC";
                    
                    $resultAnak = mysqli_query($conn, $queryAnak);
                    
                    // Cek jika query anak gagal
                    if (!$resultAnak) {
                        echo "<div class='alert alert-danger'>Query Error: " . mysqli_error($conn) . "</div>";
                        continue;
                    }
            ?>
            
            <!-- Card untuk setiap Pegawai -->
            <div class="pegawai-card">
                <div class="pegawai-header">
                    <div class="pegawai-info">
                        <div class="pegawai-details">
                            <h5 class="pegawai-name">
                                <i class="fas fa-user-tie me-2"></i>
                                <?php echo htmlspecialchars($pegawai['namaDenganGelar']); ?>
                            </h5>
                            <p class="pegawai-nip">
                                <i class="fas fa-id-card me-1"></i>
                                NIP: <?php echo htmlspecialchars($pegawai['nip']); ?>
                            </p>
                        </div>
                    </div>
                    <div class="pegawai-badge">
                        <span class="badge bg-primary">
                            <i class="fas fa-child me-1"></i>
                            <?php echo $pegawai['jumlahAnak']; ?> Anak
                        </span>
                    </div>
                </div>

                <!-- Tabel Anak (Simple Version) -->
                <div class="table-responsive">
                    <table class="table table-anak">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th width="70%">Nama Anak</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (mysqli_num_rows($resultAnak) > 0) {
                                $noAnak = 1;
                                while ($anak = mysqli_fetch_assoc($resultAnak)): 
                            ?>
                            <tr>
                                <td><?php echo $noAnak++; ?></td>
                                <td>
                                    <strong>
                                        <?php echo htmlspecialchars($anak['namaAnak']); ?>
                                    </strong>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit-anak.php?id=<?php echo $anak['idAnak']; ?>" 
                                           class="btn btn-warning btn-sm" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="hapus-anak.php?id=<?php echo $anak['idAnak']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           title="Hapus" 
                                           onclick="return confirm('Yakin ingin menghapus data anak: <?php echo htmlspecialchars($anak['namaAnak']); ?>?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                endwhile;
                            } else {
                            ?>
                            <tr>
                                <td colspan="3" class="text-center">
                                    <em class="text-muted">Tidak ada data anak</em>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <?php 
                endwhile;
            } else {
            ?>
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-child"></i>
                <h5>Tidak Ada Data</h5>
                <p>Belum ada data anak pegawai yang tersedia</p>
                <a href="tambah-anak.php" class="btn-add mt-3">
                    <i class="fas fa-plus"></i> Tambah Data Anak
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>