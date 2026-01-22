<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIGAWAI Kantor Imigrasi Lhokseumawe</title>
    
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

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 1;
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
            color: #ffffff;
            text-transform: uppercase;
            margin: 0;
        }
        
        .header-text .sub-title {
            font-size: 10px;
            font-weight: 600;
            color: #ffffff;
            text-transform: uppercase;
            margin: 0;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px 45px;
            max-width: 450px;
            width: 100%;
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
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo-container img {
            width: 180px;
            height: auto;
            object-fit: contain;
            max-height: 120px;
        }
        
        h4 {
            color: #333;
            font-weight: 600;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .subtitle {
            color: #666;
            font-size: 15px;
            margin-bottom: 35px;
            text-align: center;
        }
        
        .form-label {
            color: #555;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-control {
            padding: 12px 15px;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .form-control:focus {
            border-color: #748dff;
            box-shadow: 0 0 0 0.2rem rgba(116, 141, 255, 0.15);
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group-text {
            background: transparent;
            border: 2px solid #e0e0e0;
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: #748dff;
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
        
        .input-group:focus-within .input-group-text {
            border-color: #748dff;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            z-index: 10;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: #748dff;
        }
        
        .btn-login {
            width: 100%;
            padding: 13px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 10px;
            background: linear-gradient(135deg, #748dff 0%, #6ba9ff 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(116, 141, 255, 0.4);
            margin-top: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(116, 141, 255, 0.5);
            color: white;
        }
        
        .form-check {
            margin: 15px 0 20px 0;
        }
        
        .form-check-input:checked {
            background-color: #748dff;
            border-color: #748dff;
        }
        
        .form-check-label {
            color: #666;
            font-size: 14px;
        }
        
        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
            border: none;
            font-size: 14px;
        }
        
        .info-text {
            margin-top: 25px;
            text-align: center;
            color: #999;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .header-logo {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .header-logo img {
                height: 45px;
            }
            
            .login-card {
                margin: 20px;
                padding: 30px 25px;
            }
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

    <div class="login-card">
        <div class="logo-container">
            <img src="assets/logo_sigawai.png" alt="Logo SIGAWAI">
        </div>
        
        <h4>Sistem Informasi Kepegawaian</h4>
        <p class="subtitle">Silakan login untuk melanjutkan</p>
        
        <!-- Alert Example (uncomment to show) -->
        <!-- <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>Username atau password salah!
        </div> -->
        
        <form action="pages/proses_login.php" method="POST" id="loginForm">
            <div class="mb-3">
                <label for="username" class="form-label">
                    <i class="fas fa-user me-1"></i>Username
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" class="form-control" id="username" name="username" 
                           placeholder="Masukkan username" required autocomplete="username">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">
                    <i class="fas fa-lock me-1"></i>Password
                </label>
                <div class="input-group" style="position: relative;">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Masukkan password" required autocomplete="current-password">
                    <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                </div>
            </div>
            
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                <label class="form-check-label" for="remember">
                    Ingat saya
                </label>
            </div>
            
            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </button>
        </form>
        
        <p class="info-text">
            <i class="fas fa-shield-alt me-1"></i>
            Sistem dilindungi dengan keamanan tingkat tinggi
        </p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
        
        // Form validation
        const loginForm = document.getElementById('loginForm');
        loginForm.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            if (username === '' || password === '') {
                e.preventDefault();
                alert('Username dan password harus diisi!');
            }
        });
    </script>
</body>
</html>