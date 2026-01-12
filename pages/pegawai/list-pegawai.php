<?php
require_once '../../config/database.php';

$page_title = 'Data Pegawai';

// Ambil keyword search
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Modifikasi query untuk search
if ($search != '') {
    $query = "SELECT * FROM pegawai WHERE nama LIKE '%$search%' OR nip LIKE '%$search%' OR namaDenganGelar LIKE '%$search%'";
} else {
    $query = "SELECT * FROM pegawai";
}

$result = mysqli_query($conn, $query);

include '../../includes/sidebar.php';

include '../../assets/css/style-dashboard.css'
?>

<link rel="stylesheet" href="../../assets/css/style-tables.css">

<div class="container-fluid px-2 py-2">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h2><i class="fas fa-user me-2"></i>Data Pegawai</h2>
            <p>Sistem Informasi Pegawai - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-users page-header-icon d-none d-md-block"></i>
    </div>

    <div class="action-bar">
        <div class="action-bar-left">
            <a href="tambah-pegawai.php" class="btn-add">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
        </div>
        <div class="action-bar-right">
            <!-- <form method="GET" action="" class="search-form">
                <div class="search-box">
                    <input type="text" name="search" class="search-input" placeholder="Cari nama pegawai..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                    <?php if ($search != ''): ?>
                        <a href="?" class="search-reset" title="Reset pencarian">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form> -->
        </div>
    </div>

    <!-- Alert -->
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php 
                if ($_GET['message'] == 'tambah') echo 'Data pegawai berhasil ditambahkan!';
                if ($_GET['message'] == 'edit') echo 'Data pegawai berhasil diubah!';
                if ($_GET['message'] == 'hapus') echo 'Data pegawai berhasil dihapus!';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Search Result Info -->
    <?php if ($search != ''): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            Menampilkan hasil pencarian untuk: <strong>"<?php echo htmlspecialchars($search); ?>"</strong>
            <?php 
                $total_results = mysqli_num_rows($result);
                echo " - Ditemukan $total_results data";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Table -->
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-karyawan" class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Gelar Depan</th>
                        <th>Gelar Belakang</th>
                        <th>Nama dengan Gelar</th>
                        <th>Tempat Lahir</th>
                        <th>Tanggal Lahir</th>
                        <th>Gender</th>
                        <th>Agama</th>
                        <th>Status Pegawai</th>
                        <th>Email Pribadi</th>
                        <th>Email Dinas</th>
                        <th>No Telp</th>
                        <th>Hobi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($result) > 0):
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)): 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['nip']; ?></td>
                        <td><?php echo $row['nama']; ?></td>
                        <td><?php echo $row['gelarDepan']; ?></td>
                        <td><?php echo $row['gelarBelakang']; ?></td>
                        <td><?php echo $row['namaDenganGelar']; ?></td>
                        <td><?php echo $row['tempatLahir']; ?></td>
                        <td><?php echo $row['tanggalLahir']; ?></td>
                        <td><?php echo $row['gender']; ?></td>
                        <td><?php echo $row['agama']; ?></td>
                        <td><?php echo $row['statusPegawai']; ?></td>
                        <td><?php echo $row['emailPribadi']; ?></td>
                        <td><?php echo $row['emailDinas']; ?></td>
                        <td><?php echo $row['noHp']; ?></td>
                        <td><?php echo $row['hobi']; ?></td>
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
                    <?php 
                        endwhile;
                    else: 
                    ?>
                    <tr>
                        <td colspan="16" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-search"></i>
                                <h5>Data Tidak Ditemukan</h5>
                                <p>Tidak ada data pegawai yang sesuai dengan pencarian Anda.</p>
                                <?php if ($search != ''): ?>
                                    <a href="?" class="btn-add">
                                        <i class="fas fa-redo"></i> Reset Pencarian
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>