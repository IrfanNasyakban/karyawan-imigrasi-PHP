<?php
require_once '../../config/database.php';

$page_title = 'Detail Karyawan';

$id = $_GET['id'];

// Ambil data karyawan
$query = "SELECT * FROM karyawan WHERE id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header('Location: index.php');
    exit();
}

include '../../includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user me-2"></i>Detail Data Karyawan
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">NIP</th>
                            <td>: <?php echo $data['nip']; ?></td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td>: <?php echo $data['nama']; ?></td>
                        </tr>
                        <tr>
                            <th>Jabatan</th>
                            <td>: <?php echo $data['jabatan']; ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>: <?php echo $data['email']; ?></td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>: <?php echo $data['telepon']; ?></td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>: <?php echo $data['alamat'] ? $data['alamat'] : '-'; ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Input</th>
                            <td>: <?php echo date('d-m-Y H:i', strtotime($data['created_at'])); ?></td>
                        </tr>
                    </table>

                    <div class="d-flex gap-2 mt-4">
                        <a href="edit.php?id=<?php echo $data['id']; ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>