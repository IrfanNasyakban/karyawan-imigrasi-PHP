<?php
require_once '../../../config/database.php';

$page_title = 'Edit Data Identitas';

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../list-identitas.php?error=invalid_request");
    exit();
}

$idIdentitas = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data Identitas dan pegawai berdasarkan ID
$query = "SELECT i.*, p.nip, p.namaDenganGelar, p.statusPegawai 
          FROM identitas i
          LEFT JOIN pegawai p ON i.idPegawai = p.idPegawai
          WHERE i.idIdentitas = '$idIdentitas'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: ../list-identitas.php?error=tidak_ditemukan");
    exit();
}

$identitas = mysqli_fetch_assoc($result);

// Proses update data
if (isset($_POST['submit'])) {
    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $nomorKK = mysqli_real_escape_string($conn, $_POST['nomorKK']);
    $nomorBPJS = mysqli_real_escape_string($conn, $_POST['nomorBPJS']);
    $nomorTaspen = mysqli_real_escape_string($conn, $_POST['nomorTaspen']);
    
    $queryUpdate = "UPDATE identitas SET 
                    nik = '$nik',
                    nomorKK = '$nomorKK',
                    nomorBPJS = '$nomorBPJS',
                    nomorTaspen = '$nomorTaspen'
                    WHERE idIdentitas = '$idIdentitas'";
    
    if (mysqli_query($conn, $queryUpdate)) {
        header("Location: ../list-identitas.php?message=edit");
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
            <h2><i class="fas fa-address-card me-2"></i>Edit Data Identitas</h2>
            <p>Formulir Edit Data Identitas & Nomor Kependudukan - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-address-card page-header-icon d-none d-md-block"></i>
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
                <div class="info-value"><?php echo htmlspecialchars($identitas['namaDenganGelar']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-id-card"></i> NIP
                </div>
                <div class="info-value"><?php echo htmlspecialchars($identitas['nip']); ?></div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="" id="formIdentitas">

            <!-- Section: Data Identitas Kependudukan -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-id-card"></i>
                    <h5>Data Identitas Kependudukan</h5>
                </div>
                <div class="form-section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-id-card"></i>
                                </span>
                                <input type="text" 
                                       name="nik" 
                                       class="form-control" 
                                       placeholder="16 digit NIK"
                                       maxlength="16"
                                       value="<?php echo htmlspecialchars($identitas['nik']); ?>"
                                       required>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                NIK terdiri dari 16 digit angka sesuai KTP
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Nomor KK (Kartu Keluarga) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-users"></i>
                                </span>
                                <input type="text" 
                                       name="nomorKK" 
                                       class="form-control" 
                                       placeholder="16 digit Nomor KK"
                                       maxlength="16"
                                       value="<?php echo htmlspecialchars($identitas['nomorKK']); ?>"
                                       required>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Nomor KK terdiri dari 16 digit angka
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Data Kepesertaan -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-hospital"></i>
                    <h5>Data Kepesertaan Asuransi & Pensiun</h5>
                </div>
                <div class="form-section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Nomor BPJS Kesehatan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-hospital-user"></i>
                                </span>
                                <input type="text" 
                                       name="nomorBPJS" 
                                       class="form-control" 
                                       placeholder="13 digit Nomor BPJS"
                                       maxlength="13"
                                       value="<?php echo htmlspecialchars($identitas['nomorBPJS']); ?>"
                                       required>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                BPJS: Badan Penyelenggara Jaminan Sosial (13 digit)
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Nomor TASPEN <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-piggy-bank"></i>
                                </span>
                                <input type="text" 
                                       name="nomorTaspen" 
                                       class="form-control" 
                                       placeholder="Nomor TASPEN"
                                       value="<?php echo htmlspecialchars($identitas['nomorTaspen']); ?>"
                                       required>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                TASPEN: Tabungan dan Asuransi Pegawai Negeri
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="../list-identitas.php" class="btn-cancel">
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
// Format NIK - hanya angka
document.querySelector('input[name="nik"]').addEventListener('input', function(e) {
    let value = this.value.replace(/\D/g, ''); // Remove non-digits
    if (value.length > 16) {
        value = value.slice(0, 16);
    }
    this.value = value;
});

// Format Nomor KK - hanya angka
document.querySelector('input[name="nomorKK"]').addEventListener('input', function(e) {
    let value = this.value.replace(/\D/g, '');
    if (value.length > 16) {
        value = value.slice(0, 16);
    }
    this.value = value;
});

// Format BPJS - hanya angka
document.querySelector('input[name="nomorBPJS"]').addEventListener('input', function(e) {
    let value = this.value.replace(/\D/g, '');
    if (value.length > 13) {
        value = value.slice(0, 13);
    }
    this.value = value;
});

// Form validation - hanya cek required field
document.getElementById('formIdentitas').addEventListener('submit', function(e) {
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

// Remove invalid class on input
document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });
    
    input.addEventListener('change', function() {
        this.classList.remove('is-invalid');
    });
});

// Prevent non-numeric input untuk field angka
const numericFields = ['nik', 'nomorKK', 'nomorBPJS'];
numericFields.forEach(fieldName => {
    const field = document.querySelector(`input[name="${fieldName}"]`);
    if (field) {
        field.addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab' && e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') {
                e.preventDefault();
            }
        });
    }
});
</script>

<?php include '../../../includes/footer.php'; ?>