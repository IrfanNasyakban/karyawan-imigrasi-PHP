<?php
require_once '../../config/database.php';

$page_title = 'Data Fisik';

// Ambil data Fisik
$query = "SELECT 
            f.idFisik,
            f.idPegawai,
            p.namaDenganGelar,
            f.tinggiBadan,
            f.beratBadan,
            f.jenisRambut,
            f.warnaRambut,
            f.bentukWajah,
            f.warnaKulit
          FROM fisik f
          LEFT JOIN pegawai p ON f.idPegawai = p.idPegawai
          ORDER BY f.idFisik DESC";

$result = mysqli_query($conn, $query);

include '../../includes/sidebar.php';
?>

<link rel="stylesheet" href="../../assets/css/style-tables.css">

<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h2><i class="fas fa-heartbeat me-2"></i>Data Fisik</h2>
            <p>Sistem Informasi Fisik - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-users page-header-icon d-none d-md-block"></i>
    </div>

    <div class="action-bar">
        <a href="tambah-fisik.php" class="btn-add">
            <i class="fas fa-plus"></i> Tambah Data
        </a>
    </div>

    <!-- Alert -->
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php 
                if ($_GET['message'] == 'tambah') echo 'Data fisik berhasil ditambahkan!';
                if ($_GET['message'] == 'edit') echo 'Data fisik berhasil diubah!';
                if ($_GET['message'] == 'hapus') echo 'Data fisik berhasil dihapus!';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Table -->
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-fisik" class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Pegawai</th>
                            <th>Tinggi Badan</th>
                            <th>Berat Badan</th>
                            <th>Jenis Rambut</th>
                            <th>Warna Rambut</th>
                            <th>Bentuk Wajah</th>
                            <th>Warna Kulit</th>
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
                            <td><?php echo $row['tinggiBadan']; ?> CM </td>
                            <td><?php echo $row['beratBadan']; ?> KG </td>
                            <td><?php echo $row['jenisRambut']; ?></td>
                            <td><?php echo $row['warnaRambut']; ?></td>
                            <td><?php echo $row['bentukWajah']; ?></td>
                            <td><?php echo $row['warnaKulit']; ?></td>
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
                                    <p>Belum ada data Fisik yang tersedia</p>
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