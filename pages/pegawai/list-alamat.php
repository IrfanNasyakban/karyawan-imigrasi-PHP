<?php
require_once '../../config/database.php';

$page_title = 'Data Alamat';

// Ambil data alamat
$query = "SELECT 
            a.idAlamat,
            a.idPegawai,
            p.namaDenganGelar,
            a.alamatKTP,
            a.alamatDomisili
          FROM alamat a
          LEFT JOIN pegawai p ON a.idPegawai = p.idPegawai
          ORDER BY a.idAlamat DESC";

$result = mysqli_query($conn, $query);

include '../../includes/sidebar.php';
?>

<link rel="stylesheet" href="../../assets/css/style-tables.css">

<div class="container-fluid px-2 py-2">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h2><i class="fas fa-map-marker-alt me-2"></i>Data Alamat</h2>
            <p>Sistem Informasi Alamat - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-users page-header-icon d-none d-md-block"></i>
    </div>

    <div class="action-bar">
        <a href="tambah/tambah-alamat-2.php" class="btn-add">
            <i class="fas fa-plus"></i> Tambah Data
        </a>
    </div>

    <!-- Table -->
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-alamat" class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Pegawai</th>
                        <th>Alamat KTP</th>
                        <th>Alamat Domisili</th>
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
                        <td><?php echo htmlspecialchars($row['alamatKTP']); ?></td>
                        <td><?php echo htmlspecialchars($row['alamatDomisili']); ?></td>
                        <td>
                            <a href="edit/edit-alamat.php?id=<?php echo $row['idAlamat']; ?>" 
                               class="btn btn-warning btn-sm" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-danger btn-sm" 
                                    title="Hapus"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal"
                                    data-id="<?php echo $row['idAlamat']; ?>"
                                    data-nama="<?php echo htmlspecialchars($row['namaDenganGelar']); ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endwhile;
                    } else {
                    ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h5>Tidak Ada Data</h5>
                                <p>Belum ada data alamat yang tersedia</p>
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
                <p class="text-muted mb-2">Data alamat untuk:</p>
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
                        if ($_GET['message'] == 'tambah') echo 'Data alamat berhasil ditambahkan!';
                        if ($_GET['message'] == 'edit') echo 'Data alamat berhasil diubah!';
                        if ($_GET['message'] == 'hapus') echo 'Data alamat berhasil dihapus!';
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
                        if ($_GET['error'] == 'gagal_hapus') echo 'Gagal menghapus data alamat!';
                        if ($_GET['error'] == 'tidak_ditemukan') echo 'Data alamat tidak ditemukan!';
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
            const idAlamat = button.getAttribute('data-id');
            const namaPegawai = button.getAttribute('data-nama');
            
            // Update modal content
            document.getElementById('deleteNamaPegawai').textContent = namaPegawai;
            document.getElementById('confirmDeleteBtn').href = 'hapus/hapus-alamat.php?id=' + idAlamat;
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