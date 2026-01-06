<?php
require_once '../../config/database.php';

$page_title = 'Data Kepegawaian';

// Ambil data kepegawaian
$query = "SELECT * FROM kepegawaian";
$result = mysqli_query($conn, $query);

include '../../includes/header.php';
?>

<div class="container my-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold">
                <i class="fas fa-users me-2"></i>Data Kepegawaian
            </h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="tambah-kepegawaian.php" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Kepegawaian
            </a>
        </div>
    </div>

    <!-- Alert -->
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php 
                if ($_GET['message'] == 'tambah') echo 'Data kepegawaian berhasil ditambahkan!';
                if ($_GET['message'] == 'edit') echo 'Data kepegawaian berhasil diubah!';
                if ($_GET['message'] == 'hapus') echo 'Data kepegawaian berhasil dihapus!';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-pangkat" class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Pegawai</th>
                            <th>Pangkat</th>
                            <th>Golongan Ruang</th>
                            <th>TMT Pangkat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)): 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            
                            <td><?php echo $row['pangkat']; ?></td>
                            <td><?php echo $row['golonganRuang']; ?></td>
                            <td><?php echo $row['tmtPangkat']; ?></td>
                            <td>
                                <a href="detail.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>