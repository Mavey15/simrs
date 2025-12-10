<?php
require_once __DIR__ . '/../_config/config.php';

// Check session
if(!isset($_SESSION['user'])) {
    header('Location: ' . base_url('auth/login.php'));
    exit;
}

$error = '';
$success = '';
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if($id === '') {
    header('Location: ' . base_url('pasien/list.php'));
    exit;
}

// Get data
$id_esc = mysqli_real_escape_string($con, $id);
$sql = "SELECT * FROM pasien WHERE nomor_rm = '$id_esc'";
$result = mysqli_query($con, $sql) or die(mysqli_error($con));
$row = mysqli_fetch_assoc($result);

if(!$row) {
    header('Location: ' . base_url('pasien/list.php'));
    exit;
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

    if($nama_pasien === '' || $nik === '') {
        $error = 'Nama dan NIK wajib diisi.';
    } else {
        // Check for duplicate NIK (excluding current record)
        $nik_check_esc = mysqli_real_escape_string($con, $nik);
        $nik_check_sql = "SELECT nomor_rm FROM pasien WHERE nik = '$nik_check_esc' AND nomor_rm != '$id_esc' LIMIT 1";
        $nik_check_result = mysqli_query($con, $nik_check_sql);
        $nik_exists = mysqli_num_rows($nik_check_result) > 0;
        
        if($nik_exists) {
            $error = 'NIK ini sudah terdaftar untuk pasien lain.';
        } else {
            $nama_pasien_esc = mysqli_real_escape_string($con, $nama_pasien);
            $nik_esc = mysqli_real_escape_string($con, $nik);
            $alamat_esc = mysqli_real_escape_string($con, $alamat);
            $pekerjaan_esc = mysqli_real_escape_string($con, $pekerjaan);
            $agama_esc = mysqli_real_escape_string($con, $agama);
            $nama_ibu_esc = mysqli_real_escape_string($con, $nama_ibu);
            $nomor_hp_esc = mysqli_real_escape_string($con, $nomor_hp);

            $sql = "UPDATE pasien SET 
                    nama_pasien = '$nama_pasien_esc',
                    nik = '$nik_esc',
                    alamat = '$alamat_esc',
                    tanggal_lahir = '$tanggal_lahir',
                    umur = '$umur',
                    jenis_kelamin = '$jenis_kelamin',
                    pekerjaan = '$pekerjaan_esc',
                    agama = '$agama_esc',
                    nama_ibu = '$nama_ibu_esc',
                    nomor_hp = '$nomor_hp_esc'
                    WHERE nomor_rm = '$id_esc'";

            if(mysqli_query($con, $sql)) {
                $success = 'Data pasien berhasil diperbarui.';
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
                <h2><i class="fa fa-user-injured"></i> Edit Data Pasien</h2>

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
                            <input type="text" class="form-control" value="<?=htmlspecialchars($row['nomor_rm']);?>" disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Pasien *</label>
                        <div class="col-sm-9">
                            <input type="text" name="nama_pasien" class="form-control" value="<?=htmlspecialchars($row['nama_pasien']);?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">NIK *</label>
                        <div class="col-sm-9">
                            <input type="text" name="nik" class="form-control" value="<?=htmlspecialchars($row['nik']);?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Alamat</label>
                        <div class="col-sm-9">
                            <textarea name="alamat" class="form-control" rows="3"><?=htmlspecialchars($row['alamat']);?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tanggal Lahir</label>
                        <div class="col-sm-9">
                            <input type="date" name="tanggal_lahir" class="form-control" value="<?=htmlspecialchars($row['tanggal_lahir']);?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Umur</label>
                        <div class="col-sm-9">
                            <input type="number" name="umur" class="form-control" value="<?=htmlspecialchars($row['umur']);?>" min="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Jenis Kelamin</label>
                        <div class="col-sm-9">
                            <select name="jenis_kelamin" class="form-control">
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki" <?=($row['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '');?>>Laki-laki</option>
                                <option value="Perempuan" <?=($row['jenis_kelamin'] == 'Perempuan' ? 'selected' : '');?>>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Pekerjaan</label>
                        <div class="col-sm-9">
                            <input type="text" name="pekerjaan" class="form-control" value="<?=htmlspecialchars($row['pekerjaan']);?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Agama</label>
                        <div class="col-sm-9">
                            <select name="agama" class="form-control">
                                <option value="">-- Pilih --</option>
                                <option value="Islam" <?=($row['agama'] == 'Islam' ? 'selected' : '');?>>Islam</option>
                                <option value="Kristen" <?=($row['agama'] == 'Kristen' ? 'selected' : '');?>>Kristen</option>
                                <option value="Katolik" <?=($row['agama'] == 'Katolik' ? 'selected' : '');?>>Katolik</option>
                                <option value="Hindu" <?=($row['agama'] == 'Hindu' ? 'selected' : '');?>>Hindu</option>
                                <option value="Buddha" <?=($row['agama'] == 'Buddha' ? 'selected' : '');?>>Buddha</option>
                                <option value="Konghucu" <?=($row['agama'] == 'Konghucu' ? 'selected' : '');?>>Konghucu</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Ibu</label>
                        <div class="col-sm-9">
                            <input type="text" name="nama_ibu" class="form-control" value="<?=htmlspecialchars($row['nama_ibu']);?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">No. HP</label>
                        <div class="col-sm-9">
                            <input type="text" name="nomor_hp" class="form-control" value="<?=htmlspecialchars($row['nomor_hp']);?>">
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
