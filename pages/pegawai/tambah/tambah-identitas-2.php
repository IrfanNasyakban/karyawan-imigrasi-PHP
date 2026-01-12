<?php
require_once '../../../config/database.php';

$page_title = 'Tambah Data Identitas';

// Get ID Pegawai dari parameter URL atau POST
$idPegawai = isset($_GET['idPegawai']) ? $_GET['idPegawai'] : (isset($_POST['idPegawai']) ? $_POST['idPegawai'] : '');

if (isset($_POST['submit'])) {
    $idPegawai = mysqli_real_escape_string($conn, $_POST['idPegawai'] ?? '');
    $nik = mysqli_real_escape_string($conn, $_POST['nik'] ?? '');
    $nomorKK = mysqli_real_escape_string($conn, $_POST['nomorKK'] ?? '');
    $nomorBPJS = mysqli_real_escape_string($conn, $_POST['nomorBPJS'] ?? '');
    $nomorTaspen = mysqli_real_escape_string($conn, $_POST['nomorTaspen'] ?? '');

    if ($idPegawai === '') {
        $error = "ID Pegawai kosong. Pastikan alur tambah pegawai benar.";
    } else {
        $query = "INSERT INTO identitas
            (idPegawai, nik, nomorKK, nomorBPJS, nomorTaspen)
            VALUES
            ('$idPegawai', '$nik', '$nomorKK', '$nomorBPJS', '$nomorTaspen')";

        if (mysqli_query($conn, $query)) {
            header("Location: ../list-identitas.php");
            exit();
        } else {
            $error = "Gagal menambahkan data: " . mysqli_error($conn);
        }
    }
}

// Get all pegawai data for dropdown
$queryAllPegawai = "SELECT p.idPegawai, p.namaDenganGelar, p.nip 
                    FROM pegawai p
                    LEFT JOIN identitas id ON p.idPegawai = id.idPegawai
                    WHERE id.idPegawai IS NULL
                    ORDER BY p.namaDenganGelar ASC";
$resultAllPegawai = mysqli_query($conn, $queryAllPegawai);

// Cek apakah ada pegawai yang tersedia
$countAvailable = mysqli_num_rows($resultAllPegawai);

// Get selected pegawai data
$namaDenganGelar = '';
$nip = '';
if ($idPegawai !== '') {
    $query = "SELECT namaDenganGelar, nip 
              FROM pegawai 
              WHERE idPegawai = '" . mysqli_real_escape_string($conn, $idPegawai) . "'";
    
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $pegawai = mysqli_fetch_assoc($result);
        $namaDenganGelar = $pegawai['namaDenganGelar'];
        $nip = $pegawai['nip'];
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
            <h2><i class="fas fa-address-card me-2"></i>Tambah Data Identitas</h2>
            <p>Formulir Penambahan Data Identitas & Nomor Kependudukan - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
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

    <!-- Info Pegawai Card (hanya tampil jika pegawai sudah dipilih) -->
    <?php if ($idPegawai !== '' && $namaDenganGelar !== ''): ?>
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
                <div class="info-value"><?php echo htmlspecialchars($namaDenganGelar); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-id-card"></i> NIP
                </div>
                <div class="info-value"><?php echo htmlspecialchars($nip); ?></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="" id="formIdentitas">
            
            <!-- Section 0: Pilih Pegawai -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-user-circle"></i>
                    <h5>Pilih Pegawai</h5>
                </div>
                <div class="form-section-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Nama Pegawai <span class="text-danger">*</span>
                            </label>
                            <?php if ($countAvailable > 0): ?>
                                <div class="input-group">
                                    <span class="input-icon">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <select name="idPegawai" id="idPegawai" class="form-control" required onchange="updatePegawaiInfo(this)">
                                        <option value="" selected disabled>-- Pilih Pegawai (<?php echo $countAvailable; ?> pegawai tersedia) --</option>
                                        <?php while($row = mysqli_fetch_assoc($resultAllPegawai)): ?>
                                            <option value="<?php echo htmlspecialchars($row['idPegawai']); ?>" 
                                                    data-nip="<?php echo htmlspecialchars($row['nip']); ?>"
                                                    data-nama="<?php echo htmlspecialchars($row['namaDenganGelar']); ?>"
                                                    <?php echo ($idPegawai == $row['idPegawai']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($row['namaDenganGelar']) . ' - ' . htmlspecialchars($row['nip']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <small class="form-text text-success">
                                    <i class="fas fa-info-circle"></i> Menampilkan pegawai yang belum memiliki data kepegawaian
                                </small>
                            <?php else: ?>
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Tidak Ada Pegawai Tersedia</strong>
                                <p class="mb-0">Semua pegawai sudah memiliki data kepegawaian. Silakan kelola data kepegawaian yang ada di halaman <a href="../list-kepegawaian.php" class="alert-link">Daftar Kepegawaian</a>.</p>
                            </div>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-search"></i>
                                </span>
                                <select name="idPegawai" id="idPegawai" class="form-control" disabled>
                                    <option value="">-- Tidak ada pegawai tersedia --</option>
                                </select>
                            </div>
                            <?php endif; ?>
                            <small class="form-text">Pilih pegawai untuk menambahkan data identitas</small>
                        </div>
                    </div>
                    
                    <!-- Info pegawai yang dipilih (ditampilkan secara dinamis) -->
                    <div id="pegawaiInfoPreview" class="mt-3" style="display: <?php echo ($idPegawai !== '' ? 'block' : 'none'); ?>;">
                        <div class="alert alert-info mb-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong><i class="fas fa-user"></i> Nama:</strong>
                                    <span id="previewNama"><?php echo htmlspecialchars($namaDenganGelar); ?></span>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-id-card"></i> NIP:</strong>
                                    <span id="previewNip"><?php echo htmlspecialchars($nip); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                       pattern="[0-9]{16}"
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
                                       pattern="[0-9]{16}"
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
                                       pattern="[0-9]{13}"
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
                <button type="submit" name="submit" class="btn-submit">
                    <i class="fas fa-arrow-right me-2"></i>Selanjutnya
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Function to update pegawai info preview
function updatePegawaiInfo(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const previewDiv = document.getElementById('pegawaiInfoPreview');
    
    if (selectedOption.value) {
        const nama = selectedOption.getAttribute('data-nama');
        const nip = selectedOption.getAttribute('data-nip');
        
        document.getElementById('previewNama').textContent = nama;
        document.getElementById('previewNip').textContent = nip;
        previewDiv.style.display = 'block';
    } else {
        previewDiv.style.display = 'none';
    }
}

// Format NIK - Auto add separator (optional)
document.querySelector('input[name="nik"]').addEventListener('input', function(e) {
    let value = this.value.replace(/\D/g, ''); // Remove non-digits
    if (value.length > 16) {
        value = value.slice(0, 16);
    }
    this.value = value;
});

// Format Nomor KK
document.querySelector('input[name="nomorKK"]').addEventListener('input', function(e) {
    let value = this.value.replace(/\D/g, '');
    if (value.length > 16) {
        value = value.slice(0, 16);
    }
    this.value = value;
});

// Format BPJS
document.querySelector('input[name="nomorBPJS"]').addEventListener('input', function(e) {
    let value = this.value.replace(/\D/g, '');
    if (value.length > 13) {
        value = value.slice(0, 13);
    }
    this.value = value;
});

// Validate NIK (16 digits)
document.querySelector('input[name="nik"]').addEventListener('blur', function() {
    if (this.value.length > 0 && this.value.length !== 16) {
        alert('NIK harus terdiri dari 16 digit angka!');
        this.focus();
    }
});

// Validate Nomor KK (16 digits)
document.querySelector('input[name="nomorKK"]').addEventListener('blur', function() {
    if (this.value.length > 0 && this.value.length !== 16) {
        alert('Nomor KK harus terdiri dari 16 digit angka!');
        this.focus();
    }
});

// Validate BPJS (13 digits)
document.querySelector('input[name="nomorBPJS"]').addEventListener('blur', function() {
    if (this.value.length > 0 && this.value.length !== 13) {
        alert('Nomor BPJS harus terdiri dari 13 digit angka!');
        this.focus();
    }
});

// Form validation
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
    
    // Check NIK length
    const nik = document.querySelector('input[name="nik"]');
    if (nik.value.length !== 16) {
        isValid = false;
        nik.classList.add('is-invalid');
        alert('NIK harus terdiri dari 16 digit!');
    }
    
    // Check KK length
    const nomorKK = document.querySelector('input[name="nomorKK"]');
    if (nomorKK.value.length !== 16) {
        isValid = false;
        nomorKK.classList.add('is-invalid');
        alert('Nomor KK harus terdiri dari 16 digit!');
    }
    
    // Check BPJS length
    const nomorBPJS = document.querySelector('input[name="nomorBPJS"]');
    if (nomorBPJS.value.length !== 13) {
        isValid = false;
        nomorBPJS.classList.add('is-invalid');
        alert('Nomor BPJS harus terdiri dari 13 digit!');
    }
    
    if (!isValid) {
        e.preventDefault();
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
</script>

<?php include '../../../includes/footer.php'; ?>