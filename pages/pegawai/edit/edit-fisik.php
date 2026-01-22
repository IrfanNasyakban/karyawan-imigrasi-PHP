<?php
require_once '../../../config/database.php';
require_once '../../../includes/check_login.php';

$page_title = 'Edit Data Fisik';

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../list-fisik.php?error=invalid_request");
    exit();
}

$idFisik = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data Fisik dan pegawai berdasarkan ID
$query = "SELECT f.*, p.nip, p.namaDenganGelar, p.statusPegawai 
          FROM fisik f
          LEFT JOIN pegawai p ON f.idPegawai = p.idPegawai
          WHERE f.idFisik = '$idFisik'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: ../list-fisik.php?error=tidak_ditemukan");
    exit();
}

$fisik = mysqli_fetch_assoc($result);

// Proses update data
if (isset($_POST['submit'])) {
    $tinggiBadan = mysqli_real_escape_string($conn, $_POST['tinggiBadan']);
    $beratBadan = mysqli_real_escape_string($conn, $_POST['beratBadan']);
    $jenisRambut = mysqli_real_escape_string($conn, $_POST['jenisRambut']);
    $warnaRambut = mysqli_real_escape_string($conn, $_POST['warnaRambut']);
    $bentukWajah = mysqli_real_escape_string($conn, $_POST['bentukWajah']);
    $warnaKulit = mysqli_real_escape_string($conn, $_POST['warnaKulit']);
    $ciriKhusus = mysqli_real_escape_string($conn, $_POST['ciriKhusus']);
    
    $queryUpdate = "UPDATE fisik SET 
                    tinggiBadan = '$tinggiBadan',
                    beratBadan = '$beratBadan',
                    jenisRambut = '$jenisRambut',
                    warnaRambut = '$warnaRambut',
                    bentukWajah = '$bentukWajah',
                    warnaKulit = '$warnaKulit',
                    ciriKhusus = '$ciriKhusus'
                    WHERE idFisik = '$idFisik'";
    
    if (mysqli_query($conn, $queryUpdate)) {
        header("Location: ../list-fisik.php?message=edit");
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
            <h2><i class="fas fa-user-md me-2"></i>Edit Data Fisik</h2>
            <p>Formulir Edit Data Ciri-Ciri Fisik Pegawai - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-user-md page-header-icon d-none d-md-block"></i>
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
                <div class="info-value"><?php echo htmlspecialchars($fisik['namaDenganGelar']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-id-card"></i> NIP
                </div>
                <div class="info-value"><?php echo htmlspecialchars($fisik['nip']); ?></div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="" id="formFisik">

            <!-- Section 1: Ukuran Tubuh -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-weight"></i>
                    <h5>Ukuran Tubuh</h5>
                </div>
                <div class="form-section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Tinggi Badan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-ruler-vertical"></i>
                                </span>
                                <input type="number" 
                                       name="tinggiBadan" 
                                       class="form-control" 
                                       placeholder="Contoh: 170"
                                       min="100"
                                       max="250"
                                       value="<?php echo htmlspecialchars($fisik['tinggiBadan']); ?>"
                                       required>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Masukkan tinggi badan dalam satuan centimeter (CM)
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Berat Badan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-weight"></i>
                                </span>
                                <input type="number" 
                                       name="beratBadan" 
                                       class="form-control" 
                                       placeholder="Contoh: 65"
                                       min="30"
                                       max="200"
                                       value="<?php echo htmlspecialchars($fisik['beratBadan']); ?>"
                                       required>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Masukkan berat badan dalam satuan kilogram (KG)
                            </small>
                        </div>
                    </div>

                    <!-- BMI Calculator Display -->
                    <div class="bmi-display" id="bmiDisplay" style="display: none;">
                        <div class="alert alert-info-custom">
                            <i class="fas fa-calculator me-2"></i>
                            <div>
                                <strong>BMI (Body Mass Index):</strong> <span id="bmiValue">-</span>
                                <br>
                                <small>Status: <span id="bmiStatus">-</span></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Ciri-Ciri Rambut -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-head-side"></i>
                    <h5>Ciri-Ciri Rambut</h5>
                </div>
                <div class="form-section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Jenis Rambut <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-wave-square"></i>
                                </span>
                                <select name="jenisRambut" class="form-control" required>
                                    <option value="" disabled>Pilih Jenis Rambut</option>
                                    <option value="Lurus" <?php echo ($fisik['jenisRambut'] == 'Lurus') ? 'selected' : ''; ?>>Lurus</option>
                                    <option value="Berombak" <?php echo ($fisik['jenisRambut'] == 'Berombak') ? 'selected' : ''; ?>>Berombak</option>
                                    <option value="Ikal" <?php echo ($fisik['jenisRambut'] == 'Ikal') ? 'selected' : ''; ?>>Ikal</option>
                                    <option value="Keriting" <?php echo ($fisik['jenisRambut'] == 'Keriting') ? 'selected' : ''; ?>>Keriting</option>
                                </select>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Pilih jenis rambut sesuai kondisi saat ini
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Warna Rambut <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-palette"></i>
                                </span>
                                <select name="warnaRambut" class="form-control" required>
                                    <option value="" disabled>Pilih Warna Rambut</option>
                                    <option value="Hitam" <?php echo ($fisik['warnaRambut'] == 'Hitam') ? 'selected' : ''; ?>>Hitam</option>
                                    <option value="Coklat Tua" <?php echo ($fisik['warnaRambut'] == 'Coklat Tua') ? 'selected' : ''; ?>>Coklat Tua</option>
                                    <option value="Coklat Muda" <?php echo ($fisik['warnaRambut'] == 'Coklat Muda') ? 'selected' : ''; ?>>Coklat Muda</option>
                                    <option value="Pirang" <?php echo ($fisik['warnaRambut'] == 'Pirang') ? 'selected' : ''; ?>>Pirang</option>
                                    <option value="Merah" <?php echo ($fisik['warnaRambut'] == 'Merah') ? 'selected' : ''; ?>>Merah</option>
                                    <option value="Putih/Uban" <?php echo ($fisik['warnaRambut'] == 'Putih/Uban') ? 'selected' : ''; ?>>Putih/Uban</option>
                                </select>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Pilih warna rambut alami (bukan hasil pewarnaan)
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Ciri-Ciri Wajah & Kulit -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-user-circle"></i>
                    <h5>Ciri-Ciri Wajah & Kulit</h5>
                </div>
                <div class="form-section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Bentuk Wajah <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-smile"></i>
                                </span>
                                <select name="bentukWajah" class="form-control" required>
                                    <option value="" disabled>Pilih Bentuk Wajah</option>
                                    <option value="Bulat" <?php echo ($fisik['bentukWajah'] == 'Bulat') ? 'selected' : ''; ?>>Bulat</option>
                                    <option value="Oval" <?php echo ($fisik['bentukWajah'] == 'Oval') ? 'selected' : ''; ?>>Oval</option>
                                    <option value="Persegi" <?php echo ($fisik['bentukWajah'] == 'Persegi') ? 'selected' : ''; ?>>Persegi</option>
                                    <option value="Hati" <?php echo ($fisik['bentukWajah'] == 'Hati') ? 'selected' : ''; ?>>Hati</option>
                                    <option value="Lonjong" <?php echo ($fisik['bentukWajah'] == 'Lonjong') ? 'selected' : ''; ?>>Lonjong</option>
                                    <option value="Diamond" <?php echo ($fisik['bentukWajah'] == 'Diamond') ? 'selected' : ''; ?>>Diamond</option>
                                </select>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Pilih bentuk wajah yang paling sesuai
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Warna Kulit <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-hand-paper"></i>
                                </span>
                                <select name="warnaKulit" class="form-control" required>
                                    <option value="" disabled>Pilih Warna Kulit</option>
                                    <option value="Putih" <?php echo ($fisik['warnaKulit'] == 'Putih') ? 'selected' : ''; ?>>Putih</option>
                                    <option value="Kuning Langsat" <?php echo ($fisik['warnaKulit'] == 'Kuning Langsat') ? 'selected' : ''; ?>>Kuning Langsat</option>
                                    <option value="Sawo Matang" <?php echo ($fisik['warnaKulit'] == 'Sawo Matang') ? 'selected' : ''; ?>>Sawo Matang</option>
                                    <option value="Coklat" <?php echo ($fisik['warnaKulit'] == 'Coklat') ? 'selected' : ''; ?>>Coklat</option>
                                    <option value="Coklat Gelap" <?php echo ($fisik['warnaKulit'] == 'Coklat Gelap') ? 'selected' : ''; ?>>Coklat Gelap</option>
                                    <option value="Hitam" <?php echo ($fisik['warnaKulit'] == 'Hitam') ? 'selected' : ''; ?>>Hitam</option>
                                </select>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Pilih warna kulit yang paling mendekati
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Ciri Khusus <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-crosshairs"></i>
                                </span>
                                <textarea name="ciriKhusus" 
                                          class="form-control" 
                                          rows="3"
                                          placeholder="Contoh: Tahi lalat di pipi kiri, bekas luka di lengan kanan, dll."
                                          required><?php echo htmlspecialchars($fisik['ciriKhusus']); ?></textarea>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Masukkan ciri khusus yang mudah dikenali (tahi lalat, bekas luka, tato, dll.)
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="../list-fisik.php" class="btn-cancel">
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
// BMI Calculator
function calculateBMI() {
    const tinggi = parseFloat(document.querySelector('input[name="tinggiBadan"]').value);
    const berat = parseFloat(document.querySelector('input[name="beratBadan"]').value);
    
    if (tinggi > 0 && berat > 0) {
        const tinggiMeter = tinggi / 100;
        const bmi = (berat / (tinggiMeter * tinggiMeter)).toFixed(2);
        
        let status = '';
        let statusClass = '';
        
        if (bmi < 18.5) {
            status = 'Kurus';
            statusClass = 'text-warning';
        } else if (bmi >= 18.5 && bmi < 25) {
            status = 'Normal';
            statusClass = 'text-success';
        } else if (bmi >= 25 && bmi < 30) {
            status = 'Gemuk';
            statusClass = 'text-warning';
        } else {
            status = 'Obesitas';
            statusClass = 'text-danger';
        }
        
        document.getElementById('bmiValue').textContent = bmi;
        document.getElementById('bmiStatus').textContent = status;
        document.getElementById('bmiStatus').className = statusClass + ' fw-bold';
        document.getElementById('bmiDisplay').style.display = 'block';
    }
}

// Add event listeners for BMI calculation
document.querySelector('input[name="tinggiBadan"]').addEventListener('input', calculateBMI);
document.querySelector('input[name="beratBadan"]').addEventListener('input', calculateBMI);

// Calculate BMI on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateBMI();
});

// Form validation
document.getElementById('formFisik').addEventListener('submit', function(e) {
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
    
    // Validate tinggi badan range
    const tinggi = parseInt(document.querySelector('input[name="tinggiBadan"]').value);
    if (tinggi < 100 || tinggi > 250) {
        isValid = false;
        alert('Tinggi badan harus antara 100-250 cm!');
    }
    
    // Validate berat badan range
    const berat = parseInt(document.querySelector('input[name="beratBadan"]').value);
    if (berat < 30 || berat > 200) {
        isValid = false;
        alert('Berat badan harus antara 30-200 kg!');
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