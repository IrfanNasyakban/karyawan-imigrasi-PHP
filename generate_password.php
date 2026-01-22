<?php
$password = "admin123"; // ganti dengan password yang diinginkan
$hashed = password_hash($password, PASSWORD_BCRYPT);

echo "Password: " . $password . "<br>";
echo "Hashed: " . $hashed . "<br>";
echo "<br>Copy hashed password untuk update database";
?>