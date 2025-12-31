<?php
require_once '../config/database.php';

$page_title = 'Dashboard';

// Hitung total karyawan
$query_total = "SELECT COUNT(*) as total FROM karyawan";
$result_total = mysqli_query($conn, $query_total);
$total_karyawan = mysqli_fetch_assoc($result_total)['total'];

include '../includes/header.php';
?>

<div class="container my-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </h2>
            <p class="text-muted">Sistem Manajemen Data Karyawan</p>
        </div>
    </div>

    <!-- Cards -->
    <div class="row">
        <!-- Card Total Karyawan -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Karyawan</h6>
                            <h2 class="fw-bold mb-0"><?php echo $total_karyawan; ?></h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Menu Data Karyawan -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100 card-hover">
                <div class="card-body d-flex align-items-center justify-content-center">
                    <a href="karyawan/index.php" class="text-decoration-none text-center w-100">
                        <i class="fas fa-users-cog fa-3x text-success mb-3"></i>
                        <h5 class="fw-bold">Data Karyawan</h5>
                        <p class="text-muted small mb-0">Kelola data karyawan</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Tambah Karyawan -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100 card-hover">
                <div class="card-body d-flex align-items-center justify-content-center">
                    <a href="karyawan/tambah.php" class="text-decoration-none text-center w-100">
                        <i class="fas fa-user-plus fa-3x text-info mb-3"></i>
                        <h5 class="fw-bold">Tambah Karyawan</h5>
                        <p class="text-muted small mb-0">Tambah karyawan baru</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-info-circle me-2"></i>Informasi Sistem
                    </h5>
                    <p class="mb-0">
                        Sistem ini digunakan untuk mengelola data karyawan di Kantor Imigrasi Kelas I TPI Lhokseumawe. 
                        Anda dapat melihat, menambah, mengubah, dan menghapus data karyawan melalui menu yang tersedia.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>