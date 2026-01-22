<?php
require_once '../../../config/database.php';
require_once '../../../includes/check_login.php';

$page_title = 'Tambah Data Alamat';

// Get ID Pegawai dari parameter URL atau POST
$idPegawai = isset($_GET['idPegawai']) ? $_GET['idPegawai'] : (isset($_POST['idPegawai']) ? $_POST['idPegawai'] : '');

if (isset($_POST['submit'])) {
    $idPegawai = mysqli_real_escape_string($conn, $_POST['idPegawai'] ?? '');
    $alamatKTP = mysqli_real_escape_string($conn, $_POST['alamatKTP'] ?? '');
    $alamatDomisili = mysqli_real_escape_string($conn, $_POST['alamatDomisili'] ?? '');

    if ($idPegawai === '') {
        $error = "ID Pegawai kosong. Pastikan alur tambah pegawai benar.";
    } else {
        $query = "INSERT INTO alamat
            (idPegawai, alamatKTP, alamatDomisili)
            VALUES
            ('$idPegawai', '$alamatKTP', '$alamatDomisili')";

        if (mysqli_query($conn, $query)) {
            header("Location: ../list-alamat.php");
            exit();
        } else {
            $error = "Gagal menambahkan data: " . mysqli_error($conn);
        }
    }
}

// Get all pegawai data for dropdown
$queryAllPegawai = "SELECT p.idPegawai, p.namaDenganGelar, p.nip 
                    FROM pegawai p
                    LEFT JOIN alamat a ON p.idPegawai = a.idPegawai
                    WHERE a.idPegawai IS NULL
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
            <h2><i class="fas fa-map-marked-alt me-2"></i>Tambah Data Alamat</h2>
            <p>Formulir Penambahan Data Alamat Pegawai - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-map-marked-alt page-header-icon d-none d-md-block"></i>
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
        <form method="POST" action="" id="formAlamat">
            
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

            <!-- Section: Data Alamat -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-map-marked-alt"></i>
                    <h5>Data Alamat Lengkap</h5>
                </div>
                <div class="form-section-body">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <label class="form-label">
                                Alamat Sesuai KTP <span class="text-danger">*</span>
                            </label>
                            <div class="input-group-textarea">
                                <span class="input-icon-textarea">
                                    <i class="fas fa-id-card"></i>
                                </span>
                                <textarea 
                                    name="alamatKTP" 
                                    class="form-control-textarea" 
                                    rows="4"
                                    placeholder="Masukkan alamat lengkap sesuai KTP&#10;Contoh: Jl. Merdeka No. 123, RT 01/RW 02, Kelurahan Kampung Jawa, Kecamatan Banda Sakti, Kota Lhokseumawe, Aceh 24352"
                                    required></textarea>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Tuliskan alamat lengkap sesuai dengan yang tertera di KTP (termasuk RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Provinsi, dan Kode Pos)
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="checkbox-group">
                                <label class="checkbox-option">
                                    <input type="checkbox" id="samadenganKTP" onchange="copyAlamat()">
                                    <span class="checkbox-label">
                                        <i class="fas fa-copy me-2"></i>
                                        Alamat Domisili sama dengan Alamat KTP
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                Alamat Domisili (Tempat Tinggal Saat Ini) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group-textarea">
                                <span class="input-icon-textarea">
                                    <i class="fas fa-home"></i>
                                </span>
                                <textarea 
                                    name="alamatDomisili" 
                                    id="alamatDomisili"
                                    class="form-control-textarea" 
                                    rows="4"
                                    placeholder="Masukkan alamat domisili saat ini&#10;Contoh: Jl. Medan-Banda Aceh No. 45, RT 03/RW 04, Desa Muara Dua, Kecamatan Lhokseumawe Utara, Kota Lhokseumawe, Aceh 24356"
                                    required></textarea>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Alamat tempat tinggal saat ini (jika berbeda dengan alamat KTP)
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

// Copy alamat KTP ke alamat Domisili
function copyAlamat() {
    const checkbox = document.getElementById('samadenganKTP');
    const alamatKTP = document.querySelector('textarea[name="alamatKTP"]');
    const alamatDomisili = document.getElementById('alamatDomisili');
    
    if (checkbox.checked) {
        alamatDomisili.value = alamatKTP.value;
        alamatDomisili.readOnly = true;
        alamatDomisili.style.backgroundColor = '#f3f4f6';
    } else {
        alamatDomisili.value = '';
        alamatDomisili.readOnly = false;
        alamatDomisili.style.backgroundColor = 'white';
    }
}

// Update alamat domisili saat alamat KTP berubah (jika checkbox dicentang)
document.querySelector('textarea[name="alamatKTP"]').addEventListener('input', function() {
    const checkbox = document.getElementById('samadenganKTP');
    if (checkbox.checked) {
        document.getElementById('alamatDomisili').value = this.value;
    }
});

// Form validation
document.getElementById('formAlamat').addEventListener('submit', function(e) {
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
document.querySelectorAll('.form-control-textarea').forEach(input => {
    input.addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });
});

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