<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Validasi input kosong
    if (empty($username) || empty($password)) {
        header("Location: ../index.php?error=empty");
        exit();
    }
    
    // Query cek username
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        
        // Verifikasi password
        if (password_verify($password, $admin['password'])) {
            // Set session
            $_SESSION['id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['nama_lengkap'] = $admin['nama_lengkap'];
            $_SESSION['logged_in'] = true;
            
            // Redirect ke dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            header("Location: ../index.php?error=wrong");
            exit();
        }
    } else {
        header("Location: ../index.php?error=wrong");
        exit();
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../index.php");
    exit();
}
?>