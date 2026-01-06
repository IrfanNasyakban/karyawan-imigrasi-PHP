<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Data Karyawan'; ?> - Kantor Imigrasi Lhokseumawe</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="/karyawan-imigrasi/pages/dashboard.php">
                <i class="fas fa-building me-2"></i>
                Kantor Imigrasi Lhokseumawe
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/karyawan-imigrasi/pages/dashboard.php">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/karyawan-imigrasi/pages/pegawai/list-pegawai.php">
                            <i class="fas fa-users me-1"></i>Data Pegawai
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/karyawan-imigrasi/pages/pegawai/list-kepegawaian.php">
                            <i class="fas fa-users me-1"></i>Data Kepegawaian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/karyawan-imigrasi/pages/pegawai/list-pangkat.php">
                            <i class="fas fa-users me-1"></i>Data Pangkat
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>