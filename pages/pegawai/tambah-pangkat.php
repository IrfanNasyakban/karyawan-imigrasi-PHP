<?php
require_once '../../config/database.php';

$page_title = 'Tambah Data Pangkat';

$idPegawai = $_GET['idPegawai'] ?? null;
if (!$idPegawai) {
    header("Location: tambah-pegawai.php");
    exit();
}

if (isset($_POST['submit'])) {
    $idPegawai = mysqli_real_escape_string($conn, $_POST['idPegawai'] ?? '');
    $pangkat = mysqli_real_escape_string($conn, $_POST['pangkat'] ?? '');
    $golonganRuang = mysqli_real_escape_string($conn, $_POST['golonganRuang'] ?? '');
    $tmtPangkat = mysqli_real_escape_string($conn, $_POST['tmtPangkat'] ?? '');
    $tanggalSKPangkat = mysqli_real_escape_string($conn, $_POST['tanggalSKPangkat'] ?? '');
    $nomorSKPangkat = mysqli_real_escape_string($conn, $_POST['nomorSKPangkat'] ?? '');
    $SKPangkatDari = mysqli_real_escape_string($conn, $_POST['SKPangkatDari'] ?? '');
    $uraianSKPangkat = mysqli_real_escape_string($conn, $_POST['uraianSKPangkat'] ?? '');

    if ($idPegawai === '') {
        $error = "ID Pegawai kosong. Pastikan alur tambah pegawai benar.";
    } else {
        $query = "INSERT INTO pangkat 
            (idPegawai, pangkat, golonganRuang, tmtPangkat)
            VALUES
            ('$idPegawai', '$pangkat', '$golonganRuang', '$tmtPangkat')";

        if (mysqli_query($conn, $query)) {
            header("Location: tambah-alamat.php?idPegawai=$idPegawai&message=tambah");
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
            <h2><i class="fas fa-id-badge me-2"></i>Tambah Data Pangkat</h2>
            <p>Formulir Penambahan Data Pangkat Pegawai - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-id-badge page-header-icon d-none d-md-block"></i>
    </div>

    <!-- Progress Steps -->
    <div class="progress-steps mb-4">
        <div class="step completed">
            <div class="step-number">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Data Pegawai</div>
        </div>
        
        <div class="step-line"></div>
        <div class="step completed">
            <div class="step-number">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Data Kepegawaian</div>
        </div>

        <div class="step-line completed"></div>
        <div class="step active">
            <div class="step-number">3</div>
            <div class="step-label">Data Pangkat</div>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-number">4</div>
            <div class="step-label">Data Alamat</div>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-number">5</div>
            <div class="step-label">Data Identitas</div>
        </div>
        <div class="step-line"></div>
        <div class="step">
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
        <form method="POST" action="" id="formPangkat">
            <input type="hidden" name="idPegawai" value="<?php echo htmlspecialchars($idPegawai); ?>">
            
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
                                          required></textarea>
                            </div>
                            <small class="form-text">Deskripsi atau keterangan SK Pangkat</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="tambah-pegawai.php" class="btn-cancel">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" name="submit" class="btn-submit">
                    <i class="fas fa-arrow-right me-2"></i>Selanjutnya
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

// Update pegawai info preview
function updatePegawaiInfo(select) {
    const selectedOption = select.options[select.selectedIndex];
    const nama = selectedOption.getAttribute('data-nama');
    const nip = selectedOption.getAttribute('data-nip');
    
    if (nama && nip) {
        document.getElementById('previewNama').textContent = nama;
        document.getElementById('previewNip').textContent = nip;
        document.getElementById('pegawaiInfoPreview').style.display = 'block';
    } else {
        document.getElementById('pegawaiInfoPreview').style.display = 'none';
    }
}
</script>

<?php include '../../includes/footer.php'; ?>