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
    $id_dokter = trim($_POST['id_dokter'] ?? '');
    $nama_dokter = trim($_POST['nama_dokter'] ?? '');
    $spesialis = trim($_POST['spesialis'] ?? '');

    if($id_dokter === '' || $nama_dokter === '') {
        $error = 'ID Dokter dan Nama Dokter wajib diisi.';
    } else {
        $id_esc = mysqli_real_escape_string($con, $id_dokter);
        $nama_esc = mysqli_real_escape_string($con, $nama_dokter);
        $spesialis_esc = mysqli_real_escape_string($con, $spesialis);

        $sql = "INSERT INTO dokter (id_dokter, nama_dokter, spesialis) VALUES ('$id_esc', '$nama_esc', '$spesialis_esc')";
        if(mysqli_query($con, $sql)) {
            $success = 'Data dokter berhasil ditambahkan.';
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
                <h2><i class="fa fa-user-md"></i> Tambah Data Dokter</h2>

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
                        <label class="col-sm-3 control-label">ID Dokter *</label>
                        <div class="col-sm-9">
                            <input type="text" name="id_dokter" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Dokter *</label>
                        <div class="col-sm-9">
                            <input type="text" name="nama_dokter" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Spesialis</label>
                        <div class="col-sm-9">
                            <input type="text" name="spesialis" class="form-control">
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