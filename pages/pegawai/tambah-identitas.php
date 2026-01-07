<?php
require_once '../../config/database.php';

$page_title = 'Tambah Data Identitas';

$idPegawai = $_GET['idPegawai'] ?? null;
if (!$idPegawai) {
    die("ID Pegawai tidak ditemukan. Silakan tambah pegawai terlebih dahulu.");
}

if (isset($_POST['submit'])) {
    $idPegawai = mysqli_real_escape_string($conn, $_POST['idPegawai'] ?? '');
    $nik = mysqli_real_escape_string($conn, $_POST['nik'] ?? '');
    $nomorKK = mysqli_real_escape_string($conn, $_POST['nomorKK'] ?? '');
    $nomorBPJS = mysqli_real_escape_string($conn, $_POST['nomorBPJS'] ?? '');
    $nomorTaspen = mysqli_real_escape_string($conn, $_POST['nomorTaspen'] ?? '');

    if ($idPegawai === '') {
        $error = "ID Pegawai kosong. Pastikan alur tambah pegawai benar.";
    } else {
        $query = "INSERT INTO identitas
            (idPegawai, nik, nomorKK, nomorBPJS, nomorTaspen)
            VALUES
            ('$idPegawai', '$nik', '$nomorKK', '$nomorBPJS', '$nomorTaspen')";

        if (mysqli_query($conn, $query)) {
            header("Location: tambah-rekening.php?idPegawai=$idPegawai");
            exit();
        } else {
            $error = "Gagal menambahkan data: " . mysqli_error($conn);
        }
    }
}

$query = "SELECT namaDenganGelar 
          FROM pegawai 
          WHERE idPegawai = '" . mysqli_real_escape_string($conn, $idPegawai) . "'";

$namaDenganGelar = mysqli_query($conn, $query);

if ($namaDenganGelar && mysqli_num_rows($namaDenganGelar) > 0) {
    $pegawai = mysqli_fetch_assoc($namaDenganGelar);
    $namaDenganGelar = $pegawai['namaDenganGelar'];
} else {
    die("Data pegawai dengan ID $idPegawai tidak ditemukan.");
}

include '../../includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>Tambah Data Identitas
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <strong>Nama Pegawai:</strong> <?php echo htmlspecialchars($namaDenganGelar); ?>
                    </div>

                    <form method="POST" action="">
                        <input type="hidden" name="idPegawai" value="<?php echo htmlspecialchars($idPegawai); ?>">

                        <div class="mb-3">
                            <label class="form-label">NIK <span class="text-danger">*</span></label>
                            <input type="number" name="nik" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor KK <span class="text-danger">*</span></label>
                            <input type="number" name="nomorKK" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor BPJS <span class="text-danger">*</span></label>
                            <input type="number" name="nomorBPJS" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Taspen <span class="text-danger">*</span></label>
                            <input type="number" name="nomorTaspen" class="form-control" required>
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