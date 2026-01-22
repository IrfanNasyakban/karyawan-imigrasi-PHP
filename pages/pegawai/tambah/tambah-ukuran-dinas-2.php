<?php
require_once '../../../config/database.php';
require_once '../../../includes/check_login.php';

$page_title = 'Tambah Data Ukuran Dinas';

// Get ID Pegawai dari parameter URL atau POST
$idPegawai = isset($_GET['idPegawai']) ? $_GET['idPegawai'] : (isset($_POST['idPegawai']) ? $_POST['idPegawai'] : '');

if (isset($_POST['submit'])) {
    $idPegawai = mysqli_real_escape_string($conn, $_POST['idPegawai'] ?? '');
    $ukuranPadDivamot = mysqli_real_escape_string($conn, $_POST['ukuranPadDivamot'] ?? '');
    $ukuranSepatu = mysqli_real_escape_string($conn, $_POST['ukuranSepatu'] ?? '');
    $ukuranTopi = mysqli_real_escape_string($conn, $_POST['ukuranTopi'] ?? '');

    if ($idPegawai === '') {
        $error = "ID Pegawai kosong. Pastikan alur tambah pegawai benar.";
    } else {
        $query = "INSERT INTO ukuran
            (idPegawai, ukuranPadDivamot, ukuranSepatu, ukuranTopi)
            VALUES
            ('$idPegawai', '$ukuranPadDivamot', '$ukuranSepatu', '$ukuranTopi')";

        if (mysqli_query($conn, $query)) {
            header("Location: ../list-ukuran-dinas.php");
            exit();
        } else {
            $error = "Gagal menambahkan data: " . mysqli_error($conn);
        }
    }
}

// Get all pegawai data for dropdown
$queryAllPegawai = "SELECT p.idPegawai, p.namaDenganGelar, p.nip 
                    FROM pegawai p
                    LEFT JOIN ukuran uk ON p.idPegawai = uk.idPegawai
                    WHERE uk.idPegawai IS NULL
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
            <h2><i class="fas fa-ruler-combined me-2"></i>Tambah Data Ukuran Dinas</h2>
            <p>Formulir Penambahan Data Ukuran Seragam & Perlengkapan Dinas - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-ruler-combined page-header-icon d-none d-md-block"></i>
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
        <form method="POST" action="" id="formUkuran">
            
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
                            <small class="form-text">Pilih pegawai untuk menambahkan data ukuran dinas</small>
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

            <!-- Section: Ukuran Seragam & Perlengkapan Dinas -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-tshirt"></i>
                    <h5>Ukuran Seragam & Perlengkapan Dinas</h5>
                </div>
                <div class="form-section-body">
                    <div class="alert alert-info-custom mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Data ukuran ini digunakan untuk pembuatan seragam dinas dan perlengkapan kedinasan lainnya. Pastikan ukuran yang diinput akurat.
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                Ukuran PDH/PDL (Pakaian Dinas) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-tshirt"></i>
                                </span>
                                <select name="ukuranPadDivamot" class="form-control" required>
                                    <option value="" disabled selected>Pilih Ukuran</option>
                                    <option value="XS">XS (Extra Small)</option>
                                    <option value="S">S (Small)</option>
                                    <option value="M">M (Medium)</option>
                                    <option value="L">L (Large)</option>
                                    <option value="XL">XL (Extra Large)</option>
                                    <option value="XXL">XXL (Double XL)</option>
                                    <option value="XXXL">XXXL (Triple XL)</option>
                                    <option value="XXXXL">XXXXL (Quadruple XL)</option>
                                </select>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                PDH: Pakaian Dinas Harian, PDL: Pakaian Dinas Lapangan
                            </small>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                Ukuran Sepatu <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-shoe-prints"></i>
                                </span>
                                <select name="ukuranSepatu" class="form-control" required>
                                    <option value="" disabled selected>Pilih Ukuran</option>
                                    <?php for($i = 36; $i <= 46; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Ukuran sepatu dinas dalam satuan Eropa (EU)
                            </small>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                Ukuran Topi <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-hat-cowboy"></i>
                                </span>
                                <select name="ukuranTopi" class="form-control" required>
                                    <option value="" disabled selected>Pilih Ukuran</option>
                                    <?php for($i = 52; $i <= 62; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?> cm</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Ukuran lingkar kepala dalam centimeter (cm)
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" name="submit" class="btn-submit">
                    <i class="fas fa-arrow-right me-2"></i>Simpan
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

// Auto scroll to active step
document.addEventListener('DOMContentLoaded', function() {
    const progressSteps = document.querySelector('.progress-steps');
    const activeStep = document.querySelector('.step.active');
    
    if (progressSteps && activeStep) {
        const scrollLeft = activeStep.offsetLeft - (progressSteps.offsetWidth / 2) + (activeStep.offsetWidth / 2);
        progressSteps.scrollTo({
            left: scrollLeft,
            behavior: 'smooth'
        });
    }
    
    progressSteps.addEventListener('scroll', function() {
        const isScrolledToEnd = this.scrollLeft + this.clientWidth >= this.scrollWidth - 10;
        if (isScrolledToEnd) {
            this.classList.add('scrolled-end');
        } else {
            this.classList.remove('scrolled-end');
        }
    });
});

// Form validation
document.getElementById('formUkuran').addEventListener('submit', function(e) {
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
        alert('Mohon lengkapi semua ukuran yang diperlukan!');
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
</script>

<?php include '../../../includes/footer.php'; ?>