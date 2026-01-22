<?php
require_once '../../config/database.php';
require_once '../../includes/check_login.php';

$page_title = 'Tambah Data Ukuran Dinas';

$idPegawai = $_GET['idPegawai'] ?? null;
if (!$idPegawai) {
    header("Location: tambah-pegawai.php");
    exit();
}

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
            header("Location: tambah-keluarga.php?idPegawai=$idPegawai&message=tambah");
            exit();
        } else {
            $error = "Gagal menambahkan data: " . mysqli_error($conn);
        }
    }
}

// Get pegawai data
$query = "SELECT namaDenganGelar, nip 
          FROM pegawai 
          WHERE idPegawai = '" . mysqli_real_escape_string($conn, $idPegawai) . "'";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $pegawai = mysqli_fetch_assoc($result);
    $namaDenganGelar = $pegawai['namaDenganGelar'];
    $nip = $pegawai['nip'];
} else {
    die("Data pegawai dengan ID $idPegawai tidak ditemukan.");
}

include '../../includes/sidebar.php';
?>

<link rel="stylesheet" href="../../assets/css/style-tables.css">
<link rel="stylesheet" href="../../assets/css/style-form.css">

<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h2><i class="fas fa-ruler-combined me-2"></i>Tambah Data Ukuran Dinas</h2>
            <p>Formulir Penambahan Data Ukuran Seragam & Perlengkapan Dinas - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-ruler-combined page-header-icon d-none d-md-block"></i>
    </div>

    <!-- Progress Steps -->
    <div class="progress-steps mb-4">
        <div class="step completed">
            <div class="step-number">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Data Pegawai</div>
        </div>
        <div class="step-line completed"></div>
        <div class="step completed">
            <div class="step-number">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Data Kepegawaian</div>
        </div>
        <div class="step-line completed"></div>
        <div class="step completed">
            <div class="step-number">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Data Pangkat</div>
        </div>
        <div class="step-line completed"></div>
        <div class="step completed">
            <div class="step-number">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Data Alamat</div>
        </div>
        <div class="step-line completed"></div>
        <div class="step completed">
            <div class="step-number">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Data Identitas</div>
        </div>
        <div class="step-line completed"></div>
        <div class="step completed">
            <div class="step-number">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Data Rekening</div>
        </div>
        <div class="step-line completed"></div>
        <div class="step completed">
            <div class="step-number">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Data Pendidikan</div>
        </div>
        <div class="step-line completed"></div>
        <div class="step completed">
            <div class="step-number">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Data Fisik</div>
        </div>
        <div class="step-line completed"></div>
        <div class="step active">
            <div class="step-number">9</div>
            <div class="step-label">Data Ukuran</div>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-number">10</div>
            <div class="step-label">Data Keluarga</div>
        </div>
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

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="" id="formUkuran">
            <input type="hidden" name="idPegawai" value="<?php echo htmlspecialchars($idPegawai); ?>">
            
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
                    <i class="fas fa-arrow-right me-2"></i>Selanjutnya
                </button>
            </div>
        </form>
    </div>
</div>

<script>
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
});
</script>

<?php include '../../includes/footer.php'; ?>