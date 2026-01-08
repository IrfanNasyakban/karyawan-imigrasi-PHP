<?php
require_once '../../config/database.php';

$page_title = 'Data Kepegawaian';

// Ambil data kepegawaian dengan JOIN ke tabel pegawai dan pangkat
$query = "SELECT 
            k.idKepegawaian,
            k.idPegawai,
            p.namaDenganGelar,
            k.jabatan,
            k.tmtJabatan,
            k.bagianKerja,
            k.eselon,
            k.angkatanPejim,
            k.ppns,
            k.tmtPensiun,
            k.statusKepegawaian
          FROM kepegawaian k
          LEFT JOIN pegawai p ON k.idPegawai = p.idPegawai
          ORDER BY k.idKepegawaian DESC";

$result = mysqli_query($conn, $query);

// Cek jika query gagal
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}

include '../../includes/sidebar.php';
?>

<link rel="stylesheet" href="../../assets/css/style-tables.css">

<div class="container-fluid px-2 py-2">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h2><i class="fas fa-id-card me-2"></i>Data Kepegawaian</h2>
            <p>Sistem Informasi Kepegawaian - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-users page-header-icon d-none d-md-block"></i>
    </div>

    <div class="action-bar">
        <a href="tambah-kepegawaian.php" class="btn-add">
            <i class="fas fa-plus"></i> Tambah Data
        </a>
    </div>

    <!-- Alert -->
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            <?php 
                if ($_GET['message'] == 'tambah') echo 'Data kepegawaian berhasil ditambahkan!';
                if ($_GET['message'] == 'edit') echo 'Data kepegawaian berhasil diubah!';
                if ($_GET['message'] == 'hapus') echo 'Data kepegawaian berhasil dihapus!';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Data Card -->
    <div class="data-card">
            <div class="table-responsive">
                <table id="table-kepegawaian" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pegawai</th>
                            <th>Status</th>
                            <th>Jabatan</th>
                            <th>TMT Jabatan</th>
                            <th>Bagian Kerja</th>
                            <th>Eselon</th>
                            <th>Angkatan Pejim</th>
                            <th>PPNS</th>
                            <th>TMT Pensiun</th>
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
                            <td><?php echo htmlspecialchars($row['namaDenganGelar']); ?></td>
                            <td>
                                <?php 
                                $status = isset($row['statusKepegawaian']) ? $row['statusKepegawaian'] : '';
                                $badge_class = '';
                                switch($status) {
                                    case 'PNS':
                                        $badge_class = 'bg-success';
                                        break;
                                    case 'PPPK':
                                        $badge_class = 'bg-primary';
                                        break;
                                    case 'Honorer':
                                        $badge_class = 'bg-warning';
                                        break;
                                    default:
                                        $badge_class = 'bg-secondary';
                                }
                                ?>
                                <span class="badge <?php echo $badge_class; ?>">
                                    <?php echo $status ? htmlspecialchars($status) : '-'; ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($row['jabatan']); ?></td>
                            <td><?php echo htmlspecialchars($row['tmtJabatan']); ?></td>
                            <td><?php echo htmlspecialchars($row['bagianKerja']); ?></td>
                            <td><?php echo htmlspecialchars($row['eselon']); ?></td>
                            <td><?php echo htmlspecialchars($row['angkatanPejim']); ?></td>
                            <td><?php echo htmlspecialchars($row['ppns']); ?></td>
                            <td><?php echo htmlspecialchars($row['tmtPensiun']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit.php?id=<?php echo $row['idKepegawaian']; ?>" 
                                       class="btn btn-warning btn-sm" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="hapus.php?id=<?php echo $row['idKepegawaian']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       title="Hapus" 
                                       onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            endwhile;
                        } else {
                        ?>
                        <tr>
                            <td colspan="11">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h5>Tidak Ada Data</h5>
                                    <p>Belum ada data kepegawaian yang tersedia</p>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
    </div>
</div>

<!-- DataTables Initialization -->
<script>
$(document).ready(function() {
    $('#table-kepegawaian').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "pageLength": 10,
        "ordering": true,
        "searching": true,
        "responsive": true,
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
    });
});
</script>

<?php include '../../includes/footer.php'; ?>