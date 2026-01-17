<?php
require_once '../../../config/database.php';

$page_title = 'Edit Data Rekening';

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../list-rekening.php?error=invalid_request");
    exit();
}

$idRekening = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data Rekening dan pegawai berdasarkan ID
$query = "SELECT r.*, p.nip, p.namaDenganGelar, p.statusPegawai 
          FROM rekening r
          LEFT JOIN pegawai p ON r.idPegawai = p.idPegawai
          WHERE r.idRekening = '$idRekening'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: ../list-rekening.php?error=tidak_ditemukan");
    exit();
}

$rekening = mysqli_fetch_assoc($result);

// Proses update data
if (isset($_POST['submit'])) {
    $nomorRekGaji = mysqli_real_escape_string($conn, $_POST['nomorRekGaji']);
    $namaBank = mysqli_real_escape_string($conn, $_POST['namaBank']);
    $kantorCabang = mysqli_real_escape_string($conn, $_POST['kantorCabang']);
    
    // Handle bank lainnya
    if ($namaBank === 'Lainnya') {
        $namaBank = mysqli_real_escape_string($conn, $_POST['namaBankLainnya'] ?? '');
    }
    
    $queryUpdate = "UPDATE rekening SET 
                    nomorRekGaji = '$nomorRekGaji',
                    namaBank = '$namaBank',
                    kantorCabang = '$kantorCabang'
                    WHERE idRekening = '$idRekening'";
    
    if (mysqli_query($conn, $queryUpdate)) {
        header("Location: ../list-rekening.php?message=edit");
        exit();
    } else {
        $error = "Gagal mengupdate data: " . mysqli_error($conn);
    }
}

// Cek apakah bank adalah yang ada di list atau lainnya
$bankList = ['Bank Mandiri', 'Bank BRI', 'Bank BNI', 'Bank BTN', 'Bank Syariah Indonesia (BSI)', 
             'Bank Aceh', 'Bank Aceh Syariah', 'Bank CIMB Niaga', 'Bank Danamon', 'Bank Permata', 
             'Bank Mega', 'Bank BCA', 'Bank Panin', 'Bank OCBC NISP'];
$isOtherBank = !in_array($rekening['namaBank'], $bankList);
$selectedBank = $isOtherBank ? 'Lainnya' : $rekening['namaBank'];
$otherBankName = $isOtherBank ? $rekening['namaBank'] : '';

include '../../../includes/sidebar.php';
?>

<link rel="stylesheet" href="../../../assets/css/style-tables.css">
<link rel="stylesheet" href="../../../assets/css/style-form.css">

<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h2><i class="fas fa-university me-2"></i>Edit Data Rekening</h2>
            <p>Formulir Edit Data Rekening Bank - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-university page-header-icon d-none d-md-block"></i>
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
                <div class="info-value"><?php echo htmlspecialchars($rekening['namaDenganGelar']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-id-card"></i> NIP
                </div>
                <div class="info-value"><?php echo htmlspecialchars($rekening['nip']); ?></div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="" id="formRekening">

            <!-- Section: Data Rekening Bank -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-university"></i>
                    <h5>Data Rekening Bank untuk Gaji</h5>
                </div>
                <div class="form-section-body">
                    <div class="alert alert-info-custom mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Pastikan nomor rekening yang Anda masukkan adalah rekening aktif atas nama Anda sendiri untuk keperluan transfer gaji.
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Nama Bank <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-landmark"></i>
                                </span>
                                <select name="namaBank" class="form-control" id="namaBank" required>
                                    <option value="" disabled>Pilih Bank</option>
                                    <option value="Bank Mandiri" <?php echo ($selectedBank == 'Bank Mandiri') ? 'selected' : ''; ?>>Bank Mandiri</option>
                                    <option value="Bank BRI" <?php echo ($selectedBank == 'Bank BRI') ? 'selected' : ''; ?>>Bank BRI</option>
                                    <option value="Bank BNI" <?php echo ($selectedBank == 'Bank BNI') ? 'selected' : ''; ?>>Bank BNI</option>
                                    <option value="Bank BTN" <?php echo ($selectedBank == 'Bank BTN') ? 'selected' : ''; ?>>Bank BTN</option>
                                    <option value="Bank Syariah Indonesia (BSI)" <?php echo ($selectedBank == 'Bank Syariah Indonesia (BSI)') ? 'selected' : ''; ?>>Bank Syariah Indonesia (BSI)</option>
                                    <option value="Bank Aceh" <?php echo ($selectedBank == 'Bank Aceh') ? 'selected' : ''; ?>>Bank Aceh</option>
                                    <option value="Bank Aceh Syariah" <?php echo ($selectedBank == 'Bank Aceh Syariah') ? 'selected' : ''; ?>>Bank Aceh Syariah</option>
                                    <option value="Bank CIMB Niaga" <?php echo ($selectedBank == 'Bank CIMB Niaga') ? 'selected' : ''; ?>>Bank CIMB Niaga</option>
                                    <option value="Bank Danamon" <?php echo ($selectedBank == 'Bank Danamon') ? 'selected' : ''; ?>>Bank Danamon</option>
                                    <option value="Bank Permata" <?php echo ($selectedBank == 'Bank Permata') ? 'selected' : ''; ?>>Bank Permata</option>
                                    <option value="Bank Mega" <?php echo ($selectedBank == 'Bank Mega') ? 'selected' : ''; ?>>Bank Mega</option>
                                    <option value="Bank BCA" <?php echo ($selectedBank == 'Bank BCA') ? 'selected' : ''; ?>>Bank BCA</option>
                                    <option value="Bank Panin" <?php echo ($selectedBank == 'Bank Panin') ? 'selected' : ''; ?>>Bank Panin</option>
                                    <option value="Bank OCBC NISP" <?php echo ($selectedBank == 'Bank OCBC NISP') ? 'selected' : ''; ?>>Bank OCBC NISP</option>
                                    <option value="Lainnya" <?php echo ($selectedBank == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                                </select>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Pilih nama bank tempat rekening gaji Anda
                            </small>
                        </div>

                        <div class="col-md-6 mb-3" id="bankLainnyaContainer" style="display: <?php echo $isOtherBank ? 'block' : 'none'; ?>;">
                            <label class="form-label">
                                Nama Bank Lainnya <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-landmark"></i>
                                </span>
                                <input type="text" 
                                       name="namaBankLainnya" 
                                       id="namaBankLainnya"
                                       class="form-control" 
                                       placeholder="Masukkan nama bank"
                                       value="<?php echo htmlspecialchars($otherBankName); ?>"
                                       <?php echo $isOtherBank ? 'required' : ''; ?>>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Nomor Rekening Gaji <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-credit-card"></i>
                                </span>
                                <input type="text" 
                                       name="nomorRekGaji" 
                                       class="form-control" 
                                       placeholder="Masukkan nomor rekening"
                                       value="<?php echo htmlspecialchars($rekening['nomorRekGaji']); ?>"
                                       required>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Nomor rekening hanya berisi angka tanpa spasi atau tanda baca
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Kantor Cabang <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-building"></i>
                                </span>
                                <input type="text" 
                                       name="kantorCabang" 
                                       class="form-control" 
                                       placeholder="Contoh: KCP Lhokseumawe, Cabang Medan"
                                       value="<?php echo htmlspecialchars($rekening['kantorCabang']); ?>"
                                       required>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Nama kantor cabang bank tempat rekening dibuka
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="../list-rekening.php" class="btn-cancel">
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
// Show/Hide Bank Lainnya field
document.getElementById('namaBank').addEventListener('change', function() {
    const bankLainnyaContainer = document.getElementById('bankLainnyaContainer');
    const namaBankLainnya = document.getElementById('namaBankLainnya');
    
    if (this.value === 'Lainnya') {
        bankLainnyaContainer.style.display = 'block';
        namaBankLainnya.required = true;
    } else {
        bankLainnyaContainer.style.display = 'none';
        namaBankLainnya.required = false;
        namaBankLainnya.value = '';
    }
});

// Format Nomor Rekening - Only allow numbers
document.querySelector('input[name="nomorRekGaji"]').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, ''); // Remove non-digits
});

// Form validation
document.getElementById('formRekening').addEventListener('submit', function(e) {
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
    
    // Validate nomor rekening length (minimal 10 digit, tapi tidak blocking)
    const nomorRek = document.querySelector('input[name="nomorRekGaji"]');
    if (nomorRek.value.length > 0 && nomorRek.value.length < 10) {
        const confirm = window.confirm('Nomor rekening biasanya minimal 10 digit. Apakah Anda yakin ingin melanjutkan?');
        if (!confirm) {
            isValid = false;
            nomorRek.classList.add('is-invalid');
            nomorRek.focus();
        }
    }
    
    // Check if "Lainnya" is selected but not filled
    const namaBank = document.getElementById('namaBank');
    const namaBankLainnya = document.getElementById('namaBankLainnya');
    if (namaBank.value === 'Lainnya' && !namaBankLainnya.value.trim()) {
        isValid = false;
        namaBankLainnya.classList.add('is-invalid');
        alert('Mohon isi nama bank lainnya!');
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

// Prevent non-numeric input for nomor rekening
document.querySelector('input[name="nomorRekGaji"]').addEventListener('keypress', function(e) {
    if (!/[0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab' && e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') {
        e.preventDefault();
    }
});
</script>

<?php include '../../../includes/footer.php'; ?>