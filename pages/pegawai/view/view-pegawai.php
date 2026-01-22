<?php
require_once '../../../config/database.php';
require_once '../../../includes/check_login.php';

$page_title = 'Detail Pegawai';

// Get ID from URL
$idPegawai = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if ($idPegawai == '') {
    header("Location: ../list-pegawai.php");
    exit();
}

// Query untuk mengambil data pegawai utama
$query = "SELECT * FROM pegawai WHERE idPegawai = '$idPegawai'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: ../list-pegawai.php?error=not_found");
    exit();
}

$pegawai = mysqli_fetch_assoc($result);

// Query untuk data kepegawaian
$queryKepegawaian = "SELECT * FROM kepegawaian WHERE idPegawai = '$idPegawai' LIMIT 1";
$resultKepegawaian = mysqli_query($conn, $queryKepegawaian);
$kepegawaian = mysqli_num_rows($resultKepegawaian) > 0 ? mysqli_fetch_assoc($resultKepegawaian) : null;

// Query untuk data pangkat
$queryPangkat = "SELECT * FROM pangkat WHERE idPegawai = '$idPegawai'";
$resultPangkat = mysqli_query($conn, $queryPangkat);
$pangkat = mysqli_num_rows($resultPangkat) > 0 ? mysqli_fetch_assoc($resultPangkat) : null;

// Query untuk data alamat
$queryAlamat = "SELECT * FROM alamat WHERE idPegawai = '$idPegawai' LIMIT 1";
$resultAlamat = mysqli_query($conn, $queryAlamat);
$alamat = mysqli_num_rows($resultAlamat) > 0 ? mysqli_fetch_assoc($resultAlamat) : null;

// Query untuk data identitas
$queryIdentitas = "SELECT * FROM identitas WHERE idPegawai = '$idPegawai' LIMIT 1";
$resultIdentitas = mysqli_query($conn, $queryIdentitas);
$identitas = mysqli_num_rows($resultIdentitas) > 0 ? mysqli_fetch_assoc($resultIdentitas) : null;

// Query untuk data rekening
$queryRekening = "SELECT * FROM rekening WHERE idPegawai = '$idPegawai' LIMIT 1";
$resultRekening = mysqli_query($conn, $queryRekening);
$rekening = mysqli_num_rows($resultRekening) > 0 ? mysqli_fetch_assoc($resultRekening) : null;

// Query untuk data pendidikan
$queryPendidikan = "SELECT * FROM pendidikan WHERE idPegawai = '$idPegawai' LIMIT 1";
$resultPendidikan = mysqli_query($conn, $queryPendidikan);
$pendidikan = mysqli_num_rows($resultPendidikan) > 0 ? mysqli_fetch_assoc($resultPendidikan) : null;

// Query untuk data fisik
$queryFisik = "SELECT * FROM fisik WHERE idPegawai = '$idPegawai' LIMIT 1";
$resultFisik = mysqli_query($conn, $queryFisik);
$fisik = mysqli_num_rows($resultFisik) > 0 ? mysqli_fetch_assoc($resultFisik) : null;

// Query untuk data ukuran
$queryUkuran = "SELECT * FROM ukuran WHERE idPegawai = '$idPegawai' LIMIT 1";
$resultUkuran = mysqli_query($conn, $queryUkuran);
$ukuran = mysqli_num_rows($resultUkuran) > 0 ? mysqli_fetch_assoc($resultUkuran) : null;

// Query untuk data pasangan
$queryPasangan = "SELECT * FROM pasangan WHERE idPegawai = '$idPegawai' LIMIT 1";
$resultPasangan = mysqli_query($conn, $queryPasangan);
$pasangan = mysqli_num_rows($resultPasangan) > 0 ? mysqli_fetch_assoc($resultPasangan) : null;

// Query untuk data anak (mengambil semua anak)
$queryAnak = "SELECT * FROM anak WHERE idPegawai = '$idPegawai'";
$resultAnak = mysqli_query($conn, $queryAnak);

include '../../../includes/sidebar.php';
?>

<link rel="stylesheet" href="../../../assets/css/style-view.css">

<style>
/* Profile Card Styles */
.profile-container {
    max-width: 1400px;
    margin: 0 auto;
}

.profile-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.profile-header {
    background: linear-gradient(135deg, #0891b2 0%, #40cdf8ff 100%);
    padding: 40px;
    text-align: center;
    position: relative;
}

.profile-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.profile-avatar i {
    font-size: 70px;
    color: #0891b2;
}

.profile-name {
    color: white;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 8px;
}

.profile-nip {
    color: rgba(255, 255, 255, 0.9);
    font-size: 16px;
    margin-bottom: 0;
}

/* Info Sections */
.info-sections {
    padding: 40px;
}

.info-section {
    margin-bottom: 35px;
}

.section-title {
    font-size: 20px;
    font-weight: 700;
    color: #0891b2;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 3px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-title i {
    font-size: 24px;
}

/* Info Grid - 3 Column Layout */
.info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.info-item {
    padding: 15px 0;
}

.info-label {
    font-size: 14px;
    color: #6b7280;
    font-weight: 600;
    margin-bottom: 8px;
}

.info-value {
    font-size: 16px;
    color: #1f2937;
    font-weight: 500;
}

/* Table for children data */
.table-children {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.table-children th {
    background: #f3f4f6;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 2px solid #e5e7eb;
}

.table-children td {
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
}

.table-children tr:hover {
    background: #f9fafb;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    padding: 30px 40px 40px;
    border-top: 2px solid #f3f4f6;
}

.btn-action {
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #0891b2;
    color: white;
}

.btn-primary:hover {
    background: #0e7490;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(8, 145, 178, 0.3);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(107, 114, 128, 0.3);
}

/* Responsive */
@media (max-width: 992px) {
    .info-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .profile-header {
        padding: 30px 20px;
    }
    
    .profile-name {
        font-size: 22px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-action {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="container-fluid px-2 py-2">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h2><i class="fas fa-user-circle me-2"></i>Detail Pegawai</h2>
            <p>Informasi Lengkap Pegawai - Kantor Imigrasi Kelas II TPI Lhokseumawe</p>
        </div>
        <i class="fas fa-id-card page-header-icon d-none d-md-block"></i>
    </div>

    <div class="profile-container">
        <!-- Profile Card -->
        <div class="profile-card">
            <!-- Info Sections -->
            <div class="info-sections">
                <!-- Data Personal -->
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-user"></i>
                        Data Personal
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">ID Pegawai</div>
                            <div class="info-value"><?php echo htmlspecialchars($pegawai['idPegawai']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Jenis Kelamin</div>
                            <div class="info-value"><?php echo htmlspecialchars($pegawai['gender']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">NIP</div>
                            <div class="info-value"><?php echo htmlspecialchars($pegawai['nip']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Agama</div>
                            <div class="info-value"><?php echo htmlspecialchars($pegawai['agama']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Nama</div>
                            <div class="info-value"><?php echo htmlspecialchars($pegawai['nama']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Status Pegawai</div>
                            <div class="info-value">
                                <?php 
                                if(isset($pegawai['statusPegawai'])) {
                                    echo $pegawai['statusPegawai'] == 1 ? 'Kasi Kepegawaian' : 'Tidak Aktif';
                                } else {
                                    echo '-';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Gelar Depan</div>
                            <div class="info-value"><?php echo htmlspecialchars($pegawai['gelarDepan'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email Dinas</div>
                            <div class="info-value"><?php echo htmlspecialchars($pegawai['emailDinas'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Gelar Belakang</div>
                            <div class="info-value"><?php echo htmlspecialchars($pegawai['gelarBelakang'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email Pribadi</div>
                            <div class="info-value"><?php echo htmlspecialchars($pegawai['emailPribadi'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Nama Dengan Gelar</div>
                            <div class="info-value"><?php echo htmlspecialchars($pegawai['namaDenganGelar'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">No Telepon</div>
                            <div class="info-value"><?php echo htmlspecialchars($pegawai['noHp'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Tempat Lahir</div>
                            <div class="info-value"><?php echo htmlspecialchars($pegawai['tempatLahir'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Hobi</div>
                            <div class="info-value"><?php echo htmlspecialchars($pegawai['hobi'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Tanggal Lahir</div>
                            <div class="info-value">
                                <?php 
                                echo isset($pegawai['tanggalLahir']) && $pegawai['tanggalLahir'] != '0000-00-00' 
                                    ? date('d F Y', strtotime($pegawai['tanggalLahir'])) 
                                    : '-'; 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Kepegawaian -->
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-id-card"></i>
                        Data Kepegawaian
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Status Kepegawaian</div>
                            <div class="info-value"><?php echo htmlspecialchars($kepegawaian['statusKepegawaian'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Jabatan</div>
                            <div class="info-value"><?php echo htmlspecialchars($kepegawaian['jabatan'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">TMT Jabatan</div>
                            <div class="info-value">
                                <?php 
                                echo isset($kepegawaian['tmtJabatan']) && $kepegawaian['tmtJabatan'] != '0000-00-00' 
                                    ? date('d F Y', strtotime($kepegawaian['tmtJabatan'])) 
                                    : '-'; 
                                ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Bagian Kerja</div>
                            <div class="info-value"><?php echo htmlspecialchars($kepegawaian['bagianKerja'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Eselon</div>
                            <div class="info-value"><?php echo htmlspecialchars($kepegawaian['eselon'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Angkatan Pejim</div>
                            <div class="info-value"><?php echo htmlspecialchars($kepegawaian['angkatanPejim'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">PPNS</div>
                            <div class="info-value"><?php echo htmlspecialchars($kepegawaian['ppns'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">TMT Pensiun</div>
                            <div class="info-value">
                                <?php 
                                echo isset($kepegawaian['tmtPensiun']) && $kepegawaian['tmtPensiun'] != '0000-00-00' 
                                    ? date('d F Y', strtotime($kepegawaian['tmtPensiun'])) 
                                    : '-'; 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Pangkat -->
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-medal"></i>
                        Data Pangkat
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Pangkat</div>
                            <div class="info-value"><?php echo htmlspecialchars($pangkat['pangkat'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Golongan Ruang</div>
                            <div class="info-value"><?php echo htmlspecialchars($pangkat['golonganRuang'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Tanggal SK Pangkat</div>
                            <div class="info-value">
                                <?php 
                                echo isset($pangkat['tanggalSKPangkat']) && $pangkat['tanggalSKPangkat'] != '0000-00-00' 
                                    ? date('d F Y', strtotime($pangkat['tanggalSKPangkat'])) 
                                    : '-'; 
                                ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Nomor SK Pangkat</div>
                            <div class="info-value"><?php echo htmlspecialchars($pangkat['nomorSKPangkat'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">SK Pangkat Dari</div>
                            <div class="info-value"><?php echo htmlspecialchars($pangkat['SKPangkatDari'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Uraian SK Pangkat</div>
                            <div class="info-value"><?php echo htmlspecialchars($pangkat['uraianSKPangkat'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">TMT Pangkat</div>
                            <div class="info-value">
                                <?php 
                                echo isset($pangkat['tmtPangkat']) && $pangkat['tmtPangkat'] != '0000-00-00' 
                                    ? date('d F Y', strtotime($pangkat['tmtPangkat'])) 
                                    : '-'; 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Alamat -->
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Data Alamat
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Alamat KTP</div>
                            <div class="info-value"><?php echo htmlspecialchars($alamat['alamatKTP'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Alamat Domisili</div>
                            <div class="info-value"><?php echo htmlspecialchars($alamat['alamatDomisili'] ?? '-'); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Data Identitas -->
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-address-card"></i>
                        Data Identitas
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">NIK</div>
                            <div class="info-value"><?php echo htmlspecialchars($identitas['nik'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">No KK</div>
                            <div class="info-value"><?php echo htmlspecialchars($identitas['nomorKK'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">No BPJS</div>
                            <div class="info-value"><?php echo htmlspecialchars($identitas['nomorBPJS'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">No Taspen</div>
                            <div class="info-value"><?php echo htmlspecialchars($identitas['nomorTaspen'] ?? '-'); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Data Rekening -->
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-credit-card"></i>
                        Data Rekening
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">No Rekening Gaji</div>
                            <div class="info-value"><?php echo htmlspecialchars($rekening['nomorRekGaji'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Nama Bank</div>
                            <div class="info-value"><?php echo htmlspecialchars($rekening['namaBank'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Nama Kantor Cabang</div>
                            <div class="info-value"><?php echo htmlspecialchars($rekening['kantorCabang'] ?? '-'); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Data Pendidikan -->
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-graduation-cap"></i>
                        Data Pendidikan
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Pendidikan Terakhir</div>
                            <div class="info-value"><?php echo htmlspecialchars($pendidikan['pendidikanTerakhir'] ?? '-'); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Data Fisik -->
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-heartbeat"></i>
                        Data Fisik
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Tinggi Badan</div>
                            <div class="info-value"><?php echo htmlspecialchars($fisik['tinggiBadan'] ?? '-'); ?> CM</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Berat Badan</div>
                            <div class="info-value"><?php echo htmlspecialchars($fisik['beratBadan'] ?? '-'); ?> KG</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Jenis Rambut</div>
                            <div class="info-value"><?php echo htmlspecialchars($fisik['jenisRambut'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Warna Rambut</div>
                            <div class="info-value"><?php echo htmlspecialchars($fisik['warnaRambut'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Bentuk Wajah</div>
                            <div class="info-value"><?php echo htmlspecialchars($fisik['bentukWajah'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Warna Kulit</div>
                            <div class="info-value"><?php echo htmlspecialchars($fisik['warnaKulit'] ?? '-'); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Data Ukuran Dinas -->
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-tshirt"></i>
                        Data Ukuran Dinas
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Ukuran Pad Divamot</div>
                            <div class="info-value"><?php echo htmlspecialchars($ukuran['ukuranPadDivamot'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Ukuran Sepatu</div>
                            <div class="info-value"><?php echo htmlspecialchars($ukuran['ukuranSepatu'] ?? '-'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Ukuran Topi</div>
                            <div class="info-value"><?php echo htmlspecialchars($ukuran['ukuranTopi'] ?? '-'); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Data Pasangan -->
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-heart"></i>
                        Data Pasangan
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Nama Pasangan</div>
                            <div class="info-value"><?php echo htmlspecialchars($pasangan['namaPasangan'] ?? '-'); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Data Anak -->
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-child"></i>
                        Data Anak
                    </h3>
                    <?php if(mysqli_num_rows($resultAnak) > 0): ?>
                        <table class="table-children">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Anak</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                while($anak = mysqli_fetch_assoc($resultAnak)): 
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($anak['namaAnak']); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Nama Anak</div>
                                <div class="info-value">Tidak ada data anak</div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include '../../../includes/footer.php'; ?>