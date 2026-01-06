<?php
require_once '../../config/database.php';

$page_title = 'Tambah Data Pegawai';

if (isset($_POST['submit'])) {
    $nip          = mysqli_real_escape_string($conn, $_POST['nip']);
    $nama         = mysqli_real_escape_string($conn, $_POST['nama']);
    $gelarDepan   = mysqli_real_escape_string($conn, $_POST['gelarDepan']);
    $gelarBelakang= mysqli_real_escape_string($conn, $_POST['gelarBelakang']);
    $tempatLahir  = mysqli_real_escape_string($conn, $_POST['tempatLahir']);
    $tanggalLahir = mysqli_real_escape_string($conn, $_POST['tanggalLahir']);
    $gender       = mysqli_real_escape_string($conn, $_POST['gender']);
    $agama        = mysqli_real_escape_string($conn, $_POST['agama']);

    $statusPegawai = (int) $_POST['statusPegawai'];

    $emailPribadi = mysqli_real_escape_string($conn, $_POST['emailPribadi']);
    $emailDinas   = mysqli_real_escape_string($conn, $_POST['emailDinas']);
    $noHp         = mysqli_real_escape_string($conn, $_POST['noHp']);
    $hobi         = mysqli_real_escape_string($conn, $_POST['hobi']);

    $namaDenganGelar = trim(
        ($gelarDepan !== '' ? $gelarDepan . ' ' : '') .
        $nama .
        ($gelarBelakang !== '' ? ', ' . $gelarBelakang : '')
    );
    $namaDenganGelar = mysqli_real_escape_string($conn, $namaDenganGelar);

    $query = "INSERT INTO pegawai 
        (nip, nama, gelarDepan, gelarBelakang, namaDenganGelar, tempatLahir, tanggalLahir, gender, agama, statusPegawai, emailPribadi, emailDinas, noHp, hobi)
        VALUES
        ('$nip', '$nama', '$gelarDepan', '$gelarBelakang', '$namaDenganGelar', '$tempatLahir', '$tanggalLahir', '$gender', '$agama', $statusPegawai, '$emailPribadi', '$emailDinas', '$noHp', '$hobi')";

    if (mysqli_query($conn, $query)) {
        $idPegawai = mysqli_insert_id($conn);
        header("Location: tambah-kepegawaian.php?idPegawai=$idPegawai");
        exit();
    } else {
        $error = "Gagal menambahkan data: " . mysqli_error($conn);
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
                        <i class="fas fa-user-plus me-2"></i>Tambah Data Pegawai
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">NIP <span class="text-danger">*</span></label>
                            <input type="text" name="nip" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gelar Depan <span class="text-danger">*</span></label>
                            <input type="text" name="gelarDepan" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gelar Belakang <span class="text-danger">*</span></label>
                            <input type="text" name="gelarBelakang" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" name="tempatLahir" class="form-control" required></input>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" name="tanggalLahir" class="form-control" required></input>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <div>
                                <input type="radio" name="gender" id="male" value="male" required>
                                <label for="male">Laki-laki</label>
                            </div>
                            <div>
                                <input type="radio" name="gender" id="female" value="female" required>
                                <label for="female">Perempuan</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Agama <span class="text-danger">*</span></label>
                            <select name="agama" class="form-control" required>
                                <option value="" disabled selected>Pilih Agama</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen Protestan">Kristen Protestan</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Khonghucu">Khonghucu</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status Pegawai <span class="text-danger">*</span></label>
                            <select name="statusPegawai" class="form-control" required>
                                <option value="" disabled selected>Pilih Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Pribadi <span class="text-danger">*</span></label>
                            <input type="email" name="emailPribadi" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Dinas <span class="text-danger">*</span></label>
                            <input type="email" name="emailDinas" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Telp <span class="text-danger">*</span></label>
                            <input type="text" name="noHp" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hobi <span class="text-danger">*</span></label>
                            <input type="text" name="hobi" class="form-control" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Selanjutnya
                            </button>
                            <a href="list-pegawai.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>