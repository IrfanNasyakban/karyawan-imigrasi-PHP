<?php
require_once '../../config/database.php';

$page_title = 'Data Pegawai';

// Ambil data pegawai
$query = "SELECT * FROM pegawai";
$result = mysqli_query($conn, $query);

include '../../includes/header.php';
?>

<div class="container my-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold">
                <i class="fas fa-users me-2"></i>Data Pegawai
            </h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="tambah-pegawai.php" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Pegawai
            </a>
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

    <!-- Table -->
    <div class="card shadow-sm border-0">
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
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>