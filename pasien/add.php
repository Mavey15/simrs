<?php
require_once __DIR__ . '/../_config/config.php';

// Check session
if(!isset($_SESSION['user'])) {
    header('Location: ' . base_url('auth/login.php'));
    exit;
}

$error = '';
$success = '';

// Function to generate auto nomor_rm
function generateNomorRM($con) {
    $sql = "SELECT MAX(CAST(nomor_rm AS UNSIGNED)) as max_seq FROM pasien";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $next_seq = ($row['max_seq'] ?? 0) + 1;
    return str_pad($next_seq, 6, '0', STR_PAD_LEFT);
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pasien = trim($_POST['nama_pasien'] ?? '');
    $nik = trim($_POST['nik'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $umur = trim($_POST['umur'] ?? '');
    $jenis_kelamin = trim($_POST['jenis_kelamin'] ?? '');
    $pekerjaan = trim($_POST['pekerjaan'] ?? '');
    $agama = trim($_POST['agama'] ?? '');
    $nama_ibu = trim($_POST['nama_ibu'] ?? '');
    $nomor_hp = trim($_POST['nomor_hp'] ?? '');

    // Auto-generate nomor_rm
    $nomor_rm = generateNomorRM($con);

    if($nama_pasien === '' || $nik === '') {
        $error = 'Nama dan NIK wajib diisi.';
    } else {
        // Check for duplicate NIK
        $nik_check_esc = mysqli_real_escape_string($con, $nik);
        $nik_check_sql = "SELECT nomor_rm FROM pasien WHERE nik = '$nik_check_esc' LIMIT 1";
        $nik_check_result = mysqli_query($con, $nik_check_sql);
        $nik_exists = mysqli_num_rows($nik_check_result) > 0;
        
        if($nik_exists) {
            $error = 'NIK ini sudah terdaftar dalam sistem.';
        } else {
            $nomor_rm_esc = mysqli_real_escape_string($con, $nomor_rm);
            $nama_pasien_esc = mysqli_real_escape_string($con, $nama_pasien);
            $nik_esc = mysqli_real_escape_string($con, $nik);
            $alamat_esc = mysqli_real_escape_string($con, $alamat);
            $pekerjaan_esc = mysqli_real_escape_string($con, $pekerjaan);
            $agama_esc = mysqli_real_escape_string($con, $agama);
            $nama_ibu_esc = mysqli_real_escape_string($con, $nama_ibu);
            $nomor_hp_esc = mysqli_real_escape_string($con, $nomor_hp);

            $sql = "INSERT INTO pasien (nomor_rm, nama_pasien, nik, alamat, tanggal_lahir, umur, jenis_kelamin, pekerjaan, agama, nama_ibu, nomor_hp) 
                VALUES ('$nomor_rm_esc', '$nama_pasien_esc', '$nik_esc', '$alamat_esc', '$tanggal_lahir', '$umur', '$jenis_kelamin', '$pekerjaan_esc', '$agama_esc', '$nama_ibu_esc', '$nomor_hp_esc')";

            if(mysqli_query($con, $sql)) {
                $success = 'Data pasien berhasil ditambahkan.';
                header('Refresh: 2; url=' . base_url('pasien/list.php'));
            } else {
                $error = 'Error: ' . mysqli_error($con);
            }
        }
    }
}
?>
<?php require_once __DIR__ . '/../_header.php'; ?>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="card-style">
                <h2><i class="fa fa-user-injured"></i> Tambah Data Pasien</h2>

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
                        <label class="col-sm-3 control-label">No. RM</label>
                        <div class="col-sm-9">
                            <p class="form-control-static" id="nomor_rm_display">
                                <?php 
                                    if($_SERVER['REQUEST_METHOD'] === 'GET') {
                                        echo generateNomorRM($con);
                                    }
                                ?>
                            </p>
                            <small class="text-muted">Nomor RM akan di-generate otomatis</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Pasien *</label>
                        <div class="col-sm-9">
                            <input type="text" name="nama_pasien" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">NIK *</label>
                        <div class="col-sm-9">
                            <input type="text" name="nik" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Alamat</label>
                        <div class="col-sm-9">
                            <textarea name="alamat" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tanggal Lahir</label>
                        <div class="col-sm-9">
                            <input type="date" name="tanggal_lahir" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Umur</label>
                        <div class="col-sm-9">
                            <input type="number" name="umur" class="form-control" min="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Jenis Kelamin</label>
                        <div class="col-sm-9">
                            <select name="jenis_kelamin" class="form-control">
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Pekerjaan</label>
                        <div class="col-sm-9">
                            <input type="text" name="pekerjaan" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Agama</label>
                        <div class="col-sm-9">
                            <select name="agama" class="form-control">
                                <option value="">-- Pilih --</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Ibu</label>
                        <div class="col-sm-9">
                            <input type="text" name="nama_ibu" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">No. HP</label>
                        <div class="col-sm-9">
                            <input type="text" name="nomor_hp" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                            <a href="<?=base_url('pasien/list.php');?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </form>
                </div> <!-- /.card-style -->
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../_footer.php'; ?>
