<?php
require_once '../../config/database.php';

$page_title = 'Tambah Data Keluarga';

$idPegawai = $_GET['idPegawai'] ?? null;
if (!$idPegawai) {
    die("ID Pegawai tidak ditemukan. Silakan tambah pegawai terlebih dahulu.");
}

if (isset($_POST['submit'])) {
    $idPegawai = mysqli_real_escape_string($conn, $_POST['idPegawai'] ?? '');
    $namaPasangan = mysqli_real_escape_string($conn, $_POST['namaPasangan'] ?? '');
    $jumlahAnak = intval($_POST['jumlahAnak'] ?? 0);

    if ($idPegawai === '') {
        $error = "ID Pegawai kosong. Pastikan alur tambah pegawai benar.";
    } else {
        // Mulai transaksi
        mysqli_begin_transaction($conn);
        
        try {
            // Insert data pasangan
            $queryPasangan = "INSERT INTO pasangan (idPegawai, namaPasangan) 
                             VALUES ('$idPegawai', '$namaPasangan')";
            
            if (!mysqli_query($conn, $queryPasangan)) {
                throw new Exception("Gagal menambahkan data pasangan: " . mysqli_error($conn));
            }
            
            // Insert data anak jika ada
            if ($jumlahAnak > 0) {
                for ($i = 1; $i <= $jumlahAnak; $i++) {
                    $namaAnak = mysqli_real_escape_string($conn, $_POST["namaAnak$i"] ?? '');
                    
                    if (!empty($namaAnak)) {
                        $queryAnak = "INSERT INTO anak (idPegawai, namaAnak) 
                                     VALUES ('$idPegawai', '$namaAnak')";
                        
                        if (!mysqli_query($conn, $queryAnak)) {
                            throw new Exception("Gagal menambahkan data anak: " . mysqli_error($conn));
                        }
                    }
                }
            }
            
            // Commit transaksi
            mysqli_commit($conn);
            
            // Redirect ke halaman selanjutnya
            header("Location: list-pegawai.php");
            exit();
            
        } catch (Exception $e) {
            // Rollback jika terjadi error
            mysqli_rollback($conn);
            $error = $e->getMessage();
        }
    }
}

$query = "SELECT namaDenganGelar 
          FROM pegawai 
          WHERE idPegawai = '" . mysqli_real_escape_string($conn, $idPegawai) . "'";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $pegawai = mysqli_fetch_assoc($result);
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
                        <i class="fas fa-user-plus me-2"></i>Tambah Data Keluarga
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

                    <form method="POST" action="" id="formKeluarga">
                        <input type="hidden" name="idPegawai" value="<?php echo htmlspecialchars($idPegawai); ?>">

                        <div class="mb-3">
                            <label class="form-label">Nama Pasangan <span class="text-danger">*</span></label>
                            <input type="text" name="namaPasangan" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah Anak <span class="text-danger">*</span></label>
                            <input type="number" name="jumlahAnak" id="jumlahAnak" class="form-control" min="0" max="20" value="0" required>
                            <small class="text-muted">Masukkan jumlah anak (0 jika tidak ada)</small>
                        </div>

                        <div id="containerAnak" class="mb-3">
                            <!-- Input nama anak akan ditambahkan di sini secara dinamis -->
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan & Lanjutkan
                            </button>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jumlahAnakInput = document.getElementById('jumlahAnak');
    const containerAnak = document.getElementById('containerAnak');
    
    jumlahAnakInput.addEventListener('input', function() {
        const jumlah = parseInt(this.value) || 0;
        containerAnak.innerHTML = '';
        
        if (jumlah > 0) {
            const heading = document.createElement('h5');
            heading.className = 'mt-3 mb-3';
            heading.innerHTML = '<i class="fas fa-child me-2"></i>Data Anak';
            containerAnak.appendChild(heading);
            
            for (let i = 1; i <= jumlah; i++) {
                const divAnak = document.createElement('div');
                divAnak.className = 'mb-3';
                divAnak.innerHTML = `
                    <label class="form-label">Nama Anak ke-${i} <span class="text-danger">*</span></label>
                    <input type="text" name="namaAnak${i}" class="form-control" placeholder="Masukkan nama anak ke-${i}" required>
                `;
                containerAnak.appendChild(divAnak);
            }
        }
    });
    
    // Trigger untuk set default jika ada value
    jumlahAnakInput.dispatchEvent(new Event('input'));
});
</script>

<?php include '../../includes/footer.php'; ?>