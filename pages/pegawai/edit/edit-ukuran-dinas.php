<?php
require_once '../../../config/database.php';

$page_title = 'Edit Data Ukuran Dinas';

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../list-ukuran-dinas.php?error=invalid_request");
    exit();
}

$idUkuran = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data Ukuran dan pegawai berdasarkan ID
$query = "SELECT u.*, p.nip, p.namaDenganGelar, p.statusPegawai 
          FROM ukuran u
          LEFT JOIN pegawai p ON u.idPegawai = p.idPegawai
          WHERE u.idUkuran = '$idUkuran'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: ../list-ukuran-dinas.php?error=tidak_ditemukan");
    exit();
}

$ukuran = mysqli_fetch_assoc($result);

// Proses update data
if (isset($_POST['submit'])) {
    $ukuranPadDivamot = mysqli_real_escape_string($conn, $_POST['ukuranPadDivamot']);
    $ukuranSepatu = mysqli_real_escape_string($conn, $_POST['ukuranSepatu']);
    $ukuranTopi = mysqli_real_escape_string($conn, $_POST['ukuranTopi']);
    
    $queryUpdate = "UPDATE ukuran SET 
                    ukuranPadDivamot = '$ukuranPadDivamot',
                    ukuranSepatu = '$ukuranSepatu',
                    ukuranTopi = '$ukuranTopi'
                    WHERE idUkuran = '$idUkuran'";
    
    if (mysqli_query($conn, $queryUpdate)) {
        header("Location: ../list-ukuran-dinas.php?message=edit");
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
            <h2><i class="fas fa-ruler-combined me-2"></i>Edit Data Ukuran Dinas</h2>
            <p>Formulir Edit Data Ukuran Seragam & Perlengkapan Dinas - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
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
                <div class="info-value"><?php echo htmlspecialchars($ukuran['namaDenganGelar']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-id-card"></i> NIP
                </div>
                <div class="info-value"><?php echo htmlspecialchars($ukuran['nip']); ?></div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="" id="formUkuran">

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
                                    <option value="" disabled>Pilih Ukuran</option>
                                    <option value="XS" <?php echo ($ukuran['ukuranPadDivamot'] == 'XS') ? 'selected' : ''; ?>>XS (Extra Small)</option>
                                    <option value="S" <?php echo ($ukuran['ukuranPadDivamot'] == 'S') ? 'selected' : ''; ?>>S (Small)</option>
                                    <option value="M" <?php echo ($ukuran['ukuranPadDivamot'] == 'M') ? 'selected' : ''; ?>>M (Medium)</option>
                                    <option value="L" <?php echo ($ukuran['ukuranPadDivamot'] == 'L') ? 'selected' : ''; ?>>L (Large)</option>
                                    <option value="XL" <?php echo ($ukuran['ukuranPadDivamot'] == 'XL') ? 'selected' : ''; ?>>XL (Extra Large)</option>
                                    <option value="XXL" <?php echo ($ukuran['ukuranPadDivamot'] == 'XXL') ? 'selected' : ''; ?>>XXL (Double XL)</option>
                                    <option value="XXXL" <?php echo ($ukuran['ukuranPadDivamot'] == 'XXXL') ? 'selected' : ''; ?>>XXXL (Triple XL)</option>
                                    <option value="XXXXL" <?php echo ($ukuran['ukuranPadDivamot'] == 'XXXXL') ? 'selected' : ''; ?>>XXXXL (Quadruple XL)</option>
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
                                    <option value="" disabled>Pilih Ukuran</option>
                                    <?php for($i = 36; $i <= 46; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php echo ($ukuran['ukuranSepatu'] == $i) ? 'selected' : ''; ?>>
                                            <?php echo $i; ?>
                                        </option>
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
                                    <option value="" disabled>Pilih Ukuran</option>
                                    <?php for($i = 52; $i <= 62; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php echo ($ukuran['ukuranTopi'] == $i) ? 'selected' : ''; ?>>
                                            <?php echo $i; ?> cm
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Ukuran lingkar kepala dalam centimeter (cm)
                            </small>
                        </div>
                    </div>

                    <!-- Visual Size Guide -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-light border">
                                <h6 class="mb-3"><i class="fas fa-ruler me-2"></i>Panduan Ukuran</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Ukuran Pakaian:</strong>
                                        <ul class="small mb-0">
                                            <li>XS: Lingkar Dada 80-85 cm</li>
                                            <li>S: Lingkar Dada 86-90 cm</li>
                                            <li>M: Lingkar Dada 91-96 cm</li>
                                            <li>L: Lingkar Dada 97-102 cm</li>
                                            <li>XL: Lingkar Dada 103-108 cm</li>
                                            <li>XXL: Lingkar Dada 109-115 cm</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Ukuran Sepatu:</strong>
                                        <ul class="small mb-0">
                                            <li>36-38: Kaki kecil</li>
                                            <li>39-41: Kaki sedang</li>
                                            <li>42-44: Kaki besar</li>
                                            <li>45-46: Kaki sangat besar</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Ukuran Topi:</strong>
                                        <ul class="small mb-0">
                                            <li>52-54 cm: Kepala kecil</li>
                                            <li>55-57 cm: Kepala sedang</li>
                                            <li>58-60 cm: Kepala besar</li>
                                            <li>61-62 cm: Kepala sangat besar</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="../list-ukuran-dinas.php" class="btn-cancel">
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

// Highlight selected sizes
document.querySelectorAll('select[name^="ukuran"]').forEach(select => {
    select.addEventListener('change', function() {
        if (this.value) {
            this.style.fontWeight = 'bold';
            this.style.color = '#059669';
        } else {
            this.style.fontWeight = 'normal';
            this.style.color = '';
        }
    });
});

// Initialize highlight on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('select[name^="ukuran"]').forEach(select => {
        if (select.value) {
            select.style.fontWeight = 'bold';
            select.style.color = '#059669';
        }
    });
});
</script>

<?php include '../../../includes/footer.php'; ?>