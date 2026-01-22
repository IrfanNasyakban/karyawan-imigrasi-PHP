<?php
require_once '../../../config/database.php';
require_once '../../../includes/check_login.php';

$page_title = 'Edit Data Pendidikan';

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../list-pendidikan.php?error=invalid_request");
    exit();
}

$idPendidikan = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data Pendidikan dan pegawai berdasarkan ID
$query = "SELECT pd.*, p.nip, p.namaDenganGelar, p.statusPegawai 
          FROM pendidikan pd
          LEFT JOIN pegawai p ON pd.idPegawai = p.idPegawai
          WHERE pd.idPendidikan = '$idPendidikan'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: ../list-pendidikan.php?error=tidak_ditemukan");
    exit();
}

$pendidikan = mysqli_fetch_assoc($result);

// Proses update data
if (isset($_POST['submit'])) {
    $pendidikanTerakhir = mysqli_real_escape_string($conn, $_POST['pendidikanTerakhir']);
    
    $queryUpdate = "UPDATE pendidikan SET 
                    pendidikanTerakhir = '$pendidikanTerakhir'
                    WHERE idPendidikan = '$idPendidikan'";
    
    if (mysqli_query($conn, $queryUpdate)) {
        header("Location: ../list-pendidikan.php?message=edit");
        exit();
    } else {
        $error = "Gagal mengupdate data: " . mysqli_error($conn);
    }
}

include '../../../includes/sidebar.php';
?>

<link rel="stylesheet" href="../../../assets/css/style-tables.css">
<link rel="stylesheet" href="../../../assets/css/style-form.css">

<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h2><i class="fas fa-graduation-cap me-2"></i>Edit Data Pendidikan</h2>
            <p>Formulir Edit Data Pendidikan Formal - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-graduation-cap page-header-icon d-none d-md-block"></i>
    </div>

    <!-- Alert Error -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Info Pegawai Card -->
    <div class="info-card mb-4">
        <div class="info-card-header">
            <i class="fas fa-user-circle"></i>
            <span>Informasi Pegawai</span>
        </div>
        <div class="info-card-body">
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-user"></i> Nama Lengkap
                </div>
                <div class="info-value"><?php echo htmlspecialchars($pendidikan['namaDenganGelar']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-id-card"></i> NIP
                </div>
                <div class="info-value"><?php echo htmlspecialchars($pendidikan['nip']); ?></div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="" id="formPendidikan">

            <!-- Section: Data Pendidikan Formal -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-graduation-cap"></i>
                    <h5>Data Pendidikan Formal Terakhir</h5>
                </div>
                <div class="form-section-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">
                                Jenjang Pendidikan Terakhir <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-user-graduate"></i>
                                </span>
                                <select name="pendidikanTerakhir" class="form-control" required>
                                    <option value="" disabled>Pilih Jenjang Pendidikan</option>
                                    <option value="SD/Sederajat" <?php echo ($pendidikan['pendidikanTerakhir'] == 'SD/Sederajat') ? 'selected' : ''; ?>>SD/Sederajat</option>
                                    <option value="SMP/Sederajat" <?php echo ($pendidikan['pendidikanTerakhir'] == 'SMP/Sederajat') ? 'selected' : ''; ?>>SMP/Sederajat</option>
                                    <option value="SMA/Sederajat" <?php echo ($pendidikan['pendidikanTerakhir'] == 'SMA/Sederajat') ? 'selected' : ''; ?>>SMA/Sederajat</option>
                                    <option value="SMK" <?php echo ($pendidikan['pendidikanTerakhir'] == 'SMK') ? 'selected' : ''; ?>>SMK</option>
                                    <option value="D1 (Diploma I)" <?php echo ($pendidikan['pendidikanTerakhir'] == 'D1 (Diploma I)') ? 'selected' : ''; ?>>D1 (Diploma I)</option>
                                    <option value="D2 (Diploma II)" <?php echo ($pendidikan['pendidikanTerakhir'] == 'D2 (Diploma II)') ? 'selected' : ''; ?>>D2 (Diploma II)</option>
                                    <option value="D3 (Diploma III)" <?php echo ($pendidikan['pendidikanTerakhir'] == 'D3 (Diploma III)') ? 'selected' : ''; ?>>D3 (Diploma III)</option>
                                    <option value="D4 (Diploma IV)" <?php echo ($pendidikan['pendidikanTerakhir'] == 'D4 (Diploma IV)') ? 'selected' : ''; ?>>D4 (Diploma IV)</option>
                                    <option value="S1 (Sarjana)" <?php echo ($pendidikan['pendidikanTerakhir'] == 'S1 (Sarjana)') ? 'selected' : ''; ?>>S1 (Sarjana)</option>
                                    <option value="S2 (Magister)" <?php echo ($pendidikan['pendidikanTerakhir'] == 'S2 (Magister)') ? 'selected' : ''; ?>>S2 (Magister)</option>
                                    <option value="S3 (Doktor)" <?php echo ($pendidikan['pendidikanTerakhir'] == 'S3 (Doktor)') ? 'selected' : ''; ?>>S3 (Doktor)</option>
                                    <option value="Profesi" <?php echo ($pendidikan['pendidikanTerakhir'] == 'Profesi') ? 'selected' : ''; ?>>Profesi</option>
                                    <option value="Spesialis" <?php echo ($pendidikan['pendidikanTerakhir'] == 'Spesialis') ? 'selected' : ''; ?>>Spesialis</option>
                                </select>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Pilih jenjang pendidikan tertinggi yang telah diselesaikan
                            </small>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Data pendidikan terakhir digunakan untuk keperluan administrasi kepegawaian dan pengembangan karir
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="../list-pendidikan.php" class="btn-cancel">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
                <button type="submit" name="submit" class="btn-submit">
                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Form validation
document.getElementById('formPendidikan').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Mohon lengkapi semua field yang wajib diisi!');
    }
});

// Remove invalid class on change
document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('change', function() {
        this.classList.remove('is-invalid');
    });
    
    input.addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });
});

// Highlight selected education level
document.querySelector('select[name="pendidikanTerakhir"]').addEventListener('change', function() {
    if (this.value) {
        this.style.fontWeight = 'bold';
        this.style.color = '#059669';
    } else {
        this.style.fontWeight = 'normal';
        this.style.color = '';
    }
});

// Initialize highlight on page load
document.addEventListener('DOMContentLoaded', function() {
    const selectElement = document.querySelector('select[name="pendidikanTerakhir"]');
    if (selectElement.value) {
        selectElement.style.fontWeight = 'bold';
        selectElement.style.color = '#059669';
    }
});
</script>

<?php include '../../../includes/footer.php'; ?>