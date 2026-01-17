<?php
require_once '../../../config/database.php';

$page_title = 'Edit Data Pangkat';

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../list-pangkat.php?error=invalid_request");
    exit();
}

$idPangkat = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data Pangkat dan pegawai berdasarkan ID
$query = "SELECT pk.*, p.nip, p.namaDenganGelar, p.statusPegawai 
          FROM pangkat pk
          LEFT JOIN pegawai p ON pk.idPegawai = p.idPegawai
          WHERE pk.idPangkat = '$idPangkat'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: ../list-pangkat.php?error=tidak_ditemukan");
    exit();
}

$pangkat = mysqli_fetch_assoc($result);

// Proses update data
if (isset($_POST['submit'])) {
    $pangkatValue = mysqli_real_escape_string($conn, $_POST['pangkat']);
    $golonganRuang = mysqli_real_escape_string($conn, $_POST['golonganRuang']);
    $tmtPangkat = mysqli_real_escape_string($conn, $_POST['tmtPangkat']);
    $tanggalSKPangkat = mysqli_real_escape_string($conn, $_POST['tanggalSKPangkat']);
    $nomorSKPangkat = mysqli_real_escape_string($conn, $_POST['nomorSKPangkat']);
    $SKPangkatDari = mysqli_real_escape_string($conn, $_POST['SKPangkatDari']);
    $uraianSKPangkat = mysqli_real_escape_string($conn, $_POST['uraianSKPangkat']);
    
    $queryUpdate = "UPDATE pangkat SET 
                    pangkat = '$pangkatValue',
                    golonganRuang = '$golonganRuang',
                    tmtPangkat = '$tmtPangkat',
                    tanggalSKPangkat = '$tanggalSKPangkat',
                    nomorSKPangkat = '$nomorSKPangkat',
                    SKPangkatDari = '$SKPangkatDari',
                    uraianSKPangkat = '$uraianSKPangkat'
                    WHERE idPangkat = '$idPangkat'";
    
    if (mysqli_query($conn, $queryUpdate)) {
        header("Location: ../list-pangkat.php?message=edit");
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
            <h2><i class="fas fa-id-badge me-2"></i>Edit Data Pangkat</h2>
            <p>Formulir Edit Data Pangkat Pegawai - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-id-badge page-header-icon d-none d-md-block"></i>
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
                <div class="info-value"><?php echo htmlspecialchars($pangkat['namaDenganGelar']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-id-card"></i> NIP
                </div>
                <div class="info-value"><?php echo htmlspecialchars($pangkat['nip']); ?></div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="" id="formPangkat">
            
            <!-- Section: Data Pangkat -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-id-badge"></i>
                    <h5>Data Pangkat & Golongan</h5>
                </div>
                <div class="form-section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Pangkat <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-award"></i>
                                </span>
                                <input type="text" 
                                       name="pangkat" 
                                       class="form-control" 
                                       placeholder="Contoh: Penata Muda"
                                       value="<?php echo htmlspecialchars($pangkat['pangkat']); ?>"
                                       required>
                            </div>
                            <small class="form-text">Masukkan nama pangkat pegawai</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Golongan Ruang <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-layer-group"></i>
                                </span>
                                <input type="text" 
                                       name="golonganRuang" 
                                       class="form-control" 
                                       placeholder="Contoh: III/a"
                                       value="<?php echo htmlspecialchars($pangkat['golonganRuang']); ?>"
                                       required>
                            </div>
                            <small class="form-text">Format: Golongan/Ruang (Contoh: III/a)</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Terhitung Mulai Tanggal (TMT) Pangkat <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" 
                                       name="tmtPangkat" 
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($pangkat['tmtPangkat']); ?>"
                                       required>
                            </div>
                            <small class="form-text">Tanggal mulai berlakunya pangkat</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Surat Keputusan Pangkat -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-file-contract"></i>
                    <h5>Surat Keputusan Pangkat</h5>
                </div>
                <div class="form-section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Tanggal SK Pangkat <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </span>
                                <input type="date" 
                                       name="tanggalSKPangkat" 
                                       class="form-control"
                                       value="<?php echo htmlspecialchars($pangkat['tanggalSKPangkat']); ?>"
                                       required>
                            </div>
                            <small class="form-text">Tanggal diterbitkannya SK Pangkat</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Nomor SK Pangkat <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input type="text" 
                                       name="nomorSKPangkat" 
                                       class="form-control" 
                                       placeholder="Contoh: 001/SK/2024"
                                       value="<?php echo htmlspecialchars($pangkat['nomorSKPangkat']); ?>"
                                       required>
                            </div>
                            <small class="form-text">Nomor surat keputusan pangkat</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                SK Pangkat Dari <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-building"></i>
                                </span>
                                <input type="text" 
                                       name="SKPangkatDari" 
                                       class="form-control" 
                                       placeholder="Contoh: Kepala Kanwil Kemenkumham Aceh"
                                       value="<?php echo htmlspecialchars($pangkat['SKPangkatDari']); ?>"
                                       required>
                            </div>
                            <small class="form-text">Instansi/Pejabat yang menerbitkan SK</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Uraian SK Pangkat <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-align-left"></i>
                                </span>
                                <textarea name="uraianSKPangkat" 
                                          class="form-control" 
                                          rows="3"
                                          placeholder="Contoh: Kenaikan Pangkat Periode Oktober 2024"
                                          required><?php echo htmlspecialchars($pangkat['uraianSKPangkat']); ?></textarea>
                            </div>
                            <small class="form-text">Deskripsi atau keterangan SK Pangkat</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="../list-pangkat.php" class="btn-cancel">
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
document.getElementById('formPangkat').addEventListener('submit', function(e) {
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
document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });
});

// Validate Tanggal SK Pangkat should be before or equal to TMT Pangkat
document.querySelector('input[name="tmtPangkat"]').addEventListener('change', function() {
    const tanggalSK = document.querySelector('input[name="tanggalSKPangkat"]').value;
    const tmtPangkat = this.value;
    
    if (tanggalSK && tmtPangkat && tanggalSK > tmtPangkat) {
        alert('Tanggal SK Pangkat tidak boleh lebih besar dari TMT Pangkat!');
        this.value = '';
        this.classList.add('is-invalid');
    }
});

document.querySelector('input[name="tanggalSKPangkat"]').addEventListener('change', function() {
    const tanggalSK = this.value;
    const tmtPangkat = document.querySelector('input[name="tmtPangkat"]').value;
    
    if (tanggalSK && tmtPangkat && tanggalSK > tmtPangkat) {
        alert('Tanggal SK Pangkat tidak boleh lebih besar dari TMT Pangkat!');
        this.value = '';
        this.classList.add('is-invalid');
    }
});
</script>

<?php include '../../../includes/footer.php'; ?>