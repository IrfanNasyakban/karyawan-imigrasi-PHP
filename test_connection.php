<?php
echo "<!DOCTYPE html>
<html>
<head>
    <title>Test Koneksi Database</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .success { color: green; background: #d4edda; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .error { color: red; background: #f8d7da; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .info { color: #004085; background: #d1ecf1; padding: 10px; margin: 10px 0; border-radius: 5px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #007bff; color: white; }
    </style>
</head>
<body>";

echo "<h2>🔍 Test Koneksi Database</h2>";

// Konfigurasi (sesuaikan dengan database.php Anda)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_karyawan_imigrasi');

// Test Koneksi
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    echo "<div class='error'>❌ <strong>Koneksi GAGAL:</strong> " . mysqli_connect_error() . "</div>";
    echo "<div class='info'><strong>Pastikan:</strong><br>";
    echo "1. MySQL/XAMPP sudah running<br>";
    echo "2. Database 'db_karyawan_imigrasi' sudah dibuat<br>";
    echo "3. Username dan password benar</div>";
    die();
}

echo "<div class='success'>✅ <strong>Koneksi database BERHASIL!</strong></div>";
mysqli_set_charset($conn, "utf8");

// Cek Tabel Admin
echo "<h3>📊 Mengecek Tabel Admin...</h3>";
$check_table = mysqli_query($conn, "SHOW TABLES LIKE 'admin'");

if (mysqli_num_rows($check_table) > 0) {
    echo "<div class='success'>✅ Tabel 'admin' ditemukan</div>";
    
    // Cek Data Admin
    $result = mysqli_query($conn, "SELECT * FROM admin");
    $count = mysqli_num_rows($result);
    
    if ($count > 0) {
        echo "<div class='success'>✅ Data admin ditemukan: <strong>$count baris</strong></div>";
        
        echo "<h3>👥 Data Admin:</h3>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Username</th><th>Nama Lengkap</th><th>Password (Hash)</th></tr>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td><strong>" . $row['username'] . "</strong></td>";
            echo "<td>" . $row['nama_lengkap'] . "</td>";
            echo "<td><code>" . substr($row['password'], 0, 40) . "...</code></td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<div class='info'>";
        echo "<strong>📝 Informasi Login:</strong><br>";
        echo "Username: <strong>admin</strong><br>";
        echo "Password: <strong>admin123</strong><br>";
        echo "</div>";
        
    } else {
        echo "<div class='error'>❌ Tabel admin KOSONG, tidak ada data!</div>";
        echo "<div class='info'>";
        echo "<strong>Solusi:</strong> Jalankan query INSERT di phpMyAdmin:<br>";
        echo "<code>INSERT INTO admin (username, password, nama_lengkap) VALUES ('admin', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator');</code>";
        echo "</div>";
    }
} else {
    echo "<div class='error'>❌ Tabel 'admin' TIDAK DITEMUKAN!</div>";
    echo "<div class='info'>";
    echo "<strong>Solusi:</strong> Jalankan query CREATE TABLE di phpMyAdmin (lihat di atas)";
    echo "</div>";
}

mysqli_close($conn);

echo "<hr>";
echo "<h3>🔗 Langkah Selanjutnya:</h3>";
echo "<ol>";
echo "<li>Pastikan semua tanda ✅ di atas</li>";
echo "<li>Coba login dengan username: <strong>admin</strong> dan password: <strong>admin123</strong></li>";
echo "<li>Jika masih error, jalankan <strong>test_login.php</strong></li>";
echo "</ol>";

echo "</body></html>";
?>