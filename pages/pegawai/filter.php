<?php
require_once '../../config/database.php';
require_once '../../includes/check_login.php';

$page_title = 'Data Filter';

// Definisi semua kolom yang tersedia dengan mapping ke database
$available_columns = [
    'nip' => ['label' => 'NIP', 'table' => 'pegawai', 'column' => 'nip'],
    'nama' => ['label' => 'Nama', 'table' => 'pegawai', 'column' => 'nama'],
    'gelarDepan' => ['label' => 'Gelar Depan', 'table' => 'pegawai', 'column' => 'gelarDepan'],
    'gelarBelakang' => ['label' => 'Gelar Belakang', 'table' => 'pegawai', 'column' => 'gelarBelakang'],
    'namaDenganGelar' => ['label' => 'Nama dengan Gelar', 'table' => 'pegawai', 'column' => 'namaDenganGelar'],
    'jabatan' => ['label' => 'Jabatan', 'table' => 'kepegawaian', 'column' => 'jabatan'],
    'tmtJabatan' => ['label' => 'TMT Jabatan', 'table' => 'kepegawaian', 'column' => 'tmtJabatan'],
    'bagianKerja' => ['label' => 'Bagian Kerja', 'table' => 'kepegawaian', 'column' => 'bagianKerja'],
    'tempatLahir' => ['label' => 'Tempat Lahir', 'table' => 'pegawai', 'column' => 'tempatLahir'],
    'tanggalLahir' => ['label' => 'Tanggal Lahir', 'table' => 'pegawai', 'column' => 'tanggalLahir'],
    'gender' => ['label' => 'Jenis Kelamin', 'table' => 'pegawai', 'column' => 'gender'],
    'statusKepegawaian' => ['label' => 'Status Kepegawaian', 'table' => 'kepegawaian', 'column' => 'statusKepegawaian'],
    'pangkat' => ['label' => 'Pangkat', 'table' => 'pangkat', 'column' => 'pangkat'],
    'golonganRuang' => ['label' => 'Golongan Ruang', 'table' => 'pangkat', 'column' => 'golonganRuang'],
    'tmtPangkat' => ['label' => 'TMT Pangkat', 'table' => 'pangkat', 'column' => 'tmtPangkat'],
    'eselon' => ['label' => 'Eselon', 'table' => 'kepegawaian', 'column' => 'eselon'],
    'agama' => ['label' => 'Agama', 'table' => 'pegawai', 'column' => 'agama'],
    'alamatDomisili' => ['label' => 'Alamat Domisili', 'table' => 'alamat', 'column' => 'alamatDomisili'],
    'alamatKTP' => ['label' => 'Alamat KTP', 'table' => 'alamat', 'column' => 'alamatKTP'],
    'noHp' => ['label' => 'No Telpon', 'table' => 'pegawai', 'column' => 'noHp'],
    'tmtPensiun' => ['label' => 'TMT Pensiun', 'table' => 'kepegawaian', 'column' => 'tmtPensiun'],
    'angkatanPejim' => ['label' => 'Angkatan Pejim', 'table' => 'kepegawaian', 'column' => 'angkatanPejim'],
    'ppns' => ['label' => 'PPNS', 'table' => 'kepegawaian', 'column' => 'ppns'],
    'pendidikanTerakhir' => ['label' => 'Pendidikan Terakhir', 'table' => 'pendidikan', 'column' => 'pendidikanTerakhir'],
    'nomorBPJS' => ['label' => 'Nomor BPJS', 'table' => 'identitas', 'column' => 'nomorBPJS'],
    'nomorTaspen' => ['label' => 'Nomor Taspen', 'table' => 'identitas', 'column' => 'nomorTaspen'],
    'namaPasangan' => ['label' => 'Nama Pasangan', 'table' => 'pasangan', 'column' => 'namaPasangan'],
    'emailDinas' => ['label' => 'Email Dinas', 'table' => 'pegawai', 'column' => 'emailDinas'],
    'emailPribadi' => ['label' => 'Email Pribadi', 'table' => 'pegawai', 'column' => 'emailPribadi'],
    'nik' => ['label' => 'NIK', 'table' => 'identitas', 'column' => 'nik'],
    'nomorKK' => ['label' => 'Nomor KK', 'table' => 'identitas', 'column' => 'nomorKK'],
    'nomorRekGaji' => ['label' => 'Nomor Rek Gaji', 'table' => 'rekening', 'column' => 'nomorRekGaji'],
    'namaBank' => ['label' => 'Nama Bank', 'table' => 'rekening', 'column' => 'namaBank'],
    'kantorCabang' => ['label' => 'Kantor Cabang Bank', 'table' => 'rekening', 'column' => 'kantorCabang'],
    'tinggiBadan' => ['label' => 'Tinggi Badan (CM)', 'table' => 'fisik', 'column' => 'tinggiBadan'],
    'beratBadan' => ['label' => 'Berat Badan (KG)', 'table' => 'fisik', 'column' => 'beratBadan'],
    'jenisRambut' => ['label' => 'Jenis Rambut', 'table' => 'fisik', 'column' => 'jenisRambut'],
    'warnaRambut' => ['label' => 'Warna Rambut', 'table' => 'fisik', 'column' => 'warnaRambut'],
    'bentukWajah' => ['label' => 'Bentuk Wajah', 'table' => 'fisik', 'column' => 'bentukWajah'],
    'warnaKulit' => ['label' => 'Warna Kulit', 'table' => 'fisik', 'column' => 'warnaKulit'],
    'ciriKhusus' => ['label' => 'Ciri Khusus', 'table' => 'fisik', 'column' => 'ciriKhusus'],
    'hobi' => ['label' => 'Hobi', 'table' => 'pegawai', 'column' => 'hobi'],
    'ukuranPadDivamot' => ['label' => 'Ukuran PAD dan DIVAMOT', 'table' => 'ukuran', 'column' => 'ukuranPadDivamot'],
    'ukuranSepatu' => ['label' => 'Ukuran Sepatu Dinas', 'table' => 'ukuran', 'column' => 'ukuranSepatu'],
    'ukuranTopi' => ['label' => 'Ukuran Topi', 'table' => 'ukuran', 'column' => 'ukuranTopi'],
    'statusPegawai' => ['label' => 'Status Pegawai (Aktif/Tidak Aktif)', 'table' => 'pegawai', 'column' => 'statusPegawai'],
    // TAMBAHAN: Data Anak
    'namaAnak' => ['label' => 'Nama Anak', 'table' => 'anak', 'column' => 'GROUP_CONCAT(an.namaAnak SEPARATOR ", ")'],
];

// Ambil kolom yang dipilih dari form
$selected_columns = isset($_POST['columns']) ? $_POST['columns'] : ['namaDenganGelar', 'nip'];

// Build query berdasarkan kolom yang dipilih
$select_parts = ['p.idPegawai'];
$tables_needed = ['pegawai'];
$has_aggregate = false;

foreach ($selected_columns as $col) {
    if (isset($available_columns[$col])) {
        $table = $available_columns[$col]['table'];
        $column = $available_columns[$col]['column'];
        
        // Tambahkan alias untuk menghindari duplikasi
        $alias_table = substr($table, 0, 1);
        if ($table === 'pegawai') $alias_table = 'p';
        elseif ($table === 'kepegawaian') $alias_table = 'k';
        elseif ($table === 'pangkat') $alias_table = 'pg';
        elseif ($table === 'alamat') $alias_table = 'a';
        elseif ($table === 'identitas') $alias_table = 'id';
        elseif ($table === 'pendidikan') $alias_table = 'pd';
        elseif ($table === 'pasangan') $alias_table = 'ps';
        elseif ($table === 'rekening') $alias_table = 'r';
        elseif ($table === 'fisik') $alias_table = 'f';
        elseif ($table === 'ukuran') $alias_table = 'u';
        elseif ($table === 'anak') $alias_table = 'an';
        
        // Cek apakah ada fungsi agregat (GROUP_CONCAT)
        if (strpos($column, 'GROUP_CONCAT') !== false) {
            $select_parts[] = $column . " AS " . $col;
            $has_aggregate = true;
        } else {
            $select_parts[] = "$alias_table.$column";
        }
        
        if (!in_array($table, $tables_needed)) {
            $tables_needed[] = $table;
        }
    }
}

// Build JOIN berdasarkan tabel yang dibutuhkan
$query = "SELECT " . implode(', ', $select_parts) . " FROM pegawai p";

if (in_array('kepegawaian', $tables_needed)) {
    $query .= " LEFT JOIN kepegawaian k ON p.idPegawai = k.idPegawai";
}
if (in_array('pangkat', $tables_needed)) {
    $query .= " LEFT JOIN pangkat pg ON p.idPegawai = pg.idPegawai";
}
if (in_array('alamat', $tables_needed)) {
    $query .= " LEFT JOIN alamat a ON p.idPegawai = a.idPegawai";
}
if (in_array('identitas', $tables_needed)) {
    $query .= " LEFT JOIN identitas id ON p.idPegawai = id.idPegawai";
}
if (in_array('pendidikan', $tables_needed)) {
    $query .= " LEFT JOIN pendidikan pd ON p.idPegawai = pd.idPegawai";
}
if (in_array('pasangan', $tables_needed)) {
    $query .= " LEFT JOIN pasangan ps ON p.idPegawai = ps.idPegawai";
}
if (in_array('rekening', $tables_needed)) {
    $query .= " LEFT JOIN rekening r ON p.idPegawai = r.idPegawai";
}
if (in_array('fisik', $tables_needed)) {
    $query .= " LEFT JOIN fisik f ON p.idPegawai = f.idPegawai";
}
if (in_array('ukuran', $tables_needed)) {
    $query .= " LEFT JOIN ukuran u ON p.idPegawai = u.idPegawai";
}
if (in_array('anak', $tables_needed)) {
    $query .= " LEFT JOIN anak an ON p.idPegawai = an.idPegawai";
}

// Tambahkan GROUP BY jika ada fungsi agregat
if ($has_aggregate) {
    $query .= " GROUP BY p.idPegawai";
}

$query .= " ORDER BY p.namaDenganGelar ASC";

$result = mysqli_query($conn, $query);

include '../../includes/sidebar.php';
?>

<style>
    .filter-panel {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .filter-header h5 {
        margin: 0;
        color: #0891b2;
        font-weight: 700;
        font-size: 16px;
    }
    
    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 8px;
    }
    
    .checkbox-item {
        display: flex;
        align-items: center;
        padding: 6px 10px;
        background: #f8f9fa;
        border-radius: 5px;
        transition: all 0.2s;
    }
    
    .checkbox-item:hover {
        background: #e5f3f6;
    }
    
    .checkbox-item input[type="checkbox"] {
        width: 16px;
        height: 16px;
        margin-right: 8px;
        cursor: pointer;
    }
    
    .checkbox-item label {
        margin: 0;
        cursor: pointer;
        font-size: 12px;
        user-select: none;
    }
    
    .btn-apply-filter {
        background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
        color: white;
        padding: 10px 25px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(8, 145, 178, 0.3);
        transition: all 0.3s;
    }
    
    .btn-apply-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(8, 145, 178, 0.4);
        color: white;
    }
    
    .btn-select-all, .btn-deselect-all {
        padding: 5px 12px;
        border-radius: 5px;
        font-size: 12px;
        margin-left: 6px;
    }
    
    .page-header {
        background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
        padding: 20px 25px;
        border-radius: 10px;
        color: white;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .page-header h2 {
        margin: 0;
        font-weight: 700;
        font-size: 22px;
    }
    
    .page-header p {
        margin: 5px 0 0 0;
        opacity: 0.9;
        font-size: 13px;
    }
    
    .selected-count {
        background: white;
        color: #0891b2;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    /* Table Styles - Compact */
    .table {
        font-size: 12px;
    }
    
    .table thead th {
        padding: 10px 8px;
        font-size: 12px;
        white-space: nowrap;
    }
    
    .table tbody td {
        padding: 8px;
        font-size: 11px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    /* DataTable wrapper compact */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        font-size: 12px;
    }
    
    .dataTables_wrapper .dataTables_length select {
        font-size: 11px;
        padding: 3px 6px;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        font-size: 11px;
        padding: 4px 8px;
    }
</style>

<div class="container-fluid px-3 py-3">
    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="fas fa-filter me-2"></i>Data Filter Pegawai</h2>
        <p>Pilih kolom data yang ingin ditampilkan</p>
    </div>

    <!-- Filter Panel -->
    <div class="filter-panel">
        <div class="filter-header">
            <h5>
                <i class="fas fa-check-square me-2"></i>Pilih Kolom Data
                <span class="selected-count ms-2" id="selected-count"><?php echo count($selected_columns); ?> terpilih</span>
            </h5>
            <div>
                <button type="button" class="btn btn-sm btn-outline-primary btn-select-all" onclick="selectAll()">
                    <i class="fas fa-check-double me-1"></i>Pilih Semua
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary btn-deselect-all" onclick="deselectAll()">
                    <i class="fas fa-times me-1"></i>Hapus Semua
                </button>
            </div>
        </div>
        
        <form method="POST" id="filterForm">
            <div class="checkbox-grid">
                <?php foreach ($available_columns as $key => $column): ?>
                <div class="checkbox-item">
                    <input type="checkbox" 
                           name="columns[]" 
                           value="<?php echo $key; ?>" 
                           id="col_<?php echo $key; ?>"
                           <?php echo in_array($key, $selected_columns) ? 'checked' : ''; ?>
                           onchange="updateCount()">
                    <label for="col_<?php echo $key; ?>"><?php echo $column['label']; ?></label>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-apply-filter">
                    <i class="fas fa-sync-alt me-2"></i>Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTable">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <?php foreach ($selected_columns as $col): ?>
                                <?php if (isset($available_columns[$col])): ?>
                                    <th><?php echo $available_columns[$col]['label']; ?></th>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($result && mysqli_num_rows($result) > 0):
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)): 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <?php foreach ($selected_columns as $col): ?>
                                <?php if (isset($available_columns[$col])): ?>
                                    <td>
                                        <?php 
                                        // Untuk kolom dengan GROUP_CONCAT, gunakan key langsung
                                        if ($col === 'namaAnak') {
                                            echo $row[$col] ?? '-';
                                        } else {
                                            echo $row[$available_columns[$col]['column']] ?? '-';
                                        }
                                        ?>
                                    </td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                        <?php 
                            endwhile;
                        else: 
                        ?>
                        <tr>
                            <td colspan="<?php echo count($selected_columns) + 1; ?>" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5>Tidak ada data pegawai</h5>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function updateCount() {
    const checked = document.querySelectorAll('input[name="columns[]"]:checked').length;
    document.getElementById('selected-count').textContent = checked + ' terpilih';
}

function selectAll() {
    document.querySelectorAll('input[name="columns[]"]').forEach(cb => cb.checked = true);
    updateCount();
}

function deselectAll() {
    document.querySelectorAll('input[name="columns[]"]').forEach(cb => cb.checked = false);
    updateCount();
}

// DataTable initialization
$(document).ready(function() {
    $('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "pageLength": 25,
        "order": [],
        "scrollX": true
    });
});
</script>

<?php include '../../includes/footer.php'; ?>