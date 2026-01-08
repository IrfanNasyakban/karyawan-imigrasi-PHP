<?php
require_once '../../config/database.php';

$page_title = 'Tambah Data Kepegawaian';

$idPegawai = $_GET['idPegawai'] ?? null;
if (!$idPegawai) {
    header("Location: tambah-pegawai.php");
    exit();
}

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
            header("Location: tambah-pangkat.php?idPegawai=$idPegawai&message=tambah");
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
            <h2><i class="fas fa-briefcase me-2"></i>Tambah Data Kepegawaian</h2>
            <p>Formulir Penambahan Data Kepegawaian - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-briefcase page-header-icon d-none d-md-block"></i>
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
        <div class="step active">
            <div class="step-number">2</div>
            <div class="step-label">Data Kepegawaian</div>
        </div>
        <div class="step-line"></div>
        <div class="step">
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
        <form method="POST" action="" id="formKepegawaian">
            <input type="hidden" name="idPegawai" value="<?php echo htmlspecialchars($idPegawai); ?>">
            
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
document.getElementById('formKepegawaian').addEventListener('submit', function(e) {
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

<?php include '../../includes/footer.php'; ?>