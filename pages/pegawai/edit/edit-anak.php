<?php
require_once '../../../config/database.php';

$page_title = 'Edit Data Anak';

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../list-anak.php?error=invalid_request");
    exit();
}

$idAnak = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data Anak dan pegawai berdasarkan ID
$query = "SELECT a.*, p.nip, p.namaDenganGelar, p.statusPegawai 
          FROM anak a
          LEFT JOIN pegawai p ON a.idPegawai = p.idPegawai
          WHERE a.idAnak = '$idAnak'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: ../list-anak.php?error=tidak_ditemukan");
    exit();
}

$anak = mysqli_fetch_assoc($result);

// Proses update data
if (isset($_POST['submit'])) {
    $namaAnak = mysqli_real_escape_string($conn, $_POST['namaAnak']);
    
    $queryUpdate = "UPDATE anak SET 
                    namaAnak = '$namaAnak'
                    WHERE idAnak = '$idAnak'";
    
    if (mysqli_query($conn, $queryUpdate)) {
        header("Location: ../list-anak.php?message=edit");
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
            <h2><i class="fas fa-child me-2"></i>Edit Data Anak</h2>
            <p>Formulir Edit Data Anak Pegawai - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-child page-header-icon d-none d-md-block"></i>
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
            <span>Informasi Pegawai (Orang Tua)</span>
        </div>
        <div class="info-card-body">
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-user"></i> Nama Lengkap
                </div>
                <div class="info-value"><?php echo htmlspecialchars($anak['namaDenganGelar']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-id-card"></i> NIP
                </div>
                <div class="info-value"><?php echo htmlspecialchars($anak['nip']); ?></div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="" id="formAnak">

            <!-- Section: Data Anak -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-baby"></i>
                    <h5>Data Anak</h5>
                </div>
                <div class="form-section-body">
                    <div class="alert alert-info-custom mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Form ini digunakan untuk mengubah nama anak. Pastikan nama yang diinput sesuai dengan dokumen resmi.
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">
                                <i class="fas fa-baby me-1 text-primary"></i>
                                Nama Anak <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-child"></i>
                                </span>
                                <input type="text" 
                                       name="namaAnak" 
                                       class="form-control" 
                                       placeholder="Masukkan nama lengkap anak"
                                       value="<?php echo htmlspecialchars($anak['namaAnak']); ?>"
                                       required>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Nama lengkap anak sesuai akta kelahiran atau KK
                            </small>
                        </div>
                    </div>

                    <!-- Tips Box -->
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-light border">
                                <h6 class="mb-2"><i class="fas fa-lightbulb me-2 text-warning"></i>Tips Pengisian</h6>
                                <ul class="mb-0 small">
                                    <li>Gunakan huruf kapital di awal setiap kata (Title Case)</li>
                                    <li>Contoh yang benar: "Ahmad Fauzi Rahmadi"</li>
                                    <li>Hindari singkatan kecuali gelar (Bpk., Drs., dll.)</li>
                                    <li>Pastikan tidak ada typo atau kesalahan penulisan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="../list-anak.php" class="btn-cancel">
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
document.getElementById('formAnak').addEventListener('submit', function(e) {
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

// Auto-capitalize first letter of each word (Title Case)
document.querySelector('input[name="namaAnak"]').addEventListener('blur', function() {
    if (this.value) {
        // Convert to Title Case
        this.value = this.value.split(' ').map(word => {
            if (word.length > 0) {
                return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
            }
            return word;
        }).join(' ');
    }
});

// Real-time character counter (optional)
const namaAnakInput = document.querySelector('input[name="namaAnak"]');
if (namaAnakInput) {
    const maxLength = 225; // sesuai database varchar(225)
    
    namaAnakInput.addEventListener('input', function() {
        const currentLength = this.value.length;
        const remaining = maxLength - currentLength;
        
        // Create or update counter element
        let counter = this.parentElement.parentElement.querySelector('.char-counter');
        if (!counter) {
            counter = document.createElement('small');
            counter.className = 'char-counter text-muted';
            this.parentElement.parentElement.appendChild(counter);
        }
        
        if (remaining < 50) {
            counter.className = 'char-counter text-warning fw-bold';
            counter.innerHTML = `<i class="fas fa-exclamation-triangle me-1"></i>${remaining} karakter tersisa`;
        } else {
            counter.className = 'char-counter text-muted';
            counter.textContent = `${currentLength} / ${maxLength} karakter`;
        }
        
        // Prevent exceeding max length
        if (currentLength > maxLength) {
            this.value = this.value.substring(0, maxLength);
        }
    });
}

// Visual feedback for focus
namaAnakInput.addEventListener('focus', function() {
    this.parentElement.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
    this.parentElement.style.borderColor = '#3b82f6';
});

namaAnakInput.addEventListener('blur', function() {
    this.parentElement.style.boxShadow = '';
    this.parentElement.style.borderColor = '';
});

// Initialize character counter
if (namaAnakInput) {
    namaAnakInput.dispatchEvent(new Event('input'));
}
</script>

<?php include '../../../includes/footer.php'; ?>