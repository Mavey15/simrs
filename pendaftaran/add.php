<?php
require_once __DIR__ . '/../_config/config.php';

// Check session
if(!isset($_SESSION['user'])) {
    header('Location: ' . base_url('auth/login.php'));
    exit;
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor_rm = trim($_POST['nomor_rm'] ?? '');
    $tanggal_masuk = $_POST['tanggal_masuk'] ?? '';
    $cara_bayar = trim($_POST['cara_bayar'] ?? '');
    $id_dokter = trim($_POST['id_dokter'] ?? '');
    $id_poli = trim($_POST['id_poli'] ?? '');

    if($nomor_rm === '' || $tanggal_masuk === '' || $cara_bayar === '') {
        $error = 'No. RM, Tanggal Masuk, dan Cara Bayar wajib diisi.';
    } else {
        // Verify patient exists
        $nomor_rm_esc = mysqli_real_escape_string($con, $nomor_rm);
        $check_sql = "SELECT nomor_rm FROM pasien WHERE nomor_rm = '$nomor_rm_esc' LIMIT 1";
        $check_result = mysqli_query($con, $check_sql);
        
        if(mysqli_num_rows($check_result) === 0) {
            $error = 'Pasien dengan nomor RM ini tidak ditemukan.';
        } else {
            $cara_bayar_esc = mysqli_real_escape_string($con, $cara_bayar);
            $id_dokter_esc = mysqli_real_escape_string($con, $id_dokter);
            $id_poli_esc = mysqli_real_escape_string($con, $id_poli);

            $sql = "INSERT INTO pendaftaran (nomor_rm, tanggal_masuk, cara_bayar, id_dokter, id_poli) 
                VALUES ('$nomor_rm_esc', '$tanggal_masuk', '$cara_bayar_esc', '$id_dokter_esc', '$id_poli_esc')";

            if(mysqli_query($con, $sql)) {
                $success = 'Data pendaftaran berhasil ditambahkan.';
                header('Refresh: 2; url=' . base_url('pendaftaran/list.php'));
            } else {
                $error = 'Error: ' . mysqli_error($con);
            }
        }
    }
}

// Get list of patients, doctors, and clinics for dropdowns
$pasien_sql = "SELECT nomor_rm, nama_pasien FROM pasien ORDER BY nama_pasien ASC";
$pasien_result = mysqli_query($con, $pasien_sql);

$dokter_sql = "SELECT id_dokter, nama_dokter FROM dokter ORDER BY nama_dokter ASC";
$dokter_result = mysqli_query($con, $dokter_sql);

$poli_sql = "SELECT id_poli, nama_poli FROM poliklinik ORDER BY nama_poli ASC";
$poli_result = mysqli_query($con, $poli_sql);
?>
<?php require_once __DIR__ . '/../_header.php'; ?>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="card-style">
                <h2><i class="fa fa-calendar-check"></i> Tambah Data Pendaftaran</h2>

                <?php if($error): ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?=htmlspecialchars($error);?>
                    </div>
                <?php endif; ?>

                <?php if($success): ?>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?=htmlspecialchars($success);?>
                    </div>
                <?php endif; ?>

                <form method="post" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">No. RM Pasien *</label>
                        <div class="col-sm-9">
                            <input type="text" name="nomor_rm" id="nomor_rm" class="form-control" placeholder="Cari atau masukkan nomor RM..." required autocomplete="off">
                            <small class="text-muted">Mulai ketik untuk mencari pasien</small>
                            <ul id="nomor_rm_suggestions" class="list-group" style="display:none; position:absolute; width:100%; max-height:200px; overflow-y:auto; z-index:1000; margin-top:5px;"></ul>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tanggal Masuk *</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" name="tanggal_masuk" class="form-control" value="<?=date('Y-m-d\TH:i');?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Cara Bayar *</label>
                        <div class="col-sm-9">
                            <select name="cara_bayar" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="Tunai">Tunai</option>
                                <option value="Asuransi">Asuransi</option>
                                <option value="BPJS">BPJS</option>
                                <option value="Cicilan">Cicilan</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Dokter</label>
                        <div class="col-sm-9">
                            <select name="id_dokter" class="form-control">
                                <option value="">-- Pilih Dokter --</option>
                                <?php 
                                mysqli_data_seek($dokter_result, 0);
                                while($row = mysqli_fetch_assoc($dokter_result)): ?>
                                    <option value="<?=htmlspecialchars($row['id_dokter']);?>">
                                        <?=htmlspecialchars($row['nama_dokter']);?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Poliklinik</label>
                        <div class="col-sm-9">
                            <select name="id_poli" class="form-control">
                                <option value="">-- Pilih Poli --</option>
                                <?php 
                                mysqli_data_seek($poli_result, 0);
                                while($row = mysqli_fetch_assoc($poli_result)): ?>
                                    <option value="<?=htmlspecialchars($row['id_poli']);?>">
                                        <?=htmlspecialchars($row['nama_poli']);?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                            <a href="<?=base_url('pendaftaran/list.php');?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </form>
                </div> <!-- /.card-style -->
            </div>
        </div>
    </div>

<script>
// Patient search functionality
var patients = [
    <?php 
    // Reset result pointer and output patient data as JavaScript array
    mysqli_data_seek($pasien_result, 0);
    $first = true;
    while($row = mysqli_fetch_assoc($pasien_result)): 
        if(!$first) echo ',';
    ?>
    {nomor_rm: '<?=htmlspecialchars($row['nomor_rm']);?>', nama: '<?=htmlspecialchars($row['nama_pasien']);?>'}
    <?php $first = false; endwhile; ?>
];

var inputField = document.getElementById('nomor_rm');
var suggestionsBox = document.getElementById('nomor_rm_suggestions');

inputField.addEventListener('input', function() {
    var value = this.value.trim().toLowerCase();
    suggestionsBox.innerHTML = '';
    
    if(value.length === 0) {
        suggestionsBox.style.display = 'none';
        return;
    }
    
    var matches = patients.filter(function(p) {
        return p.nomor_rm.toLowerCase().includes(value) || p.nama.toLowerCase().includes(value);
    });
    
    if(matches.length > 0) {
        suggestionsBox.style.display = 'block';
        matches.slice(0, 10).forEach(function(match) {
            var li = document.createElement('li');
            li.className = 'list-group-item';
            li.style.cursor = 'pointer';
            li.innerHTML = match.nomor_rm + ' - ' + match.nama;
            li.onclick = function() {
                inputField.value = match.nomor_rm;
                suggestionsBox.style.display = 'none';
            };
            suggestionsBox.appendChild(li);
        });
    } else {
        suggestionsBox.style.display = 'none';
    }
});

// Hide suggestions when clicking outside
document.addEventListener('click', function(e) {
    if(e.target !== inputField) {
        suggestionsBox.style.display = 'none';
    }
});
</script>

<?php require_once __DIR__ . '/../_footer.php'; ?>
