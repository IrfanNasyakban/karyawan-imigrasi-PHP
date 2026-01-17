<?php
require_once '../../../config/database.php';

$page_title = 'Tambah Data Keluarga';

// Get ID Pegawai dari parameter URL atau POST
$idPegawai = isset($_GET['idPegawai']) ? $_GET['idPegawai'] : (isset($_POST['idPegawai']) ? $_POST['idPegawai'] : '');

if (isset($_POST['submit'])) {
    $idPegawai = mysqli_real_escape_string($conn, $_POST['idPegawai'] ?? '');
    $jumlahAnak = intval($_POST['jumlahAnak'] ?? 0);

    if ($idPegawai === '') {
        $error = "ID Pegawai kosong. Pastikan alur tambah pegawai benar.";
    } else {
        // Insert data anak jika ada
        if ($jumlahAnak > 0) {
            $successAnak = true;
            $successCount = 0;
            
            for ($i = 1; $i <= $jumlahAnak; $i++) {
                $namaAnak = mysqli_real_escape_string($conn, $_POST["namaAnak$i"] ?? '');
                
                if (!empty($namaAnak)) {
                    $queryAnak = "INSERT INTO anak (idPegawai, namaAnak) 
                                 VALUES ('$idPegawai', '$namaAnak')";
                    
                    if (mysqli_query($conn, $queryAnak)) {
                        $successCount++;
                    } else {
                        $successAnak = false;
                        $error = "Gagal menambahkan data anak ke-$i: " . mysqli_error($conn);
                        break;
                    }
                }
            }
            
            if ($successAnak && $successCount > 0) {
                header("Location: ../list-anak.php");
                exit();
            } elseif ($successCount == 0) {
                $error = "Tidak ada data anak yang berhasil ditambahkan.";
            }
        } else {
            // Jika jumlah anak 0, redirect dengan pesan khusus
            header("Location: ../list-anak.php");
            exit();
        }
    }
}

// Get all pegawai data for dropdown
$queryAllPegawai = "SELECT p.idPegawai, p.namaDenganGelar, p.nip 
                    FROM pegawai p
                    LEFT JOIN anak an ON p.idPegawai = an.idPegawai
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
            <h2><i class="fas fa-users me-2"></i>Tambah Data Keluarga (Anak)</h2>
            <p>Formulir Penambahan Data Anak Pegawai - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-users page-header-icon d-none d-md-block"></i>
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
        <form method="POST" action="" id="formKeluarga">
            
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
                            <small class="form-text">Pilih pegawai untuk menambahkan data anak</small>
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

            <!-- Section 1: Data Anak -->
            <div class="form-section">
                <div class="form-section-header">
                    <i class="fas fa-child"></i>
                    <h5>Data Anak</h5>
                </div>
                <div class="form-section-body">
                    <div class="alert alert-info-custom mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong> Jika pegawai tidak memiliki anak, silakan isi jumlah anak dengan 0 (nol).
                    </div>

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
                <button type="submit" name="submit" class="btn-submit">
                    <i class="fas fa-save me-2"></i>Simpan Data
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

// Auto scroll to active step
document.addEventListener('DOMContentLoaded', function() {
    const progressSteps = document.querySelector('.progress-steps');
    const activeStep = document.querySelector('.step.active');
    
    if (progressSteps && activeStep) {
        const scrollLeft = activeStep.offsetLeft - (progressSteps.offsetWidth / 2) + (activeStep.offsetWidth / 2);
        progressSteps.scrollTo({
            left: scrollLeft,
            behavior: 'smooth'
        });
    }
    
    if (progressSteps) {
        progressSteps.addEventListener('scroll', function() {
            const isScrolledToEnd = this.scrollLeft + this.clientWidth >= this.scrollWidth - 10;
            if (isScrolledToEnd) {
                this.classList.add('scrolled-end');
            } else {
                this.classList.remove('scrolled-end');
            }
        });
    }
});

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
                               class="form-control anak-input" 
                               placeholder="Masukkan nama lengkap anak ke-${i}" 
                               required>
                    </div>
                `;
                anakGrid.appendChild(divAnak);
            }
            
            containerAnak.appendChild(anakGrid);
        } else {
            // Jika jumlah anak 0, tampilkan pesan
            containerAnak.innerHTML = `
                <div class="alert alert-warning" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b; color: #78350f;">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Informasi:</strong> Pegawai tidak memiliki anak. Silakan klik "Simpan Data" untuk melanjutkan.
                </div>
            `;
        }
    });
    
    // Trigger initial
    jumlahAnakInput.dispatchEvent(new Event('input'));
}

// Form validation
document.getElementById('formKeluarga').addEventListener('submit', function(e) {
    const jumlahAnak = parseInt(document.getElementById('jumlahAnak').value) || 0;
    
    if (jumlahAnak > 0) {
        const requiredFields = this.querySelectorAll('[required]');
        let isValid = true;
        let errorMessages = [];
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
                
                // Get field label
                const label = field.closest('.mb-3')?.querySelector('.form-label')?.textContent.trim() || 'Field';
                errorMessages.push(label);
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi:\n- ' + errorMessages.join('\n- '));
        }
    } else {
        // Jika jumlah anak 0, pastikan user konfirmasi
        const konfirmasi = confirm('Anda akan menyimpan data tanpa anak. Apakah Anda yakin?');
        if (!konfirmasi) {
            e.preventDefault();
        }
    }
});

// Remove invalid class on input
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('form-control')) {
        e.target.classList.remove('is-invalid');
    }
});

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('form-control')) {
        e.target.classList.remove('is-invalid');
    }
});
</script>

<?php include '../../../includes/footer.php'; ?>