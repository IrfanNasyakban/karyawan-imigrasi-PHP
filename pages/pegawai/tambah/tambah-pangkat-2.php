<?php
require_once '../../../config/database.php';

$page_title = 'Tambah Data Pangkat';

// Get ID Pegawai dari parameter URL atau POST
$idPegawai = isset($_GET['idPegawai']) ? $_GET['idPegawai'] : (isset($_POST['idPegawai']) ? $_POST['idPegawai'] : '');

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
            (idPegawai, pangkat, golonganRuang, tmtPangkat, tanggalSKPangkat, nomorSKPangkat, SKPangkatDari, uraianSKPangkat)
            VALUES
            ('$idPegawai', '$pangkat', '$golonganRuang', '$tmtPangkat', '$tanggalSKPangkat', '$nomorSKPangkat', '$SKPangkatDari', '$uraianSKPangkat')";

        if (mysqli_query($conn, $query)) {
            header("Location: ../list-pangkat.php");
            exit();
        } else {
            $error = "Gagal menambahkan data: " . mysqli_error($conn);
        }
    }
}

// Get all pegawai data for dropdown
$queryAllPegawai = "SELECT p.idPegawai, p.namaDenganGelar, p.nip 
                    FROM pegawai p
                    LEFT JOIN pangkat pk ON p.idPegawai = pk.idPegawai
                    WHERE pk.idPegawai IS NULL
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
            <h2><i class="fas fa-id-badge me-2"></i>Tambah Data Pangkat</h2>
            <p>Formulir Penambahan Data Pangkat Pegawai - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
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
        <form method="POST" action="" id="formPangkat">
            <input type="hidden" name="idPegawai" value="<?php echo htmlspecialchars($idPegawai); ?>">

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
                    <i class="fas fa-arrow-right me-2"></i>Simpan
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

<?php include '../../../includes/footer.php'; ?>