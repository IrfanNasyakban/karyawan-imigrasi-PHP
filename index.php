<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan - Kantor Imigrasi Lhokseumawe</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
         body {
            background-image: url('assets/bg-awal.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .header-logo {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 25px;
        }
        
        .header-logo img {
            height: 60px;
            width: auto;
        }
        
        .header-text {
            display: flex;
            flex-direction: column;
            line-height: 1.3;
        }
        
        .header-text .main-title {
            font-size: 11px;
            font-weight: 700;
            color: #ffffffff;
            text-transform: uppercase;
            margin: 0;
        }
        
        .header-text .sub-title {
            font-size: 10px;
            font-weight: 600;
            color: #ffffffff;
            text-transform: uppercase;
            margin: 0;
        }
        
        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 20px 20px;
            text-align: center;
            max-width: 600px;
            animation: fadeInUp 0.8s ease;
            position: relative;
            z-index: 2;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logo-container {
            margin-bottom: 30px;
        }
        
        .logo-container img {
            width: 250px;
            height: auto;
            object-fit: contain;
            max-height: 150px;
        }
        
        h1 {
            color: #333;
            font-weight: 400;
            margin-bottom: 15px;
        }
        
        .subtitle {
            color: #666;
            font-size: 18px;
            margin-bottom: 40px;
        }
        
        .btn-start {
            padding: 10px 30px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 50px;
            background: linear-gradient(135deg, #748dffff 0%, #6ba9ffff 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-start:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.6);
            color: #ffffffff;
        }
        
        .info-text {
            margin-top: 30px;
            color: #999;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header-logo">
        <img src="assets/logo_pemasyarakatan.png" alt="Logo Kemenkumham">
        <img src="assets/logo_imigrasi.png" alt="Logo Imigrasi">
        <div class="header-text">
            <p class="main-title">Kementerian Imigrasi dan Pemasyarakatan</p>
            <p class="sub-title">Kantor Wilayah Direktorat Jenderal Imigrasi Aceh</p>
            <p class="sub-title">Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
    </div>

    <div class="welcome-card">
        <div class="logo-container">
            <img src="assets/logo_sigawai.png" alt="Logo Imigrasi">
        </div>
        
        <h4>Sistem Informasi Kepegawaian</h4>
        <p class="subtitle">Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        
        <a href="pages/dashboard.php" class="btn btn-start">
            <i class="fas fa-arrow-right me-2"></i>Melanjutkan
        </a>
        
        <p class="info-text">
            <i class="fas fa-info-circle me-1"></i>
            Sistem Manajemen Data Kepegawaian
        </p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>