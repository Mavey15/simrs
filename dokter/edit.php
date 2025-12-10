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
    header('Location: ' . base_url('dokter/list.php'));
    exit;
}

$id_esc = mysqli_real_escape_string($con, $id);
$sql = "SELECT * FROM dokter WHERE id_dokter = '$id_esc'";
$result = mysqli_query($con, $sql) or die(mysqli_error($con));
$row = mysqli_fetch_assoc($result);

if(!$row) {
    header('Location: ' . base_url('dokter/list.php'));
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_dokter = trim($_POST['nama_dokter'] ?? '');
    $spesialis = trim($_POST['spesialis'] ?? '');

    if($nama_dokter === '') {
        $error = 'Nama Dokter wajib diisi.';
    } else {
        $nama_esc = mysqli_real_escape_string($con, $nama_dokter);
        $spesialis_esc = mysqli_real_escape_string($con, $spesialis);

        $sqlu = "UPDATE dokter SET nama_dokter = '$nama_esc', spesialis = '$spesialis_esc' WHERE id_dokter = '$id_esc'";
        if(mysqli_query($con, $sqlu)) {
            $success = 'Data dokter berhasil diperbarui.';
            header('Refresh: 2; url=' . base_url('dokter/list.php'));
        } else {
            $error = 'Error: ' . mysqli_error($con);
        }
    }
}
?>
<?php require_once __DIR__ . '/../_header.php'; ?>

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="card-style">
                <h2><i class="fa fa-user-md"></i> Edit Data Dokter</h2>

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
                        <label class="col-sm-3 control-label">ID Dokter</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" value="<?=htmlspecialchars($row['id_dokter']);?>" disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Dokter *</label>
                        <div class="col-sm-9">
                            <input type="text" name="nama_dokter" class="form-control" value="<?=htmlspecialchars($row['nama_dokter']);?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Spesialis</label>
                        <div class="col-sm-9">
                            <input type="text" name="spesialis" class="form-control" value="<?=htmlspecialchars($row['spesialis']);?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                            <a href="<?=base_url('dokter/list.php');?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </form>
                </div> <!-- /.card-style -->
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../_footer.php'; ?>