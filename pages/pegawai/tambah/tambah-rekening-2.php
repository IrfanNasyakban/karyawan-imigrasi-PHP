<?php
require_once '../../../config/database.php';

$page_title = 'Tambah Data Rekening';

// Get ID Pegawai dari parameter URL atau POST
$idPegawai = isset($_GET['idPegawai']) ? $_GET['idPegawai'] : (isset($_POST['idPegawai']) ? $_POST['idPegawai'] : '');

if (isset($_POST['submit'])) {
    $idPegawai = mysqli_real_escape_string($conn, $_POST['idPegawai'] ?? '');
    $nomorRekGaji = mysqli_real_escape_string($conn, $_POST['nomorRekGaji'] ?? '');
    $namaBank = mysqli_real_escape_string($conn, $_POST['namaBank'] ?? '');
    $kantorCabang = mysqli_real_escape_string($conn, $_POST['kantorCabang'] ?? '');
    
    // Handle bank lainnya
    if ($namaBank === 'Lainnya') {
        $namaBank = mysqli_real_escape_string($conn, $_POST['namaBankLainnya'] ?? '');
    }

    if ($idPegawai === '') {
        $error = "ID Pegawai kosong. Pastikan alur tambah pegawai benar.";
    } else {
        $query = "INSERT INTO rekening
            (idPegawai, nomorRekGaji, namaBank, kantorCabang)
            VALUES
            ('$idPegawai', '$nomorRekGaji', '$namaBank', '$kantorCabang')";

        if (mysqli_query($conn, $query)) {
            header("Location: ../list-rekening.php");
            exit();
        } else {
            $error = "Gagal menambahkan data: " . mysqli_error($conn);
        }
    }
}

// Get all pegawai data for dropdown
$queryAllPegawai = "SELECT p.idPegawai, p.namaDenganGelar, p.nip 
                    FROM pegawai p
                    LEFT JOIN rekening rk ON p.idPegawai = rk.idPegawai
                    WHERE rk.idPegawai IS NULL
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
            <h2><i class="fas fa-university me-2"></i>Tambah Data Rekening</h2>
            <p>Formulir Penambahan Data Rekening Bank - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
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
        <form method="POST" action="" id="formRekening">
            
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
                            <small class="form-text">Pilih pegawai untuk menambahkan data rekening</small>
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
    
    input.addEventListener('change', function() {
        this.classList.remove('is-invalid');
    });
});
</script>

<?php include '../../../includes/footer.php'; ?>