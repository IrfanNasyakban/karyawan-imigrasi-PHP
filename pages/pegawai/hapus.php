<?php
require_once '../../config/database.php';

$id = $_GET['id'];

$query = "DELETE FROM karyawan WHERE id = $id";

if (mysqli_query($conn, $query)) {
    header('Location: index.php?message=hapus');
} else {
    echo "Error: " . mysqli_error($conn);
}
?>