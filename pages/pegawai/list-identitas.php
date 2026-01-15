<?php
require_once '../../config/database.php';

$page_title = 'Data Identitas Pegawai';

// Ambil keyword search
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Modifikasi query untuk search
if ($search != '') {
    $query = "SELECT 
                i.idIdentitas,
                i.idPegawai,
                p.namaDenganGelar,
                i.nik,
                i.nomorKK,
                i.nomorBPJS,
                i.nomorTaspen
              FROM identitas i
              LEFT JOIN pegawai p ON i.idPegawai = p.idPegawai
              WHERE p.namaDenganGelar LIKE '%$search%'
              OR i.nik LIKE '%$search%'
              OR i.nomorKK LIKE '%$search%'
              ORDER BY i.idIdentitas DESC";
} else {
    $query = "SELECT 
                i.idIdentitas,
                i.idPegawai,
                p.namaDenganGelar,
                i.nik,
                i.nomorKK,
                i.nomorBPJS,
                i.nomorTaspen
              FROM identitas i
              LEFT JOIN pegawai p ON i.idPegawai = p.idPegawai
              ORDER BY i.idIdentitas DESC";
}

$result = mysqli_query($conn, $query);

include '../../includes/sidebar.php';
?>

<link rel="stylesheet" href="../../assets/css/style-tables.css">

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
            <h2><i class="fas fa-address-card me-2"></i>Data Identitas</h2>
            <p>Sistem Informasi Identitas - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-users page-header-icon d-none d-md-block"></i>
    </div>

    <div class="action-bar">
        <div class="action-bar-left" style="display: flex; gap: 15px; align-items: center;">
            <a href="tambah/tambah-identitas-2.php" class="btn-add">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
            <form method="GET" action="" class="search-form" style="margin: 0;">
                <div class="search-box">
                    <input type="text" 
                           name="search" 
                           class="search-input" 
                           placeholder="Cari nama pegawai atau NIK..." 
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
                <?php echo mysqli_num_rows($result); ?> data ditemukan
            </span>
        </div>
    <?php endif; ?>

    <!-- Table -->
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-identitas" class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Pegawai</th>
                        <th>NIK</th>
                        <th>Nomor KK</th>
                        <th>Nomor BPJS</th>
                        <th>Nomor Taspen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($result) > 0) {
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)): 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['namaDenganGelar']); ?></td>
                        <td><?php echo htmlspecialchars($row['nik']); ?></td>
                        <td><?php echo htmlspecialchars($row['nomorKK']); ?></td>
                        <td><?php echo htmlspecialchars($row['nomorBPJS']); ?></td>
                        <td><?php echo htmlspecialchars($row['nomorTaspen']); ?></td>
                        <td>
                            <a href="edit/edit-identitas.php?id=<?php echo $row['idIdentitas']; ?>" 
                               class="btn btn-warning btn-sm" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-danger btn-sm" 
                                    title="Hapus"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal"
                                    data-id="<?php echo $row['idIdentitas']; ?>"
                                    data-nama="<?php echo htmlspecialchars($row['namaDenganGelar']); ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endwhile;
                    } else {
                    ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h5>Tidak Ada Data</h5>
                                <p>Belum ada data identitas yang tersedia</p>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
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
                <p class="text-muted mb-2">Data identitas untuk:</p>
                <p class="fw-bold" id="deleteNamaPegawai"></p>
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
                        if ($_GET['message'] == 'tambah') echo 'Data identitas berhasil ditambahkan!';
                        if ($_GET['message'] == 'edit') echo 'Data identitas berhasil diubah!';
                        if ($_GET['message'] == 'hapus') echo 'Data identitas berhasil dihapus!';
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
                        if ($_GET['error'] == 'gagal_hapus') echo 'Gagal menghapus data identitas!';
                        if ($_GET['error'] == 'tidak_ditemukan') echo 'Data identitas tidak ditemukan!';
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
            const idIdentitas = button.getAttribute('data-id');
            const namaPegawai = button.getAttribute('data-nama');
            
            // Update modal content
            document.getElementById('deleteNamaPegawai').textContent = namaPegawai;
            document.getElementById('confirmDeleteBtn').href = 'hapus/hapus-identitas.php?id=' + idIdentitas;
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