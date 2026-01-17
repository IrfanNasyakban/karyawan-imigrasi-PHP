<?php
require_once '../../../config/database.php';

$page_title = 'Edit Data Pegawai';

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../list-pegawai.php?error=invalid_request");
    exit();
}

$idPegawai = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data pegawai berdasarkan ID
$query = "SELECT * FROM pegawai WHERE idPegawai = '$idPegawai'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: ../list-pegawai.php?error=tidak_ditemukan");
    exit();
}

$pegawai = mysqli_fetch_assoc($result);

// Proses update data
if (isset($_POST['submit'])) {
    $nip = mysqli_real_escape_string($conn, $_POST['nip']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $gelarDepan = mysqli_real_escape_string($conn, $_POST['gelarDepan']);
    $gelarBelakang = mysqli_real_escape_string($conn, $_POST['gelarBelakang']);
    $tempatLahir = mysqli_real_escape_string($conn, $_POST['tempatLahir']);
    $tanggalLahir = mysqli_real_escape_string($conn, $_POST['tanggalLahir']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $agama = mysqli_real_escape_string($conn, $_POST['agama']);
    $emailPribadi = mysqli_real_escape_string($conn, $_POST['emailPribadi']);
    $emailDinas = mysqli_real_escape_string($conn, $_POST['emailDinas']);
    $noHp = mysqli_real_escape_string($conn, $_POST['noHp']);
    $hobi = mysqli_real_escape_string($conn, $_POST['hobi']);
    $statusPegawai = mysqli_real_escape_string($conn, $_POST['statusPegawai']);
    
    // Generate namaDenganGelar
    $namaDenganGelar = trim($gelarDepan . ' ' . $nama . ' ' . $gelarBelakang);
    
    $queryUpdate = "UPDATE pegawai SET 
                    nip = '$nip',
                    nama = '$nama',
                    gelarDepan = '$gelarDepan',
                    gelarBelakang = '$gelarBelakang',
                    namaDenganGelar = '$namaDenganGelar',
                    tempatLahir = '$tempatLahir',
                    tanggalLahir = '$tanggalLahir',
                    gender = '$gender',
                    agama = '$agama',
                    emailPribadi = '$emailPribadi',
                    emailDinas = '$emailDinas',
                    noHp = '$noHp',
                    hobi = '$hobi',
                    statusPegawai = '$statusPegawai'
                    WHERE idPegawai = '$idPegawai'";
    
    if (mysqli_query($conn, $queryUpdate)) {
        header("Location: ../list-pegawai.php?message=edit");
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
            <h2><i class="fas fa-user-edit me-2"></i>Edit Data Pegawai</h2>
            <p>Formulir Edit Data Pegawai - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-user-edit page-header-icon d-none d-md-block"></i>
    </div>

    <!-- Alert Error -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Info Card -->
    <div class="info-card mb-4">
        <div class="info-card-header">
            <i class="fas fa-info-circle"></i>
            <span>Informasi Pegawai yang akan diedit</span>
        </div>
        <div class="info-card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-id-card"></i> NIP
                        </div>
                        <div class="info-value"><?php echo htmlspecialchars($pegawai['nip']); ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-user"></i> Nama Lengkap
                        </div>
                        <div class="info-value"><?php echo htmlspecialchars($pegawai['namaDenganGelar']); ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-toggle-on"></i> Status
                        </div>
                        <div class="info-value">
                            <span class="badge <?php echo $pegawai['statusPegawai'] == 1 ? 'bg-success' : 'bg-danger'; ?>">
                                <?php echo $pegawai['statusPegawai'] == 1 ? 'Aktif' : 'Tidak Aktif'; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Edit Card -->
    <div class="form-card">
        <form method="POST" action="" id="formPegawai">
            
            <!-- Section 1: Data Pribadi -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-user-circle"></i>
                    <h5>Data Pribadi</h5>
                </div>
                <div class="form-section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                NIP <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-id-card"></i>
                                </span>
                                <input type="text" 
                                       name="nip" 
                                       class="form-control" 
                                       placeholder="Masukkan NIP"
                                       value="<?php echo htmlspecialchars($pegawai['nip']); ?>"
                                       required>
                            </div>
                            <small class="form-text">Nomor Induk Pegawai</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" 
                                       name="nama" 
                                       class="form-control" 
                                       placeholder="Masukkan Nama Lengkap"
                                       value="<?php echo htmlspecialchars($pegawai['nama']); ?>"
                                       required>
                            </div>
                            <small class="form-text">Nama tanpa gelar</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gelar Depan</label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </span>
                                <input type="text" 
                                       name="gelarDepan" 
                                       class="form-control" 
                                       placeholder="Contoh: Dr., Ir., Prof."
                                       value="<?php echo htmlspecialchars($pegawai['gelarDepan']); ?>">
                            </div>
                            <small class="form-text">Gelar akademik depan (opsional)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gelar Belakang</label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </span>
                                <input type="text" 
                                       name="gelarBelakang" 
                                       class="form-control" 
                                       placeholder="Contoh: S.H., M.Si., Ph.D"
                                       value="<?php echo htmlspecialchars($pegawai['gelarBelakang']); ?>">
                            </div>
                            <small class="form-text">Gelar akademik belakang (opsional)</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Tempat Lahir <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <input type="text" 
                                       name="tempatLahir" 
                                       class="form-control" 
                                       placeholder="Masukkan Tempat Lahir"
                                       value="<?php echo htmlspecialchars($pegawai['tempatLahir']); ?>"
                                       required>
                            </div>
                            <small class="form-text">Kota atau kabupaten kelahiran</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Tanggal Lahir <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" 
                                       name="tanggalLahir" 
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($pegawai['tanggalLahir']); ?>"
                                       required>
                            </div>
                            <small class="form-text">Format: Tahun-Bulan-Tanggal</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Jenis Kelamin <span class="text-danger">*</span>
                            </label>
                            <div class="radio-group">
                                <label class="radio-option">
                                    <input type="radio" 
                                           name="gender" 
                                           value="Laki-laki" 
                                           <?php echo ($pegawai['gender'] == 'Laki-laki') ? 'checked' : ''; ?>
                                           required>
                                    <span class="radio-label">
                                        <i class="fas fa-mars text-primary"></i>
                                        Laki-laki
                                    </span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" 
                                           name="gender" 
                                           value="Perempuan" 
                                           <?php echo ($pegawai['gender'] == 'Perempuan') ? 'checked' : ''; ?>
                                           required>
                                    <span class="radio-label">
                                        <i class="fas fa-venus text-danger"></i>
                                        Perempuan
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Agama <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-praying-hands"></i>
                                </span>
                                <select name="agama" class="form-control" required>
                                    <option value="" disabled>Pilih Agama</option>
                                    <option value="Islam" <?php echo ($pegawai['agama'] == 'Islam') ? 'selected' : ''; ?>>Islam</option>
                                    <option value="Kristen Protestan" <?php echo ($pegawai['agama'] == 'Kristen Protestan') ? 'selected' : ''; ?>>Kristen Protestan</option>
                                    <option value="Katolik" <?php echo ($pegawai['agama'] == 'Katolik') ? 'selected' : ''; ?>>Katolik</option>
                                    <option value="Hindu" <?php echo ($pegawai['agama'] == 'Hindu') ? 'selected' : ''; ?>>Hindu</option>
                                    <option value="Buddha" <?php echo ($pegawai['agama'] == 'Buddha') ? 'selected' : ''; ?>>Buddha</option>
                                    <option value="Khonghucu" <?php echo ($pegawai['agama'] == 'Khonghucu') ? 'selected' : ''; ?>>Khonghucu</option>
                                </select>
                            </div>
                            <small class="form-text">Pilih agama yang dianut</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Kontak -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-address-book"></i>
                    <h5>Data Kontak</h5>
                </div>
                <div class="form-section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Email Pribadi <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" 
                                       name="emailPribadi" 
                                       class="form-control" 
                                       placeholder="contoh@email.com"
                                       value="<?php echo htmlspecialchars($pegawai['emailPribadi']); ?>"
                                       required>
                            </div>
                            <small class="form-text">Email untuk keperluan pribadi</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Email Dinas <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" 
                                       name="emailDinas" 
                                       class="form-control" 
                                       placeholder="contoh@imigrasi.go.id"
                                       value="<?php echo htmlspecialchars($pegawai['emailDinas']); ?>"
                                       required>
                            </div>
                            <small class="form-text">Email untuk keperluan dinas</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Nomor Telepon <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input type="text" 
                                       name="noHp" 
                                       class="form-control" 
                                       placeholder="08xx-xxxx-xxxx"
                                       value="<?php echo htmlspecialchars($pegawai['noHp']); ?>"
                                       required>
                            </div>
                            <small class="form-text">Nomor telepon/HP yang dapat dihubungi</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Hobi <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-heart"></i>
                                </span>
                                <input type="text" 
                                       name="hobi" 
                                       class="form-control" 
                                       placeholder="Masukkan Hobi"
                                       value="<?php echo htmlspecialchars($pegawai['hobi']); ?>"
                                       required>
                            </div>
                            <small class="form-text">Hobi atau kegiatan yang disukai</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Status Pegawai -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-user-check"></i>
                    <h5>Status Kepegawaian</h5>
                </div>
                <div class="form-section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Status Pegawai <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-toggle-on"></i>
                                </span>
                                <select name="statusPegawai" class="form-control" required>
                                    <option value="" disabled>Pilih Status</option>
                                    <option value="1" <?php echo ($pegawai['statusPegawai'] == 1) ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="0" <?php echo ($pegawai['statusPegawai'] == 0) ? 'selected' : ''; ?>>Tidak Aktif</option>
                                </select>
                            </div>
                            <small class="form-text">Status kepegawaian saat ini</small>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Nama dengan gelar akan dibuat otomatis dari Gelar Depan + Nama + Gelar Belakang
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="../list-pegawai.php" class="btn-cancel">
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
document.getElementById('formPegawai').addEventListener('submit', function(e) {
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
});

// Email validation
document.querySelectorAll('input[type="email"]').forEach(emailInput => {
    emailInput.addEventListener('blur', function() {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (this.value && !emailPattern.test(this.value)) {
            this.classList.add('is-invalid');
            alert('Format email tidak valid!');
        }
    });
});

// Phone number validation
document.querySelector('input[name="noHp"]').addEventListener('input', function() {
    // Remove non-numeric characters except + and -
    this.value = this.value.replace(/[^0-9+-]/g, '');
});
</script>

<?php include '../../../includes/footer.php'; ?>