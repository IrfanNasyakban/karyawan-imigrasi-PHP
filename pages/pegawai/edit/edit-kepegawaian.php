<?php
require_once '../../../config/database.php';

$page_title = 'Edit Data Kepegawaian';

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../list-kepegawaian.php?error=invalid_request");
    exit();
}

$idKepegawaian = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data kepegawaian dan pegawai berdasarkan ID
$query = "SELECT k.*, p.nip, p.namaDenganGelar, p.statusPegawai 
          FROM kepegawaian k
          LEFT JOIN pegawai p ON k.idPegawai = p.idPegawai
          WHERE k.idKepegawaian = '$idKepegawaian'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: ../list-kepegawaian.php?error=tidak_ditemukan");
    exit();
}

$kepegawaian = mysqli_fetch_assoc($result);

// Proses update data
if (isset($_POST['submit'])) {
    $statusKepegawaian = mysqli_real_escape_string($conn, $_POST['statusKepegawaian']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $tmtJabatan = mysqli_real_escape_string($conn, $_POST['tmtJabatan']);
    $bagianKerja = mysqli_real_escape_string($conn, $_POST['bagianKerja']);
    $eselon = mysqli_real_escape_string($conn, $_POST['eselon']);
    $angkatanPejim = mysqli_real_escape_string($conn, $_POST['angkatanPejim']);
    $ppns = mysqli_real_escape_string($conn, $_POST['ppns']);
    $tmtPensiun = mysqli_real_escape_string($conn, $_POST['tmtPensiun']);
    
    $queryUpdate = "UPDATE kepegawaian SET 
                    statusKepegawaian = '$statusKepegawaian',
                    jabatan = '$jabatan',
                    tmtJabatan = '$tmtJabatan',
                    bagianKerja = '$bagianKerja',
                    eselon = '$eselon',
                    angkatanPejim = '$angkatanPejim',
                    ppns = '$ppns',
                    tmtPensiun = '$tmtPensiun'
                    WHERE idKepegawaian = '$idKepegawaian'";
    
    if (mysqli_query($conn, $queryUpdate)) {
        header("Location: ../list-kepegawaian.php?message=edit");
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
            <h2><i class="fas fa-user-edit me-2"></i>Edit Data Kepegawaian</h2>
            <p>Formulir Edit Data Kepegawaian - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-user-edit page-header-icon d-none d-md-block"></i>
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
                <div class="info-value"><?php echo htmlspecialchars($kepegawaian['namaDenganGelar']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-id-card"></i> NIP
                </div>
                <div class="info-value"><?php echo htmlspecialchars($kepegawaian['nip']); ?></div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="" id="formKepegawaian">
            
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
                                    <option value="" disabled>Pilih Status Kepegawaian</option>
                                    <option value="PNS" <?php echo ($kepegawaian['statusKepegawaian'] == 'PNS') ? 'selected' : ''; ?>>PNS</option>
                                    <option value="CPNS" <?php echo ($kepegawaian['statusKepegawaian'] == 'CPNS') ? 'selected' : ''; ?>>CPNS</option>
                                    <option value="PPPK" <?php echo ($kepegawaian['statusKepegawaian'] == 'PPPK') ? 'selected' : ''; ?>>PPPK</option>
                                    <option value="OUTSOURCING" <?php echo ($kepegawaian['statusKepegawaian'] == 'OUTSOURCING') ? 'selected' : ''; ?>>OUTSOURCING</option>
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
                                    <option value="" disabled>Pilih Status PPNS</option>
                                    <option value="Ya" <?php echo ($kepegawaian['ppns'] == 'Ya') ? 'selected' : ''; ?>>Ya</option>
                                    <option value="Tidak" <?php echo ($kepegawaian['ppns'] == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
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
                                       value="<?php echo htmlspecialchars($kepegawaian['jabatan']); ?>"
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
                                       value="<?php echo htmlspecialchars($kepegawaian['tmtJabatan']); ?>"
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
                                       value="<?php echo htmlspecialchars($kepegawaian['bagianKerja']); ?>"
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
                                    <option value="" disabled>Pilih Eselon</option>
                                    <option value="Non Eselon" <?php echo ($kepegawaian['eselon'] == 'Non Eselon') ? 'selected' : ''; ?>>Non Eselon</option>
                                    <option value="Eselon V" <?php echo ($kepegawaian['eselon'] == 'Eselon V') ? 'selected' : ''; ?>>Eselon V</option>
                                    <option value="Eselon IV/b" <?php echo ($kepegawaian['eselon'] == 'Eselon IV/b') ? 'selected' : ''; ?>>Eselon IV/b</option>
                                    <option value="Eselon IV/a" <?php echo ($kepegawaian['eselon'] == 'Eselon IV/a') ? 'selected' : ''; ?>>Eselon IV/a</option>
                                    <option value="Eselon III/b" <?php echo ($kepegawaian['eselon'] == 'Eselon III/b') ? 'selected' : ''; ?>>Eselon III/b</option>
                                    <option value="Eselon III/a" <?php echo ($kepegawaian['eselon'] == 'Eselon III/a') ? 'selected' : ''; ?>>Eselon III/a</option>
                                    <option value="Eselon II/b" <?php echo ($kepegawaian['eselon'] == 'Eselon II/b') ? 'selected' : ''; ?>>Eselon II/b</option>
                                    <option value="Eselon II/a" <?php echo ($kepegawaian['eselon'] == 'Eselon II/a') ? 'selected' : ''; ?>>Eselon II/a</option>
                                    <option value="Eselon I/b" <?php echo ($kepegawaian['eselon'] == 'Eselon I/b') ? 'selected' : ''; ?>>Eselon I/b</option>
                                    <option value="Eselon I/a" <?php echo ($kepegawaian['eselon'] == 'Eselon I/a') ? 'selected' : ''; ?>>Eselon I/a</option>
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
                                       value="<?php echo htmlspecialchars($kepegawaian['angkatanPejim']); ?>"
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
                                       value="<?php echo htmlspecialchars($kepegawaian['tmtPensiun']); ?>"
                                       required>
                            </div>
                            <small class="form-text">Terhitung Mulai Tanggal Pensiun</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="../list-kepegawaian.php" class="btn-cancel">
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

// Validate TMT Pensiun should be after TMT Jabatan
document.querySelector('input[name="tmtPensiun"]').addEventListener('change', function() {
    const tmtJabatan = document.querySelector('input[name="tmtJabatan"]').value;
    const tmtPensiun = this.value;
    
    if (tmtJabatan && tmtPensiun && tmtPensiun < tmtJabatan) {
        alert('TMT Pensiun tidak boleh lebih awal dari TMT Jabatan!');
        this.value = '';
        this.classList.add('is-invalid');
    }
});
</script>

<?php include '../../../includes/footer.php'; ?>