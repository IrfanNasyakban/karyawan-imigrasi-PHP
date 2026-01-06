<?php
require_once '../../config/database.php';

$page_title = 'Tambah Data Pangkat';

$idPegawai = $_GET['idPegawai'] ?? null;
if (!$idPegawai) {
    die("ID Pegawai tidak ditemukan. Silakan tambah pegawai terlebih dahulu.");
}

if (isset($_POST['submit'])) {
    $idPegawai = mysqli_real_escape_string($conn, $_POST['idPegawai'] ?? '');
    $pangkat = mysqli_real_escape_string($conn, $_POST['pangkat'] ?? '');
    $golonganRuang = mysqli_real_escape_string($conn, $_POST['golonganRuang'] ?? '');
    $tmtPangkat = mysqli_real_escape_string($conn, $_POST['tmtPangkat'] ?? '');

    if ($idPegawai === '') {
        $error = "ID Pegawai kosong. Pastikan alur tambah pegawai benar.";
    } else {
        $query = "INSERT INTO kepegawaian 
            (idPegawai, pangkat, golonganRuang, tmtPangkat)
            VALUES
            ('$idPegawai', '$pangkat', '$golonganRuang', '$tmtPangkat')";

        if (mysqli_query($conn, $query)) {
            header("Location: tambah-alamat.php?idPegawai=$idPegawai");
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
                        <i class="fas fa-user-plus me-2"></i>Tambah Data Pangkat
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
                            <label class="form-label">Pangkat <span class="text-danger">*</span></label>
                            <input type="text" name="pangkat" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Golongan Ruang <span class="text-danger">*</span></label>
                            <input type="text" name="tmtJabatan" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Terhitung Mulai Tanggal Pangkat <span class="text-danger">*</span></label>
                            <input type="date" name="tmtPangkat" class="form-control" required>
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