<?php
require_once '../../config/database.php';

$page_title = 'Data Identitas Pegawai';

// Ambil data Identitas
$query = "SELECT 
            i.idIdentitas,
            i.idPegawai,
            p.namaDenganGelar,
            i.nik,
            i.nomorKK,
            i.nomorBPJS,
            i.nomorTaspen
          FROM identitas i
          LEFT JOIN pegawai p ON i.idPegawai = p.idPegawai
          ORDER BY i.idIdentitas DESC";

$result = mysqli_query($conn, $query);

include '../../includes/sidebar.php';
?>

<link rel="stylesheet" href="../../assets/css/style-tables.css">

<div class="container-fluid px-2 py-2">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h2><i class="fas fa-address-card me-2"></i>Data Identitas</h2>
            <p>Sistem Informasi Identitas - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-users page-header-icon d-none d-md-block"></i>
    </div>

    <div class="action-bar">
        <a href="tambah-identitas.php" class="btn-add">
            <i class="fas fa-plus"></i> Tambah Data
        </a>
    </div>

    <!-- Alert -->
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php 
                if ($_GET['message'] == 'tambah') echo 'Data identitas berhasil ditambahkan!';
                if ($_GET['message'] == 'edit') echo 'Data identitas berhasil diubah!';
                if ($_GET['message'] == 'hapus') echo 'Data identitas berhasil dihapus!';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Table -->
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-identitas" class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Pegawai</th>
                            <th>NIK</th>
                            <th>Nomor KK</th>
                            <th>Nomor BPJS</th>
                            <th>Nomor Taspen</th>
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
                            <td><?php echo $row['nik']; ?></td>
                            <td><?php echo $row['nomorKK']; ?></td>
                            <td><?php echo $row['nomorBPJS']; ?></td>
                            <td><?php echo $row['nomorTaspen']; ?></td>
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
                                    <p>Belum ada data identitas yang tersedia</p>
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