<?php
require_once '../../config/database.php';

$page_title = 'Tambah Data Rekening';

$idPegawai = $_GET['idPegawai'] ?? null;
if (!$idPegawai) {
    header("Location: tambah-pegawai.php");
    exit();
}

if (isset($_POST['submit'])) {
    $idPegawai = mysqli_real_escape_string($conn, $_POST['idPegawai'] ?? '');
    $nomorRekGaji = mysqli_real_escape_string($conn, $_POST['nomorRekGaji'] ?? '');
    $namaBank = mysqli_real_escape_string($conn, $_POST['namaBank'] ?? '');
    $kantorCabang = mysqli_real_escape_string($conn, $_POST['kantorCabang'] ?? '');

    if ($idPegawai === '') {
        $error = "ID Pegawai kosong. Pastikan alur tambah pegawai benar.";
    } else {
        $query = "INSERT INTO rekening
            (idPegawai, nomorRekGaji, namaBank, kantorCabang)
            VALUES
            ('$idPegawai', '$nomorRekGaji', '$namaBank', '$kantorCabang')";

        if (mysqli_query($conn, $query)) {
            header("Location: tambah-pendidikan.php?idPegawai=$idPegawai&message=tambah");
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
            <h2><i class="fas fa-university me-2"></i>Tambah Data Rekening</h2>
            <p>Formulir Penambahan Data Rekening Bank - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-university page-header-icon d-none d-md-block"></i>
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
        <div class="step active">
            <div class="step-number">6</div>
            <div class="step-label">Data Rekening</div>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-number">7</div>
            <div class="step-label">Data Pendidikan</div>
        </div>
        <div class="step-line"></div>
        <div class="step">
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
        <form method="POST" action="" id="formRekening">
            <input type="hidden" name="idPegawai" value="<?php echo htmlspecialchars($idPegawai); ?>">
            
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
                                    <option value="" disabled selected>Pilih Bank</option>
                                    <option value="Bank Mandiri">Bank Mandiri</option>
                                    <option value="Bank BRI">Bank BRI</option>
                                    <option value="Bank BNI">Bank BNI</option>
                                    <option value="Bank BTN">Bank BTN</option>
                                    <option value="Bank Syariah Indonesia (BSI)">Bank Syariah Indonesia (BSI)</option>
                                    <option value="Bank Aceh">Bank Aceh</option>
                                    <option value="Bank Aceh Syariah">Bank Aceh Syariah</option>
                                    <option value="Bank CIMB Niaga">Bank CIMB Niaga</option>
                                    <option value="Bank Danamon">Bank Danamon</option>
                                    <option value="Bank Permata">Bank Permata</option>
                                    <option value="Bank Mega">Bank Mega</option>
                                    <option value="Bank BCA">Bank BCA</option>
                                    <option value="Bank Panin">Bank Panin</option>
                                    <option value="Bank OCBC NISP">Bank OCBC NISP</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Pilih nama bank tempat rekening gaji Anda
                            </small>
                        </div>

                        <div class="col-md-6 mb-3" id="bankLainnyaContainer" style="display: none;">
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
                                       placeholder="Masukkan nama bank">
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
                                       pattern="[0-9]+"
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
                <button type="submit" name="submit" class="btn-submit">
                    <i class="fas fa-arrow-right me-2"></i>Selanjutnya
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
    
    // Validate nomor rekening length
    const nomorRek = document.querySelector('input[name="nomorRekGaji"]');
    if (nomorRek.value.length < 10) {
        isValid = false;
        nomorRek.classList.add('is-invalid');
        alert('Nomor rekening minimal 10 digit!');
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
});
</script>

<?php include '../../includes/footer.php'; ?>