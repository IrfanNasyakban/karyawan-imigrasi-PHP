<?php
require_once '../../config/database.php';

$page_title = 'Tambah Data Pegawai';

if (isset($_POST['submit'])) {
    $nip          = mysqli_real_escape_string($conn, $_POST['nip']);
    $nama         = mysqli_real_escape_string($conn, $_POST['nama']);
    $gelarDepan   = mysqli_real_escape_string($conn, $_POST['gelarDepan']);
    $gelarBelakang= mysqli_real_escape_string($conn, $_POST['gelarBelakang']);
    $tempatLahir  = mysqli_real_escape_string($conn, $_POST['tempatLahir']);
    $tanggalLahir = mysqli_real_escape_string($conn, $_POST['tanggalLahir']);
    $gender       = mysqli_real_escape_string($conn, $_POST['gender']);
    $agama        = mysqli_real_escape_string($conn, $_POST['agama']);
    $statusPegawai = (int) $_POST['statusPegawai'];
    $emailPribadi = mysqli_real_escape_string($conn, $_POST['emailPribadi']);
    $emailDinas   = mysqli_real_escape_string($conn, $_POST['emailDinas']);
    $noHp         = mysqli_real_escape_string($conn, $_POST['noHp']);
    $hobi         = mysqli_real_escape_string($conn, $_POST['hobi']);

    $namaDenganGelar = trim(
        ($gelarDepan !== '' ? $gelarDepan . ' ' : '') .
        $nama .
        ($gelarBelakang !== '' ? ', ' . $gelarBelakang : '')
    );
    $namaDenganGelar = mysqli_real_escape_string($conn, $namaDenganGelar);

    $query = "INSERT INTO pegawai 
        (nip, nama, gelarDepan, gelarBelakang, namaDenganGelar, tempatLahir, tanggalLahir, gender, agama, statusPegawai, emailPribadi, emailDinas, noHp, hobi)
        VALUES
        ('$nip', '$nama', '$gelarDepan', '$gelarBelakang', '$namaDenganGelar', '$tempatLahir', '$tanggalLahir', '$gender', '$agama', $statusPegawai, '$emailPribadi', '$emailDinas', '$noHp', '$hobi')";

    if (mysqli_query($conn, $query)) {
        $idPegawai = mysqli_insert_id($conn);
        header("Location: tambah-kepegawaian.php?idPegawai=$idPegawai&message=tambah");
        exit();
    } else {
        $error = "Gagal menambahkan data: " . mysqli_error($conn);
    }
}

include '../../includes/sidebar.php';
?>

<link rel="stylesheet" href="../../assets/css/style-tables.css">
<link rel="stylesheet" href="../../assets/css/style-form.css">

<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h2><i class="fas fa-user-plus me-2"></i>Tambah Data Pegawai</h2>
            <p>Formulir Penambahan Data Pegawai Baru - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-user-plus page-header-icon d-none d-md-block"></i>
    </div>

    <!-- Alert Error -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
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
                                       required>
                            </div>
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
                                       required>
                            </div>
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
                                       placeholder="Contoh: Dr., Ir., Prof.">
                            </div>
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
                                       placeholder="Contoh: S.H., M.Si., Ph.D">
                            </div>
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
                                       required>
                            </div>
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
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Jenis Kelamin <span class="text-danger">*</span>
                            </label>
                            <div class="radio-group">
                                <label class="radio-option">
                                    <input type="radio" name="gender" value="male" required>
                                    <span class="radio-label">
                                        <i class="fas fa-mars text-primary"></i>
                                        Laki-laki
                                    </span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="gender" value="female" required>
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
                                    <option value="" disabled selected>Pilih Agama</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen Protestan">Kristen Protestan</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Khonghucu">Khonghucu</option>
                                </select>
                            </div>
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
                                       required>
                            </div>
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
                                       required>
                            </div>
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
                                       required>
                            </div>
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
                                       required>
                            </div>
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
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="list-pegawai.php" class="btn-cancel">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
                <button type="submit" name="submit" class="btn-submit">
                    <i class="fas fa-arrow-right me-2"></i>Selanjutnya
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
</script>

<?php include '../../includes/footer.php'; ?>