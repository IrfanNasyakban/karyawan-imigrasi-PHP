<?php
require_once '../../config/database.php';

$page_title = 'Data Ukuran Dinas';

// Ambil data Ukuran Dinas
$query = "SELECT 
            u.idUkuran,
            u.idPegawai,
            p.namaDenganGelar,
            u.ukuranPadDivamot,
            u.ukuranSepatu,
            u.ukuranTopi
          FROM ukuran u
          LEFT JOIN pegawai p ON u.idPegawai = p.idPegawai
          ORDER BY u.idUkuran DESC";

$result = mysqli_query($conn, $query);

include '../../includes/sidebar.php';
?>

<link rel="stylesheet" href="../../assets/css/style-tables.css">

<div class="container-fluid px-2 py-2">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h2><i class="fas fa-building me-2"></i>Data Ukuran Dinas</h2>
            <p>Sistem Informasi Ukuran Dinas - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-users page-header-icon d-none d-md-block"></i>
    </div>

    <div class="action-bar">
        <a href="tambah/tambah-ukuran-dinas-2.php" class="btn-add">
            <i class="fas fa-plus"></i> Tambah Data
        </a>
    </div>

    <!-- Alert -->
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php 
                if ($_GET['message'] == 'tambah') echo 'Data ukuran dinas berhasil ditambahkan!';
                if ($_GET['message'] == 'edit') echo 'Data ukuran dinas berhasil diubah!';
                if ($_GET['message'] == 'hapus') echo 'Data ukuran dinas berhasil dihapus!';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Table -->
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-ukuran-dinas" class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Pegawai</th>
                            <th>Ukuran Pad Divamot</th>
                            <th>Ukuran Sepatu</th>
                            <th>Ukuran Topi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (mysqli_num_rows($result) > 0) {
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)): 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['namaDenganGelar']; ?></td>
                            <td><?php echo $row['ukuranPadDivamot']; ?></td>
                            <td><?php echo $row['ukuranSepatu']; ?></td>
                            <td><?php echo $row['ukuranTopi']; ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile;
                        } else {
                        ?>
                        <tr>
                            <td colspan="11">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h5>Tidak Ada Data</h5>
                                    <p>Belum ada data Ukuran Dinas yang tersedia</p>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
</div>

<?php include '../../includes/footer.php'; ?>