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
    header('Location: ' . base_url('pendaftaran/list.php'));
    exit;
}

// Get data
$id_esc = mysqli_real_escape_string($con, $id);
$sql = "SELECT * FROM pendaftaran WHERE id_daftar = '$id_esc'";
$result = mysqli_query($con, $sql) or die(mysqli_error($con));
$row = mysqli_fetch_assoc($result);

if(!$row) {
    header('Location: ' . base_url('pendaftaran/list.php'));
    exit;
}

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

            $sql = "UPDATE pendaftaran SET 
                    nomor_rm = '$nomor_rm_esc',
                    tanggal_masuk = '$tanggal_masuk',
                    cara_bayar = '$cara_bayar_esc',
                    id_dokter = '$id_dokter_esc',
                    id_poli = '$id_poli_esc'
                    WHERE id_daftar = '$id_esc'";

            if(mysqli_query($con, $sql)) {
                $success = 'Data pendaftaran berhasil diperbarui.';
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
                <h2><i class="fa fa-calendar-check"></i> Edit Data Pendaftaran</h2>

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
                        <label class="col-sm-3 control-label">ID Daftar</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" value="<?=htmlspecialchars($row['id_daftar']);?>" disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">No. RM Pasien *</label>
                        <div class="col-sm-9">
                            <select name="nomor_rm" class="form-control" required>
                                <option value="">-- Pilih Pasien --</option>
                                <?php while($p_row = mysqli_fetch_assoc($pasien_result)): ?>
                                    <option value="<?=htmlspecialchars($p_row['nomor_rm']);?>" <?=($p_row['nomor_rm'] == $row['nomor_rm'] ? 'selected' : '');?>>
                                        <?=htmlspecialchars($p_row['nomor_rm'].' - '.$p_row['nama_pasien']);?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tanggal Masuk *</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" name="tanggal_masuk" class="form-control" value="<?=htmlspecialchars($row['tanggal_masuk']);?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Cara Bayar *</label>
                        <div class="col-sm-9">
                            <select name="cara_bayar" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="Tunai" <?=($row['cara_bayar'] == 'Tunai' ? 'selected' : '');?>>Tunai</option>
                                <option value="Asuransi" <?=($row['cara_bayar'] == 'Asuransi' ? 'selected' : '');?>>Asuransi</option>
                                <option value="BPJS" <?=($row['cara_bayar'] == 'BPJS' ? 'selected' : '');?>>BPJS</option>
                                <option value="Cicilan" <?=($row['cara_bayar'] == 'Cicilan' ? 'selected' : '');?>>Cicilan</option>
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
                                while($d_row = mysqli_fetch_assoc($dokter_result)): ?>
                                    <option value="<?=htmlspecialchars($d_row['id_dokter']);?>" <?=($d_row['id_dokter'] == $row['id_dokter'] ? 'selected' : '');?>>
                                        <?=htmlspecialchars($d_row['nama_dokter']);?>
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
                                while($po_row = mysqli_fetch_assoc($poli_result)): ?>
                                    <option value="<?=htmlspecialchars($po_row['id_poli']);?>" <?=($po_row['id_poli'] == $row['id_poli'] ? 'selected' : '');?>>
                                        <?=htmlspecialchars($po_row['nama_poli']);?>
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

<?php require_once __DIR__ . '/../_footer.php'; ?>
