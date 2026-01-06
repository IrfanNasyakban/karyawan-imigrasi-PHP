<?php
require_once '../../config/database.php';

$page_title = 'Tambah Data Kepegawaian';

$idPegawai = $_GET['idPegawai'] ?? null;
if (!$idPegawai) {
    die("ID Pegawai tidak ditemukan. Silakan tambah pegawai terlebih dahulu.");
}

if (isset($_POST['submit'])) {
    $idPegawai = mysqli_real_escape_string($conn, $_POST['idPegawai'] ?? '');
    $statusKepegawaian = mysqli_real_escape_string($conn, $_POST['statusKepegawaian'] ?? '');
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan'] ?? '');
    $tmtJabatan = mysqli_real_escape_string($conn, $_POST['tmtJabatan'] ?? '');
    $bagianKerja = mysqli_real_escape_string($conn, $_POST['bagianKerja'] ?? '');
    $eselon = mysqli_real_escape_string($conn, $_POST['eselon'] ?? '');
    $angkatanPejim = mysqli_real_escape_string($conn, $_POST['angkatanPejim'] ?? '');
    $ppns = mysqli_real_escape_string($conn, $_POST['ppns'] ?? '');
    $tmtPensiun = mysqli_real_escape_string($conn, $_POST['tmtPensiun'] ?? '');

    if ($idPegawai === '') {
        $error = "ID Pegawai kosong. Pastikan alur tambah pegawai benar.";
    } else {
        $query = "INSERT INTO kepegawaian 
            (idPegawai, statusKepegawaian, jabatan, tmtJabatan, bagianKerja, eselon, angkatanPejim, ppns, tmtPensiun)
            VALUES
            ('$idPegawai', '$statusKepegawaian', '$jabatan', '$tmtJabatan', '$bagianKerja', '$eselon', '$angkatanPejim', '$ppns', '$tmtPensiun')";

        if (mysqli_query($conn, $query)) {
            header("Location: tambah-pangkat.php?idPegawai=$idPegawai");
            exit();
        } else {
            $error = "Gagal menambahkan data: " . mysqli_error($conn);
        }
    }
}


include '../../includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>Tambah Data Kepegawaian
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <input type="hidden" name="idPegawai" value="<?php echo htmlspecialchars($idPegawai); ?>">

                        <div class="mb-3">
                            <label class="form-label">Status Kepegawaian <span class="text-danger">*</span></label>
                            <select name="statusKepegawaian" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Status Kepegawaian --</option>
                                <option value="PNS">PNS</option>
                                <option value="CPNS">CPNS</option>
                                <option value="PPPK">PPPK</option>
                                <option value="OUTSOURCING">OUTSOURCING</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" name="jabatan" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Terhitung Mulai Tanggal Jabatan <span class="text-danger">*</span></label>
                            <input type="date" name="tmtJabatan" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bagian Kerja <span class="text-danger">*</span></label>
                            <input type="text" name="bagianKerja" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Eselon <span class="text-danger">*</span></label>
                            <input type="text" name="eselon" class="form-control" required></input>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Angkatan Pejim <span class="text-danger">*</span></label>
                            <input type="text" name="angkatanPejim" class="form-control" required></input>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">PPNS <span class="text-danger">*</span></label>
                            <input type="text" name="ppns" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Terhitung Mulai Tanggal Pensiun <span class="text-danger">*</span></label>
                            <input type="date" name="tmtPensiun" class="form-control" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Selanjutnya
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>