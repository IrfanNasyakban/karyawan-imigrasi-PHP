<?php
require_once '../../../config/database.php';

$page_title = 'Edit Data Keluarga';

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../list-pasangan.php?error=invalid_request");
    exit();
}

$idPasangan = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data Pasangan dan pegawai berdasarkan ID
$query = "SELECT ps.*, p.nip, p.namaDenganGelar, p.statusPegawai 
          FROM pasangan ps
          LEFT JOIN pegawai p ON ps.idPegawai = p.idPegawai
          WHERE ps.idPasangan = '$idPasangan'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: ../list-pasangan.php?error=tidak_ditemukan");
    exit();
}

$pasangan = mysqli_fetch_assoc($result);

// Proses update data
if (isset($_POST['submit'])) {
    $namaPasangan = mysqli_real_escape_string($conn, $_POST['namaPasangan']);
    
    $queryUpdate = "UPDATE pasangan SET 
                    namaPasangan = '$namaPasangan'
                    WHERE idPasangan = '$idPasangan'";
    
    if (mysqli_query($conn, $queryUpdate)) {
        header("Location: ../list-pasangan.php?message=edit");
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
            <h2><i class="fas fa-users me-2"></i>Edit Data Keluarga</h2>
            <p>Formulir Edit Data Keluarga Pegawai - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-users page-header-icon d-none d-md-block"></i>
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
                <div class="info-value"><?php echo htmlspecialchars($pasangan['namaDenganGelar']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-id-card"></i> NIP
                </div>
                <div class="info-value"><?php echo htmlspecialchars($pasangan['nip']); ?></div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="" id="formKeluarga">

            <!-- Section 1: Data Pasangan -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-heart"></i>
                    <h5>Data Pasangan (Suami/Istri)</h5>
                </div>
                <div class="form-section-body">
                    <div class="alert alert-info-custom mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong> Jika pegawai belum menikah, silakan isi dengan tanda (-) atau tuliskan "Belum Menikah".
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Nama Pasangan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-user-friends"></i>
                                </span>
                                <input type="text" 
                                       name="namaPasangan" 
                                       class="form-control" 
                                       placeholder="Contoh: Ahmad Sulaiman, S.E. atau (-) jika belum menikah"
                                       value="<?php echo htmlspecialchars($pasangan['namaPasangan']); ?>"
                                       required>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Nama lengkap suami/istri sesuai KTP atau isi dengan (-) jika belum menikah
                            </small>
                        </div>
                    </div>

                    <!-- Status Pernikahan Info -->
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-light border">
                                <h6 class="mb-2"><i class="fas fa-ring me-2"></i>Status Pernikahan</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="statusInfo" id="menikah" 
                                                   <?php echo (strtolower($pasangan['namaPasangan']) != '-' && strtolower($pasangan['namaPasangan']) != 'belum menikah') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="menikah">
                                                <i class="fas fa-check-circle text-success"></i> Sudah Menikah
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="statusInfo" id="belumMenikah"
                                                   <?php echo (strtolower($pasangan['namaPasangan']) == '-' || strtolower($pasangan['namaPasangan']) == 'belum menikah') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="belumMenikah">
                                                <i class="fas fa-times-circle text-secondary"></i> Belum Menikah
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Jika memilih "Belum Menikah", sistem akan otomatis mengisi dengan tanda (-)
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="../list-pasangan.php" class="btn-cancel">
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
// Handle status pernikahan radio change
document.querySelectorAll('input[name="statusInfo"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const namaPasanganInput = document.querySelector('input[name="namaPasangan"]');
        
        if (this.id === 'belumMenikah') {
            namaPasanganInput.value = '-';
            namaPasanganInput.readOnly = true;
            namaPasanganInput.style.backgroundColor = '#f3f4f6';
        } else {
            if (namaPasanganInput.value === '-' || namaPasanganInput.value === 'Belum Menikah') {
                namaPasanganInput.value = '';
            }
            namaPasanganInput.readOnly = false;
            namaPasanganInput.style.backgroundColor = 'white';
            namaPasanganInput.focus();
        }
    });
});

// Initialize readonly state on page load
document.addEventListener('DOMContentLoaded', function() {
    const belumMenikahRadio = document.getElementById('belumMenikah');
    const namaPasanganInput = document.querySelector('input[name="namaPasangan"]');
    
    if (belumMenikahRadio.checked) {
        namaPasanganInput.readOnly = true;
        namaPasanganInput.style.backgroundColor = '#f3f4f6';
    }
});

// Form validation
document.getElementById('formKeluarga').addEventListener('submit', function(e) {
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

// Capitalize first letter of each word
document.querySelector('input[name="namaPasangan"]').addEventListener('blur', function() {
    if (this.value && this.value !== '-' && this.value !== 'Belum Menikah') {
        this.value = this.value.split(' ').map(word => {
            return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
        }).join(' ');
    }
});
</script>

<?php include '../../../includes/footer.php'; ?>