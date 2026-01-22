<?php
require_once '../../../config/database.php';
require_once '../../../includes/check_login.php';

$page_title = 'Tambah Data Kepegawaian';

// Get ID Pegawai dari parameter URL atau POST
$idPegawai = isset($_GET['idPegawai']) ? $_GET['idPegawai'] : (isset($_POST['idPegawai']) ? $_POST['idPegawai'] : '');

if (isset($_POST['submit'])) {
    $idPegawai = mysqli_real_escape_string($conn, $_POST['idPegawai'] ?? '');
    $statusKepegawaian = mysqli_real_escape_string($conn, $_POST['statusKepegawaian'] ?? '');
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan'] ?? '');
    $tmtJabatan = mysqli_real_escape_string($conn, $_POST['tmtJabatan'] ?? '');
    $bagianKerja = mysqli_real_escape_string($conn, $_POST['bagianKerja'] ?? '');
    $eselon = mysqli_real_escape_string($conn, $_POST['eselon'] ?? '');
    $angkatanPejim = mysqli_real_escape_string($conn, $_POST['angkatanPejim'] ?? '');
    $ppns = mysqli_real_escape_string($conn, $_POST['ppns'] ?? '');
    $tmtPensiun = mysqli_real_escape_string($conn, $_POST['tmtPensiun'] ?? '');

    if ($idPegawai === '') {
        $error = "ID Pegawai kosong. Pastikan alur tambah pegawai benar.";
    } else {
        $query = "INSERT INTO kepegawaian 
            (idPegawai, statusKepegawaian, jabatan, tmtJabatan, bagianKerja, eselon, angkatanPejim, ppns, tmtPensiun)
            VALUES
            ('$idPegawai', '$statusKepegawaian', '$jabatan', '$tmtJabatan', '$bagianKerja', '$eselon', '$angkatanPejim', '$ppns', '$tmtPensiun')";

        if (mysqli_query($conn, $query)) {
            header("Location: ../list-kepegawaian.php");
            exit();
        } else {
            $error = "Gagal menambahkan data: " . mysqli_error($conn);
        }
    }
}

// Get all pegawai data for dropdown
$queryAllPegawai = "SELECT p.idPegawai, p.namaDenganGelar, p.nip 
                    FROM pegawai p
                    LEFT JOIN kepegawaian k ON p.idPegawai = k.idPegawai
                    WHERE k.idPegawai IS NULL
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
            <h2><i class="fas fa-user-plus me-2"></i>Tambah Data Kepegawaian</h2>
            <p>Formulir Penambahan Data Kepegawaian Baru - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-user-plus page-header-icon d-none d-md-block"></i>
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
        <form method="POST" action="" id="formPegawai">

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
            
            <!-- Section 1: Status Kepegawaian -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-user-check"></i>
                    <h5>Status Kepegawaian</h5>
                </div>
                <div class="form-section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Status Kepegawaian <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-user-tag"></i>
                                </span>
                                <select name="statusKepegawaian" class="form-control" required>
                                    <option value="" selected disabled>Pilih Status Kepegawaian</option>
                                    <option value="PNS">PNS</option>
                                    <option value="CPNS">CPNS</option>
                                    <option value="PPPK">PPPK</option>
                                    <option value="OUTSOURCING">OUTSOURCING</option>
                                </select>
                            </div>
                            <small class="form-text">Pilih status kepegawaian pegawai</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                PPNS <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-certificate"></i>
                                </span>
                                <select name="ppns" class="form-control" required>
                                    <option value="" selected disabled>Pilih Status PPNS</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                            <small class="form-text">PPNS: Penyidik Pegawai Negeri Sipil</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Jabatan -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-briefcase"></i>
                    <h5>Data Jabatan</h5>
                </div>
                <div class="form-section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Jabatan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-user-tie"></i>
                                </span>
                                <input type="text" 
                                       name="jabatan" 
                                       class="form-control" 
                                       placeholder="Contoh: Kepala Seksi Dokumen"
                                       required>
                            </div>
                            <small class="form-text">Nama jabatan pegawai saat ini</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                TMT Jabatan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" 
                                       name="tmtJabatan" 
                                       class="form-control" 
                                       required>
                            </div>
                            <small class="form-text">Terhitung Mulai Tanggal Jabatan</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Bagian Kerja <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-building"></i>
                                </span>
                                <input type="text" 
                                       name="bagianKerja" 
                                       class="form-control" 
                                       placeholder="Contoh: Seksi Lalu Lintas Keimigrasian"
                                       required>
                            </div>
                            <small class="form-text">Unit kerja/bagian tempat bertugas</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Eselon <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-layer-group"></i>
                                </span>
                                <select name="eselon" class="form-control" required>
                                    <option value="" selected disabled>Pilih Eselon</option>
                                    <option value="Non Eselon">Non Eselon</option>
                                    <option value="Eselon V">Eselon V</option>
                                    <option value="Eselon IV/b">Eselon IV/b</option>
                                    <option value="Eselon IV/a">Eselon IV/a</option>
                                    <option value="Eselon III/b">Eselon III/b</option>
                                    <option value="Eselon III/a">Eselon III/a</option>
                                    <option value="Eselon II/b">Eselon II/b</option>
                                    <option value="Eselon II/a">Eselon II/a</option>
                                    <option value="Eselon I/b">Eselon I/b</option>
                                    <option value="Eselon I/a">Eselon I/a</option>
                                </select>
                            </div>
                            <small class="form-text">Tingkat eselon jabatan struktural</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Data Pendidikan & Pensiun -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-graduation-cap"></i>
                    <h5>Data Pendidikan Keimigrasian & Pensiun</h5>
                </div>
                <div class="form-section-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Angkatan PEJIM <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </span>
                                <input type="text" 
                                       name="angkatanPejim" 
                                       class="form-control" 
                                       placeholder="Contoh: Angkatan 15"
                                       required>
                            </div>
                            <small class="form-text">PEJIM: Pendidikan Keimigrasian</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                TMT Pensiun <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </span>
                                <input type="date" 
                                       name="tmtPensiun" 
                                       class="form-control" 
                                       required>
                            </div>
                            <small class="form-text">Terhitung Mulai Tanggal Pensiun</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="list-pegawai.php" class="btn-cancel">
                    <i class="fas fa-times me-2"></i>Batal
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
document.getElementById('formPegawai').addEventListener('submit', function(e) {
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
</script>

<?php include '../../../includes/footer.php'; ?>