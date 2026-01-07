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

<style>
    /* Page Specific Styles */
    .page-header {
        background: linear-gradient(135deg, #0891b2 0%, #40cdf8ff 100%);
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(8, 145, 178, 0.2);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
    }

    .page-header-content {
        position: relative;
        z-index: 1;
    }

    .page-header h2 {
        color: white;
        font-weight: 700;
        font-size: 32px;
        margin-bottom: 10px;
    }

    .page-header p {
        color: rgba(255, 255, 255, 0.95);
        font-size: 16px;
        margin-bottom: 0;
    }

    .page-header-icon {
        font-size: 80px;
        color: rgba(255, 255, 255, 0.2);
        position: absolute;
        right: 40px;
        top: 50%;
        transform: translateY(-50%);
    }

    /* Action Bar */
    .btn-add {
        background: linear-gradient(135deg, #0891b2 0%, #40cdf8ff 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(8, 145, 178, 0.3);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .action-bar {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 1rem;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(8, 145, 178, 0.4);
        color: white;
    }

    .btn-add i {
        font-size: 16px;
    }

    /* Alert Styling */
    .alert-success {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        border: none;
        border-radius: 15px;
        color: white;
        padding: 18px 25px;
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
        margin-bottom: 25px;
    }

    .alert-success i {
        font-size: 20px;
        margin-right: 10px;
    }

    .alert-success .btn-close {
        filter: brightness(0) invert(1);
    }

    /* Card Styling */
    .data-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .card-header-custom {
        background: linear-gradient(135deg, #0891b2 0%, #40cdf8ff 100%);
        padding: 25px 30px;
        border: none;
    }

    .card-header-custom h5 {
        color: white;
        font-weight: 700;
        margin: 0;
        font-size: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header-custom i {
        font-size: 24px;
    }

    .card-body-custom {
        padding: 30px;
    }

    /* Table Styling */
    .table-responsive {
        border-radius: 9px;
        overflow: hidden;
    }

    #table-kepegawaian {
        margin-bottom: 0 !important;
    }

    #table-kepegawaian thead th {
        background: #40cdf8ff;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        border: none;
        padding: 18px 15px;
        white-space: nowrap;
    }

    #table-kepegawaian tbody td {
        padding: 16px 15px;
        vertical-align: middle;
        color: #374151;
        font-size: 13px;
        border-bottom: 1px solid #f3f4f6;
    }

    #table-kepegawaian tbody tr {
        transition: all 0.3s ease;
    }

    /* Badge Styling */
    .badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 10px;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .badge.bg-success {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%) !important;
        box-shadow: 0 3px 8px rgba(16, 185, 129, 0.3);
    }

    .badge.bg-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%) !important;
        box-shadow: 0 3px 8px rgba(59, 130, 246, 0.3);
    }

    .badge.bg-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%) !important;
        color: white;
        box-shadow: 0 3px 8px rgba(245, 158, 11, 0.3);
    }

    .badge.bg-secondary {
        background: linear-gradient(135deg, #6b7280 0%, #9ca3af 100%) !important;
        box-shadow: 0 3px 8px rgba(107, 114, 128, 0.3);
    }

    /* Action Buttons */
    .btn-sm {
        padding: 8px 15px;
        border-radius: 3px;
        font-weight: 300;
        font-size: 10px;
        transition: all 0.3s ease;
        border: none;
        margin: 0 3px;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .btn-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        color: white;
        box-shadow: 0 3px 8px rgba(245, 158, 11, 0.3);
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(245, 158, 11, 0.4);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
        color: white;
        box-shadow: 0 3px 8px rgba(239, 68, 68, 0.3);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(239, 68, 68, 0.4);
        color: white;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 8px 12px;
        transition: all 0.3s ease;
    }

    .dataTables_wrapper .dataTables_length select:focus,
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #0891b2;
        outline: none;
        box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.1);
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: linear-gradient(135deg, #0891b2 0%, #40cdf8ff 100%) !important;
        border: none !important;
        color: white !important;
        border-radius: 8px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e5e7eb !important;
        border: none !important;
        color: #0891b2 !important;
        border-radius: 8px;
    }

    .dataTables_wrapper .dataTables_info {
        color: #6b7280;
        font-weight: 500;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        color: #d1d5db;
    }

    .empty-state h5 {
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 30px 25px;
        }

        .page-header h2 {
            font-size: 24px;
        }

        .page-header-icon {
            font-size: 60px;
            right: 25px;
        }

        .action-bar {
            padding: 15px 20px;
        }

        .card-body-custom {
            padding: 20px 15px;
        }

        .table-responsive {
            font-size: 10px;
        }

        #table-kepegawaian thead th {
            padding: 12px 10px;
            font-size: 12px;
        }

        #table-kepegawaian tbody td {
            padding: 12px 10px;
        }
    }
</style>

<div class="container-fluid px-4 py-4">
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
                            <td><strong><?php echo $no++; ?></strong></td>
                            <td><strong><?php echo htmlspecialchars($row['namaDenganGelar']); ?></strong></td>
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