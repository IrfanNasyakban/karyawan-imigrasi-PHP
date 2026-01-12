<?php
// Deteksi path logo berdasarkan lokasi file
$depth = substr_count($_SERVER['PHP_SELF'], '/') - 2;
$logo_path = str_repeat('../', $depth - 1) . 'assets/logo_sigawai.png';

// Alternatif: deteksi manual
if (strpos($_SERVER['PHP_SELF'], '/pages/pegawai/tambah/') !== false) {
        $logo_path = '../../../assets/logo_sigawai.png';
    } 
    else if (strpos($_SERVER['PHP_SELF'], '/pages/pegawai/') !== false) {
        $logo_path = '../../assets/logo_sigawai.png';
    }
else {
    $logo_path = '../assets/logo_sigawai.png';
}

// Deteksi apakah ini halaman dashboard
$is_dashboard = (basename($_SERVER['PHP_SELF']) == 'dashboard.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'SI-GAWAI'; ?> - Kantor Imigrasi Lhokseumawe</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-bg: #0891b2;
            --sidebar-hover: #06809b;
            --sidebar-active: #06799a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            overflow-x: hidden;
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        /* Sidebar Header */
        .sidebar-header {
            background: white;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid rgba(8, 145, 178, 0.2);
        }

        .sidebar-header img {
            max-width: 200px;
            height: auto;
        }

        /* Menu Section */
        .menu-section {
            padding: 20px 0;
        }

        .menu-title {
            color: rgba(255, 255, 255, 0.6);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 20px 10px;
        }

        /* Menu Item */
        .menu-item {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            color: white;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            background: var(--sidebar-hover);
            color: white;
            border-left-color: white;
            padding-left: 25px;
        }

        .menu-item.active {
            background: var(--sidebar-active);
            border-left-color: #fbbf24;
            font-weight: 600;
        }

        .menu-item i {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            margin-right: 12px;
            font-size: 16px;
        }

        .menu-item:hover i,
        .menu-item.active i {
            background: rgba(255, 255, 255, 0.2);
        }

        .menu-item span {
            font-size: 15px;
        }

        /* Submenu */
        .submenu {
            display: none;
            background: rgba(0, 0, 0, 0.2);
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .submenu.show {
            display: block;
        }

        .submenu .menu-item {
            padding-left: 67px;
            font-size: 14px;
        }

        .submenu .menu-item:hover {
            padding-left: 72px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            min-height: 100vh;
        }

        /* Top Bar - Default (untuk halaman selain dashboard) */
        .top-bar {
            background: white;
            padding: 15px 30px;
            margin: -30px -30px 30px -30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .top-bar img {
            max-width: 250px;
            height: auto;
        }

        /* Top Bar Transparent - Hanya untuk Dashboard */
        .top-bar.transparent {
            background: transparent;
            box-shadow: none;
            padding: 15px 30px;
        }

        /* Mobile */
        .mobile-toggle {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 56px;
            height: 56px;
            background: var(--sidebar-bg);
            color: white;
            border: none;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 998;
            font-size: 20px;
            cursor: pointer;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        @media (max-width: 768px) {
            .sidebar {
                left: -280px;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
            }

            .overlay.show {
                display: block;
            }
        }

        /* Card Hover */
        .card-hover {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
</head>
<body>
    <!-- Mobile Toggle -->
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Overlay -->
    <div class="overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Menu -->
        <div class="menu-section">
            <!-- Dashboard -->
            <h6 class="menu-title">Dashboard</h6>
            <a href="/karyawan-imigrasi/pages/dashboard.php" 
               class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                <span>Homepage Si-Gawai</span>
            </a>

            <!-- Data Pegawai -->
            <h6 class="menu-title">Data Pegawai</h6>

            <a href="/karyawan-imigrasi/pages/pegawai/list-pegawai.php" 
               class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'list-pegawai.php') ? 'active' : ''; ?>">
                <i class="fas fa-user"></i>
                <span>Pegawai</span>
            </a>
            
            <a href="/karyawan-imigrasi/pages/pegawai/list-kepegawaian.php" 
               class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'list-kepegawaian.php') ? 'active' : ''; ?>">
                <i class="fas fa-id-card"></i>
                <span>Kepegawaian</span>
            </a>

            <a href="/karyawan-imigrasi/pages/pegawai/list-pangkat.php" 
               class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'list-pangkat.php') ? 'active' : ''; ?>">
                <i class="fas fa-medal"></i>
                <span>Pangkat</span>
            </a>

            <a href="/karyawan-imigrasi/pages/pegawai/list-alamat.php" 
               class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'list-alamat.php') ? 'active' : ''; ?>">
                <i class="fas fa-map-marker-alt"></i>
                <span>Alamat</span>
            </a>

            <a href="/karyawan-imigrasi/pages/pegawai/list-identitas.php" 
               class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'list-identitas.php') ? 'active' : ''; ?>">
                <i class="fas fa-address-card"></i>
                <span>Identitas</span>
            </a>

            <a href="/karyawan-imigrasi/pages/pegawai/list-rekening.php" 
               class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'list-rekening.php') ? 'active' : ''; ?>">
                <i class="fas fa-credit-card"></i>
                <span>Rekening</span>
            </a>

            <a href="/karyawan-imigrasi/pages/pegawai/list-pendidikan.php" 
               class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'list-pendidikan.php') ? 'active' : ''; ?>">
                <i class="fas fa-graduation-cap"></i>
                <span>Pendidikan</span>
            </a>

            <a href="/karyawan-imigrasi/pages/pegawai/list-fisik.php" 
               class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'list-fisik.php') ? 'active' : ''; ?>">
                <i class="fas fa-heartbeat"></i>
                <span>Fisik</span>
            </a>

            <a href="/karyawan-imigrasi/pages/pegawai/list-ukuran-dinas.php" 
               class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'list-ukuran-dinas.php') ? 'active' : ''; ?>">
                <i class="fas fa-building"></i>
                <span>Dinas</span>
            </a>

            <!-- Keluarga dengan Submenu -->
            <a href="javascript:void(0)" 
               class="menu-item <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['list-pasangan.php', 'list-anak.php'])) ? 'active' : ''; ?>"
               onclick="toggleSubmenu()">
                <i class="fas fa-users"></i>
                <span>Keluarga</span>
            </a>
            <ul class="submenu" id="submenu-keluarga">
                <li>
                    <a href="/karyawan-imigrasi/pages/pegawai/list-pasangan.php" 
                       class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'list-pasangan.php') ? 'active' : ''; ?>">
                        <i class="fas fa-heart"></i>
                        <span>Pasangan</span>
                    </a>
                </li>
                <li>
                    <a href="/karyawan-imigrasi/pages/pegawai/list-anak.php" 
                       class="menu-item <?php echo (basename($_SERVER['PHP_SELF']) == 'list-anak.php') ? 'active' : ''; ?>">
                        <i class="fas fa-child"></i>
                        <span>Anak</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar dengan kondisi transparent untuk dashboard -->
        <div class="top-bar <?php echo $is_dashboard ? 'transparent' : ''; ?>">
            <img src="<?php echo $logo_path; ?>" alt="SI-GAWAI">
        </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.querySelector('.overlay').classList.toggle('show');
        }

        function toggleSubmenu() {
            document.getElementById('submenu-keluarga').classList.toggle('show');
        }

        // Auto expand submenu jika halaman submenu aktif
        document.addEventListener('DOMContentLoaded', function() {
            const submenuActive = document.querySelector('.submenu .menu-item.active');
            if (submenuActive) {
                document.getElementById('submenu-keluarga').classList.add('show');
            }
        });
    </script>