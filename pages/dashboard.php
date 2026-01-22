<?php
require_once '../config/database.php';
require_once '../includes/check_login.php';

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

<style>
/* Dashboard Background Styles */
body {
    background-image: url('../assets/bg-kedua.webp');
    background-attachment: fixed;
    background-size: cover;
    background-position: center;
    min-height: 100vh;
    position: relative;
}

/* Overlay biru semi-transparent */
body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(198, 232, 255, 0.7);
    z-index: -1;
}

.dashboard-container {
    padding: 3rem 2rem;
    position: relative;
    z-index: 1;
}

/* Header Title */
.dashboard-title {
    text-align: center;
    color: #000;
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 3rem;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.card-link {
    text-decoration: none;
    color: inherit;
}

/* Card Statistics - Simple White */
.stat-card {
    background: #fff;
    border-radius: 15px;
    padding: 1rem 1rem;
    text-align: center;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    height: 100%;
    min-height: 280px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.stat-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

/* Icon Container */
.stat-icon {
    width: 120px;
    height: 100px;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon i {
    font-size: 80px;
    color: #000;
}

/* Title */
.stat-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #0088cc;
    margin-bottom: 0.5rem;
}

/* Subtitle */
.stat-subtitle {
    font-size: 1rem;
    color: #333;
    margin-bottom: 0;
}

/* Information Section */
.info-section {
    background: rgba(100, 160, 200, 0.9);
    border-radius: 15px;
    padding: 2rem;
    margin-top: 3rem;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.info-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #fff;
    margin-bottom: 1rem;
}

.info-text {
    font-size: 1rem;
    color: #fff;
    line-height: 1.6;
    margin-bottom: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-title {
        font-size: 1.8rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        min-height: 250px;
        padding: 2rem 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-icon {
        width: 100px;
        height: 100px;
    }
    
    .stat-icon i {
        font-size: 60px;
    }
    
    .stat-title {
        font-size: 1.3rem;
    }
    
    .dashboard-container {
        padding: 2rem 1rem;
    }
    
    body {
        background-attachment: scroll;
    }
}
</style>

<div class="dashboard-container">
    <!-- Main Title -->
    <h1 class="dashboard-title">SISTEM MANAJEMEN DATA KEPEGAWAIAN</h1>

    <!-- Statistics Cards -->
    <div class="row justify-content-center">
        <!-- Total Pegawai -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h2 class="stat-number"><?php echo number_format($total_pegawai); ?></h2>
                <h3 class="stat-title">Total Pegawai</h3>
                <p class="stat-subtitle">Total Pegawai Kantor</p>
            </div>
        </div>

        <!-- Data Pegawai -->
        <div class="col-md-6 col-lg-4 mb-4">
            <a href="pegawai/list-pegawai.php" class="card-link">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <h3 class="stat-title">Data Pegawai</h3>
                    <p class="stat-subtitle">Kelola data Pegawai</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Information Section -->
    <div class="row">
        <div class="col-12">
            <div class="info-section">
                <h2 class="info-title">Informasi Sistem</h2>
                <p class="info-text">
                    Sistem ini digunakan untuk mengelola data pegawai di Kantor Imigrasi Kelas II TPI Lhokseumawe. 
                    Anda dapat melihat, menambah, mengubah dan menghapus data pegawai melalui menu yang tersedia.
                </p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>