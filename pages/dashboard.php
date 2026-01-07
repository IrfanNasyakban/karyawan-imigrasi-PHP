<?php
require_once '../config/database.php';

$page_title = 'Dashboard';

// Hitung total pegawai
$query_total = "SELECT COUNT(*) as total FROM pegawai";
$result_total = mysqli_query($conn, $query_total);
$total_pegawai = mysqli_fetch_assoc($result_total)['total'];

// Hitung total per kategori (contoh)
$query_kepegawaian = "SELECT COUNT(*) as total FROM kepegawaian";
$result_kepegawaian = mysqli_query($conn, $query_kepegawaian);
$total_kepegawaian = mysqli_fetch_assoc($result_kepegawaian)['total'];

$query_pangkat = "SELECT COUNT(*) as total FROM pangkat";
$result_pangkat = mysqli_query($conn, $query_pangkat);
$total_pangkat = mysqli_fetch_assoc($result_pangkat)['total'];

// Include sidebar (yang sudah ada HTML header)
include '../includes/sidebar.php';
?>

<!-- Dashboard Content -->
<div class="row mb-4">
    <!-- Welcome Card -->
    <div class="col-12 mb-4">
        
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <!-- Total Pegawai -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card border-0 shadow-sm h-100 card-hover">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                    <span class="badge bg-success">
                        <i class="fas fa-arrow-up me-1"></i>100%
                    </span>
                </div>
                <h6 class="text-muted mb-2">Total Pegawai</h6>
                <h2 class="fw-bold mb-0"><?php echo number_format($total_pegawai); ?></h2>
                <small class="text-muted">Pegawai terdaftar</small>
            </div>
        </div>
    </div>

    <!-- Quick Action -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card border-0 shadow-sm h-100 card-hover" style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);">
            <div class="card-body text-white d-flex flex-column justify-content-center">
                <div class="text-center">
                    <i class="fas fa-user-plus fa-3x mb-3 opacity-75"></i>
                    <h6 class="mb-3">Tambah Pegawai Baru</h6>
                    <a href="pegawai/tambah-pegawai.php" class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-2"></i>Tambah Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Information Section -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-info-circle text-primary me-2"></i>Tentang Si-Gawai
                </h5>
                <p class="text-muted mb-3">
                    Sistem Informasi Kepegawaian (Si-Gawai) adalah platform digital untuk mengelola 
                    seluruh data pegawai di Kantor Imigrasi Kelas II TPI Lhokseumawe secara terpadu 
                    dan terstruktur. Sistem ini digunakan untuk mengelola data karyawan di Kantor Imigrasi Kelas II TPI Lhokseumawe. Anda dapat melihat, menambah, mengubah dan menghapus data karyawan melalui menu yang tersedia.
                </p>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-headset text-info me-2"></i>Bantuan
                </h5>
                <p class="text-muted small mb-3">
                    Butuh bantuan? Hubungi administrator sistem.
                </p>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-book me-2"></i>Panduan Penggunaan
                    </button>
                    <button class="btn btn-outline-success btn-sm">
                        <i class="fas fa-phone me-2"></i>Hubungi Admin
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>