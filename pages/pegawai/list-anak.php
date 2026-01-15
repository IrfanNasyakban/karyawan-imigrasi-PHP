<?php
require_once '../../config/database.php';

$page_title = 'Data Anak';

// Ambil keyword search
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Modifikasi query untuk search
if ($search != '') {
    $query = "SELECT 
                p.idPegawai,
                p.namaDenganGelar,
                p.nip,
                COUNT(a.idAnak) as jumlahAnak
              FROM pegawai p
              INNER JOIN anak a ON p.idPegawai = a.idPegawai
              WHERE p.namaDenganGelar LIKE '%$search%'
              OR a.namaAnak LIKE '%$search%'
              GROUP BY p.idPegawai, p.namaDenganGelar, p.nip
              ORDER BY p.namaDenganGelar ASC";
} else {
    $query = "SELECT 
                p.idPegawai,
                p.namaDenganGelar,
                p.nip,
                COUNT(a.idAnak) as jumlahAnak
              FROM pegawai p
              INNER JOIN anak a ON p.idPegawai = a.idPegawai
              GROUP BY p.idPegawai, p.namaDenganGelar, p.nip
              ORDER BY p.namaDenganGelar ASC";
}

$result = mysqli_query($conn, $query);

// Cek jika query gagal
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}

include '../../includes/sidebar.php';
?>

<link rel="stylesheet" href="../../assets/css/style-tables.css">
<link rel="stylesheet" href="../../assets/css/style-list-anak.css">

<style>
/* Search Box Styling */
.search-box {
    position: relative;
    display: flex;
    align-items: center;
    gap: 8px;
}

.search-input {
    padding: 10px 40px 10px 16px;
    border-radius: 8px;
    font-size: 14px;
    width: 300px;
    transition: all 0.3s ease;
    background: white;
}

.search-input:focus {
    outline: none;
    border-color: #4CAF50;
    box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
}

.search-button {
    position: absolute;
    right: 8px;
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 8px 12px;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.search-button:hover {
    background: #f5f5f5;
    color: #4CAF50;
}

.search-reset {
    position: absolute;
    right: 45px;
    background: #ff5252;
    border: none;
    color: white;
    cursor: pointer;
    padding: 6px 10px;
    border-radius: 50%;
    transition: all 0.3s ease;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.search-reset:hover {
    background: #ff1744;
    transform: rotate(90deg);
    color: white;
}

.search-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 6px rgba(102, 126, 234, 0.2);
}

.search-info i {
    font-size: 20px;
}

.search-info strong {
    background: rgba(255, 255, 255, 0.2);
    padding: 4px 10px;
    border-radius: 6px;
}
</style>

<div class="container-fluid px-2 py-2">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h2><i class="fas fa-child me-2"></i>Data Anak</h2>
            <p>Sistem Informasi Anak Pegawai - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-users page-header-icon d-none d-md-block"></i>
    </div>

    <div class="action-bar">
        <div class="action-bar-left" style="display: flex; gap: 15px; align-items: center;">
            <a href="tambah/tambah-anak-2.php" class="btn-add">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
            <form method="GET" action="" class="search-form" style="margin: 0;">
                <div class="search-box">
                    <input type="text" 
                           name="search" 
                           class="search-input" 
                           placeholder="Cari nama pegawai atau anak..." 
                           value="<?php echo htmlspecialchars($search); ?>"
                           autocomplete="off">
                    <?php if ($search != ''): ?>
                        <a href="?" class="search-reset" title="Reset pencarian">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Search Result Info -->
    <?php if ($search != ''): ?>
        <div class="search-info">
            <i class="fas fa-filter"></i>
            <span>Hasil pencarian untuk: <strong>"<?php echo htmlspecialchars($search); ?>"</strong></span>
            <span style="margin-left: auto; background: rgba(255,255,255,0.3); padding: 4px 12px; border-radius: 20px;">
                <?php echo mysqli_num_rows($result); ?> pegawai ditemukan
            </span>
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
                                        <a href="edit/edit-anak.php?id=<?php echo $anak['idAnak']; ?>" 
                                           class="btn btn-warning btn-sm" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                title="Hapus"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal"
                                                data-id="<?php echo $anak['idAnak']; ?>"
                                                data-nama-anak="<?php echo htmlspecialchars($anak['namaAnak']); ?>"
                                                data-nama-pegawai="<?php echo htmlspecialchars($pegawai['namaDenganGelar']); ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
                <a href="tambah/tambah-anak-2.php" class="btn-add mt-3">
                    <i class="fas fa-plus"></i> Tambah Data Anak
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                </div>
                <h5 class="mb-3">Apakah Anda yakin ingin menghapus data ini?</h5>
                <div class="text-start mb-3">
                    <p class="text-muted mb-1">Data anak:</p>
                    <p class="fw-bold mb-2" id="deleteNamaAnak"></p>
                    <p class="text-muted small mb-0">Dari pegawai: <span id="deleteNamaPegawai" class="fw-semibold"></span></p>
                </div>
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>Data yang dihapus tidak dapat dikembalikan!</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i>Ya, Hapus Data
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Alert Success -->
<?php if (isset($_GET['message'])): ?>
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>Berhasil
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                <h5 class="mt-3 mb-0">
                    <?php 
                        if ($_GET['message'] == 'tambah') echo 'Data anak berhasil ditambahkan!';
                        if ($_GET['message'] == 'edit') echo 'Data anak berhasil diubah!';
                        if ($_GET['message'] == 'hapus') echo 'Data anak berhasil dihapus!';
                    ?>
                </h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Modal Alert Error -->
<?php if (isset($_GET['error'])): ?>
<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Error
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-times-circle text-danger" style="font-size: 3rem;"></i>
                <h5 class="mt-3 mb-0">
                    <?php 
                        if ($_GET['error'] == 'gagal_hapus') echo 'Gagal menghapus data anak!';
                        if ($_GET['error'] == 'tidak_ditemukan') echo 'Data anak tidak ditemukan!';
                        if ($_GET['error'] == 'invalid_request') echo 'Permintaan tidak valid!';
                    ?>
                </h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
// Script untuk modal delete
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete button click
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const idAnak = button.getAttribute('data-id');
            const namaAnak = button.getAttribute('data-nama-anak');
            const namaPegawai = button.getAttribute('data-nama-pegawai');
            
            // Update modal content
            document.getElementById('deleteNamaAnak').textContent = namaAnak;
            document.getElementById('deleteNamaPegawai').textContent = namaPegawai;
            document.getElementById('confirmDeleteBtn').href = 'hapus/hapus-anak.php?id=' + idAnak;
        });
    }
    
    // Auto show success modal
    <?php if (isset($_GET['message'])): ?>
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
    
    // Auto close after 3 seconds
    setTimeout(function() {
        successModal.hide();
        // Remove query parameter from URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }, 3000);
    <?php endif; ?>
    
    // Auto show error modal
    <?php if (isset($_GET['error'])): ?>
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    errorModal.show();
    
    // Auto close after 3 seconds
    setTimeout(function() {
        errorModal.hide();
        // Remove query parameter from URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }, 3000);
    <?php endif; ?>
});
</script>

<?php include '../../includes/footer.php'; ?>