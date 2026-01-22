<?php
require_once '../../../config/database.php';
require_once '../../../includes/check_login.php';

$page_title = 'Edit Data Alamat';

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../list-alamat.php?error=invalid_request");
    exit();
}

$idAlamat = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data Alamat dan pegawai berdasarkan ID
$query = "SELECT a.*, p.nip, p.namaDenganGelar, p.statusPegawai 
          FROM alamat a
          LEFT JOIN pegawai p ON a.idPegawai = p.idPegawai
          WHERE a.idAlamat = '$idAlamat'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: ../list-alamat.php?error=tidak_ditemukan");
    exit();
}

$alamat = mysqli_fetch_assoc($result);

// Proses update data
if (isset($_POST['submit'])) {
    $alamatKTP = mysqli_real_escape_string($conn, $_POST['alamatKTP']);
    $alamatDomisili = mysqli_real_escape_string($conn, $_POST['alamatDomisili']);
    
    $queryUpdate = "UPDATE alamat SET 
                    alamatKTP = '$alamatKTP',
                    alamatDomisili = '$alamatDomisili'
                    WHERE idAlamat = '$idAlamat'";
    
    if (mysqli_query($conn, $queryUpdate)) {
        header("Location: ../list-alamat.php?message=edit");
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
            <h2><i class="fas fa-map-marked-alt me-2"></i>Edit Data Alamat</h2>
            <p>Formulir Edit Data Alamat Pegawai - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
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
                <div class="info-value"><?php echo htmlspecialchars($alamat['namaDenganGelar']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-id-card"></i> NIP
                </div>
                <div class="info-value"><?php echo htmlspecialchars($alamat['nip']); ?></div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="" id="formAlamat">

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
                                    required><?php echo htmlspecialchars($alamat['alamatKTP']); ?></textarea>
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
                                    required><?php echo htmlspecialchars($alamat['alamatDomisili']); ?></textarea>
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
                <a href="../list-alamat.php" class="btn-cancel">
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

// Auto-check checkbox if both addresses are the same on load
window.addEventListener('DOMContentLoaded', function() {
    const alamatKTP = document.querySelector('textarea[name="alamatKTP"]').value;
    const alamatDomisili = document.getElementById('alamatDomisili').value;
    
    if (alamatKTP && alamatDomisili && alamatKTP === alamatDomisili) {
        const checkbox = document.getElementById('samadenganKTP');
        checkbox.checked = true;
        document.getElementById('alamatDomisili').readOnly = true;
        document.getElementById('alamatDomisili').style.backgroundColor = '#f3f4f6';
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

document.getElementById('alamatDomisili').addEventListener('blur', function() {
    if (!this.readOnly && this.value.trim().length > 0 && this.value.trim().length < 20) {
        alert('Alamat Domisili harus minimal 20 karakter untuk alamat lengkap');
        this.classList.add('is-invalid');
    }
});
</script>

<?php include '../../../includes/footer.php'; ?>