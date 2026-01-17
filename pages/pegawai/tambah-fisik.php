<?php
require_once '../../config/database.php';

$page_title = 'Tambah Data Fisik';

$idPegawai = $_GET['idPegawai'] ?? null;
if (!$idPegawai) {
    header("Location: tambah-pegawai.php");
    exit();
}

if (isset($_POST['submit'])) {
    $idPegawai = mysqli_real_escape_string($conn, $_POST['idPegawai'] ?? '');
    $tinggiBadan = mysqli_real_escape_string($conn, $_POST['tinggiBadan'] ?? '');
    $beratBadan = mysqli_real_escape_string($conn, $_POST['beratBadan'] ?? '');
    $jenisRambut = mysqli_real_escape_string($conn, $_POST['jenisRambut'] ?? '');
    $warnaRambut = mysqli_real_escape_string($conn, $_POST['warnaRambut'] ?? '');
    $bentukWajah = mysqli_real_escape_string($conn, $_POST['bentukWajah'] ?? '');
    $warnaKulit = mysqli_real_escape_string($conn, $_POST['warnaKulit'] ?? '');
    $ciriKhusus = mysqli_real_escape_string($conn, $_POST['ciriKhusus'] ?? '');

    if ($idPegawai === '') {
        $error = "ID Pegawai kosong. Pastikan alur tambah pegawai benar.";
    } else {
        $query = "INSERT INTO fisik
            (idPegawai, tinggiBadan, beratBadan, jenisRambut, warnaRambut, bentukWajah, warnaKulit, ciriKhusus)
            VALUES
            ('$idPegawai', '$tinggiBadan', '$beratBadan', '$jenisRambut', '$warnaRambut', '$bentukWajah', '$warnaKulit', '$ciriKhusus')";

        if (mysqli_query($conn, $query)) {
            header("Location: tambah-ukuran-dinas.php?idPegawai=$idPegawai&message=tambah");
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
            <h2><i class="fas fa-user-md me-2"></i>Tambah Data Fisik</h2>
            <p>Formulir Penambahan Data Ciri-Ciri Fisik Pegawai - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-user-md page-header-icon d-none d-md-block"></i>
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
        <div class="step active">
            <div class="step-number">8</div>
            <div class="step-label">Data Fisik</div>
        </div>
        <div class="step-line"></div>
        <div class="step">
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
        <form method="POST" action="" id="formFisik">
            <input type="hidden" name="idPegawai" value="<?php echo htmlspecialchars($idPegawai); ?>">
            
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
                                       required>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Masukkan berat badan dalam satuan kilogram (KG)
                            </small>
                        </div>
                    </div>

                    <!-- BMI Calculator Display (Optional) -->
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
                                    <option value="" disabled selected>Pilih Jenis Rambut</option>
                                    <option value="Lurus">Lurus</option>
                                    <option value="Berombak">Berombak</option>
                                    <option value="Ikal">Ikal</option>
                                    <option value="Keriting">Keriting</option>
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
                                    <option value="" disabled selected>Pilih Warna Rambut</option>
                                    <option value="Hitam">Hitam</option>
                                    <option value="Coklat Tua">Coklat Tua</option>
                                    <option value="Coklat Muda">Coklat Muda</option>
                                    <option value="Pirang">Pirang</option>
                                    <option value="Merah">Merah</option>
                                    <option value="Putih/Uban">Putih/Uban</option>
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
                                    <option value="" disabled selected>Pilih Bentuk Wajah</option>
                                    <option value="Bulat">Bulat</option>
                                    <option value="Oval">Oval</option>
                                    <option value="Persegi">Persegi</option>
                                    <option value="Hati">Hati</option>
                                    <option value="Lonjong">Lonjong</option>
                                    <option value="Diamond">Diamond</option>
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
                                    <option value="" disabled selected>Pilih Warna Kulit</option>
                                    <option value="Putih">Putih</option>
                                    <option value="Kuning Langsat">Kuning Langsat</option>
                                    <option value="Sawo Matang">Sawo Matang</option>
                                    <option value="Coklat">Coklat</option>
                                    <option value="Coklat Gelap">Coklat Gelap</option>
                                    <option value="Hitam">Hitam</option>
                                </select>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Pilih warna kulit yang paling mendekati
                            </small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Ciri Khusus <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-crosshairs"></i>
                                </span>
                                <input type="text" 
                                       name="ciriKhusus" 
                                       class="form-control"
                                       min="30"
                                       max="200"
                                       required>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Masukkan ciri khusus
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

<?php include '../../includes/footer.php'; ?>