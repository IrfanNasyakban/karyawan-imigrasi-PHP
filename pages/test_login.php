<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Test Login Process</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .success { color: green; background: #d4edda; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .error { color: red; background: #f8d7da; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .info { color: #004085; background: #d1ecf1; padding: 10px; margin: 10px 0; border-radius: 5px; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow: auto; }
        h3 { margin-top: 30px; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
    </style>
</head>
<body>";

echo "<h2>🔐 Debug Login Process</h2>";

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    echo "<h3>📥 Step 1: Data yang Diterima</h3>";
    echo "<div class='info'>";
    echo "Username: <strong>" . htmlspecialchars($username) . "</strong><br>";
    echo "Password: <strong>" . str_repeat('*', strlen($password)) . "</strong> (panjang: " . strlen($password) . " karakter)";
    echo "</div>";
    
    // Validasi input kosong
    if (empty($username) || empty($password)) {
        echo "<div class='error'>❌ Username atau password kosong!</div>";
        die("</body></html>");
    }
    
    echo "<div class='success'>✅ Input tidak kosong</div>";
    
    echo "<h3>🔍 Step 2: Mencari User di Database</h3>";
    
    // Query cek username menggunakan mysqli
    $stmt = mysqli_prepare($conn, "SELECT * FROM admin WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $num_rows = mysqli_num_rows($result);
    
    echo "<div class='info'>Query dijalankan: <code>SELECT * FROM admin WHERE username = '$username'</code><br>";
    echo "Jumlah hasil: <strong>$num_rows</strong></div>";
    
    if ($num_rows === 1) {
        $admin = mysqli_fetch_assoc($result);
        
        echo "<div class='success'>✅ User ditemukan!</div>";
        
        echo "<h3>👤 Step 3: Data User</h3>";
        echo "<pre>";
        echo "ID           : " . $admin['id'] . "\n";
        echo "Username     : " . $admin['username'] . "\n";
        echo "Nama Lengkap : " . $admin['nama_lengkap'] . "\n";
        echo "Password Hash: " . substr($admin['password'], 0, 60) . "...";
        echo "</pre>";
        
        echo "<h3>🔑 Step 4: Verifikasi Password</h3>";
        
        // Test password
        $verify_result = password_verify($password, $admin['password']);
        
        if ($verify_result) {
            echo "<div class='success'>✅ <strong>Password COCOK!</strong></div>";
            
            // Set session
            $_SESSION['id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['nama_lengkap'] = $admin['nama_lengkap'];
            $_SESSION['logged_in'] = true;
            
            echo "<h3>💾 Step 5: Session Dibuat</h3>";
            echo "<pre>";
            print_r($_SESSION);
            echo "</pre>";
            
            echo "<div class='success'>";
            echo "<h3>🎉 LOGIN BERHASIL!</h3>";
            echo "<p><a href='dashboard.php' style='background:#28a745;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;'>🏠 Ke Dashboard</a></p>";
            echo "</div>";
            
        } else {
            echo "<div class='error'>❌ <strong>Password TIDAK COCOK!</strong></div>";
            echo "<div class='info'>";
            echo "<strong>Debugging:</strong><br>";
            echo "Password yang Anda masukkan: <code>$password</code><br>";
            echo "Password di database: <code>" . substr($admin['password'], 0, 60) . "...</code><br><br>";
            echo "<strong>Solusi:</strong><br>";
            echo "1. Pastikan password Anda: <strong>admin123</strong><br>";
            echo "2. Atau generate password baru dengan generate_password.php";
            echo "</div>";
        }
    } else {
        echo "<div class='error'>❌ Username '<strong>" . htmlspecialchars($username) . "</strong>' tidak ditemukan!</div>";
        echo "<div class='info'>";
        echo "<strong>Solusi:</strong><br>";
        echo "1. Pastikan username yang Anda masukkan: <strong>admin</strong><br>";
        echo "2. Cek database apakah data admin sudah ada<br>";
        echo "3. Jalankan test_connection.php untuk memastikan";
        echo "</div>";
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
} else {
    // Tampilkan form untuk test
    echo "<form method='POST' style='max-width:400px;'>";
    echo "<h3>Form Test Login</h3>";
    echo "<div style='margin:10px 0;'>";
    echo "<label>Username:</label><br>";
    echo "<input type='text' name='username' value='admin' style='width:100%;padding:8px;' required>";
    echo "</div>";
    echo "<div style='margin:10px 0;'>";
    echo "<label>Password:</label><br>";
    echo "<input type='password' name='password' value='admin123' style='width:100%;padding:8px;' required>";
    echo "</div>";
    echo "<button type='submit' style='background:#007bff;color:white;padding:10px 20px;border:none;border-radius:5px;cursor:pointer;'>Test Login</button>";
    echo "</form>";
    echo "<div class='info' style='margin-top:20px;'>";
    echo "<strong>Default Login:</strong><br>";
    echo "Username: admin<br>";
    echo "Password: admin123";
    echo "</div>";
}

echo "</body></html>";
?>