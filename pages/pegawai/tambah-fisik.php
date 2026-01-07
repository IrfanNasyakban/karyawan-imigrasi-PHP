<?php
require_once '../../config/database.php';

$page_title = 'Tambah Data Fisik';

$idPegawai = $_GET['idPegawai'] ?? null;
if (!$idPegawai) {
    die("ID Pegawai tidak ditemukan. Silakan tambah pegawai terlebih dahulu.");
}

if (isset($_POST['submit'])) {
    $idPegawai = mysqli_real_escape_string($conn, $_POST['idPegawai'] ?? '');
    $tinggiBadan = mysqli_real_escape_string($conn, $_POST['tinggiBadan'] ?? '');
    $beratBadan = mysqli_real_escape_string($conn, $_POST['beratBadan'] ?? '');
    $jenisRambut = mysqli_real_escape_string($conn, $_POST['jenisRambut'] ?? '');
    $warnaRambut = mysqli_real_escape_string($conn, $_POST['warnaRambut'] ?? '');
    $bentukWajah = mysqli_real_escape_string($conn, $_POST['bentukWajah'] ?? '');
    $warnaKulit = mysqli_real_escape_string($conn, $_POST['warnaKulit'] ?? '');

    if ($idPegawai === '') {
        $error = "ID Pegawai kosong. Pastikan alur tambah pegawai benar.";
    } else {
        $query = "INSERT INTO fisik
            (idPegawai, tinggiBadan, beratBadan, jenisRambut, warnaRambut, bentukWajah, warnaKulit)
            VALUES
            ('$idPegawai', '$tinggiBadan', '$beratBadan', '$jenisRambut', '$warnaRambut', '$bentukWajah', '$warnaKulit')";

        if (mysqli_query($conn, $query)) {
            header("Location: tambah-ukuran-dinas.php?idPegawai=$idPegawai");
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
                        <i class="fas fa-user-plus me-2"></i>Tambah Data Fisik
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
                            <label class="form-label">Tinggi Badan (CM) <span class="text-danger">*</span></label>
                            <input type="text" name="tinggiBadan" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Berat Badan (KG) <span class="text-danger">*</span></label>
                            <input type="text" name="beratBadan" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Rambut <span class="text-danger">*</span></label>
                            <input type="text" name="jenisRambut" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Warna Rambut <span class="text-danger">*</span></label>
                            <input type="text" name="warnaRambut" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bentuk Wajah <span class="text-danger">*</span></label>
                            <input type="text" name="bentukWajah" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Warna Kulit <span class="text-danger">*</span></label>
                            <input type="text" name="warnaKulit" class="form-control" required>
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