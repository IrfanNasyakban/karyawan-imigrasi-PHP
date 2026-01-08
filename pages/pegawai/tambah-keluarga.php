<?php
// Enable error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../config/database.php';

$page_title = 'Tambah Data Keluarga';

$idPegawai = $_GET['idPegawai'] ?? null;
if (!$idPegawai) {
    header("Location: tambah-pegawai.php");
    exit();
}

if (isset($_POST['btn_submit'])) {
    // Debug: tampilkan data POST
    // var_dump($_POST); // Uncomment untuk debug
    
    $idPegawai = mysqli_real_escape_string($conn, $_POST['idPegawai'] ?? '');
    $namaPasangan = mysqli_real_escape_string($conn, $_POST['namaPasangan'] ?? '');
    $jumlahAnak = intval($_POST['jumlahAnak'] ?? 0);

    if (empty($idPegawai)) {
        $error = "ID Pegawai kosong. Pastikan alur tambah pegawai benar.";
    } else {
        // Cek apakah data pasangan sudah ada
        $checkPasangan = "SELECT idPasangan FROM pasangan WHERE idPegawai = '$idPegawai'";
        $resultCheck = mysqli_query($conn, $checkPasangan);
        
        if (mysqli_num_rows($resultCheck) > 0) {
            $error = "Data pasangan untuk pegawai ini sudah ada!";
        } else {
            // Mulai transaksi
            mysqli_begin_transaction($conn);
            
            try {
                // Insert data pasangan
                $queryPasangan = "INSERT INTO pasangan (idPegawai, namaPasangan) 
                                 VALUES ('$idPegawai', '$namaPasangan')";
                
                if (!mysqli_query($conn, $queryPasangan)) {
                    throw new Exception("Gagal menambahkan data pasangan: " . mysqli_error($conn));
                }
                
                // Insert data anak jika ada
                if ($jumlahAnak > 0) {
                    for ($i = 1; $i <= $jumlahAnak; $i++) {
                        $namaAnak = mysqli_real_escape_string($conn, $_POST["namaAnak$i"] ?? '');
                        
                        if (!empty($namaAnak)) {
                            $queryAnak = "INSERT INTO anak (idPegawai, namaAnak) 
                                         VALUES ('$idPegawai', '$namaAnak')";
                            
                            if (!mysqli_query($conn, $queryAnak)) {
                                throw new Exception("Gagal menambahkan data anak ke-$i: " . mysqli_error($conn));
                            }
                        }
                    }
                }
                
                // Commit transaksi
                if (mysqli_commit($conn)) {
                    // Set session success message
                    $_SESSION['success_message'] = 'Data pegawai berhasil ditambahkan lengkap!';
                    exit();
                } else {
                    throw new Exception("Gagal commit: " . mysqli_error($conn));
                }
                
            } catch (Exception $e) {
                // Rollback jika terjadi error
                mysqli_rollback($conn);
                $error = $e->getMessage();
            }
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
            <h2><i class="fas fa-users me-2"></i>Tambah Data Keluarga</h2>
            <p>Formulir Penambahan Data Pasangan & Anak - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-users page-header-icon d-none d-md-block"></i>
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
        <div class="step completed">
            <div class="step-number">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Data Rekening</div>
        </div>
        <div class="step-line completed"></div>
        <div class="step completed">
            <div class="step-number">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Data Pendidikan</div>
        </div>
        <div class="step-line completed"></div>
        <div class="step completed">
            <div class="step-number">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Data Fisik</div>
        </div>
        <div class="step-line completed"></div>
        <div class="step completed">
            <div class="step-number">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Data Ukuran</div>
        </div>
        <div class="step-line completed"></div>
        <div class="step active">
            <div class="step-number">10</div>
            <div class="step-label">Data Keluarga</div>
        </div>
    </div>

    <!-- Alert Error -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
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
        <form method="POST" action="" id="formKeluarga">
            <input type="hidden" name="idPegawai" value="<?php echo htmlspecialchars($idPegawai); ?>">
            
            <!-- Section 1: Data Pasangan -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-user-friends"></i>
                    <h5>Data Pasangan (Suami/Istri)</h5>
                </div>
                <div class="form-section-body">
                    <div class="alert alert-info-custom mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Jika belum menikah atau tidak memiliki pasangan, silakan isi dengan tanda strip (-).
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">
                                Nama Lengkap Pasangan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-ring"></i>
                                </span>
                                <input type="text" 
                                       name="namaPasangan" 
                                       id="namaPasangan"
                                       class="form-control" 
                                       placeholder="Masukkan nama lengkap pasangan atau (-) jika belum menikah"
                                       required>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Nama lengkap suami/istri sesuai KTP atau isi dengan (-) jika belum menikah
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Anak -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-child"></i>
                    <h5>Data Anak</h5>
                </div>
                <div class="form-section-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                Jumlah Anak <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-icon">
                                    <i class="fas fa-calculator"></i>
                                </span>
                                <input type="number" 
                                       name="jumlahAnak" 
                                       id="jumlahAnak" 
                                       class="form-control" 
                                       min="0" 
                                       max="20" 
                                       value="0"
                                       placeholder="0"
                                       required>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Masukkan 0 jika tidak memiliki anak
                            </small>
                        </div>
                    </div>

                    <!-- Container untuk input nama anak -->
                    <div id="containerAnak" class="mt-4">
                        <!-- Input nama anak akan ditambahkan di sini secara dinamis -->
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" name="btn_submit" id="btn_submit" class="btn-submit btn-success-gradient">
                    <i class="fas fa-check-circle me-2"></i>Selesai & Simpan Semua Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Wrap all code in DOMContentLoaded to ensure DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded');
    
    // Auto scroll to active step
    const progressSteps = document.querySelector('.progress-steps');
    const activeStep = document.querySelector('.step.active');
    
    if (progressSteps && activeStep) {
        const scrollLeft = activeStep.offsetLeft - (progressSteps.offsetWidth / 2) + (activeStep.offsetWidth / 2);
        progressSteps.scrollTo({
            left: scrollLeft,
            behavior: 'smooth'
        });
    }

    // Dynamic Anak Fields
    const jumlahAnakInput = document.getElementById('jumlahAnak');
    const containerAnak = document.getElementById('containerAnak');

    if (jumlahAnakInput && containerAnak) {
        jumlahAnakInput.addEventListener('input', function() {
            const jumlah = parseInt(this.value) || 0;
            containerAnak.innerHTML = '';
            
            if (jumlah > 0) {
                const heading = document.createElement('div');
                heading.className = 'anak-header mb-3';
                heading.innerHTML = `
                    <div class="alert alert-success" style="background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border-left: 4px solid #0891b2; color: #0c4a6e;">
                        <i class="fas fa-child me-2"></i>
                        <strong>Silakan isi nama-nama anak (${jumlah} anak)</strong>
                    </div>
                `;
                containerAnak.appendChild(heading);
                
                const anakGrid = document.createElement('div');
                anakGrid.className = 'row';
                
                for (let i = 1; i <= jumlah; i++) {
                    const divAnak = document.createElement('div');
                    divAnak.className = 'col-md-6 mb-3';
                    divAnak.innerHTML = `
                        <label class="form-label">
                            <i class="fas fa-baby me-1 text-primary"></i>
                            Nama Anak ke-${i} <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-icon">
                                <i class="fas fa-child"></i>
                            </span>
                            <input type="text" 
                                   name="namaAnak${i}" 
                                   class="form-control" 
                                   placeholder="Masukkan nama anak ke-${i}" 
                                   required>
                        </div>
                    `;
                    anakGrid.appendChild(divAnak);
                }
                
                containerAnak.appendChild(anakGrid);
            }
        });
        
        // Trigger initial
        jumlahAnakInput.dispatchEvent(new Event('input'));
    } else {
        console.error('jumlahAnakInput or containerAnak not found');
    }
    
    console.log('All event listeners attached successfully');
});
</script>

<?php include '../../includes/footer.php'; ?>