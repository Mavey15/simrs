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
    $id_poli = trim($_POST['id_poli'] ?? '');
    $nama_poli = trim($_POST['nama_poli'] ?? '');

    if($id_poli === '' || $nama_poli === '') {
        $error = 'ID Poli dan Nama Poli wajib diisi.';
    } else {
        $id_esc = mysqli_real_escape_string($con, $id_poli);
        $nama_esc = mysqli_real_escape_string($con, $nama_poli);

        $sql = "INSERT INTO poliklinik (id_poli, nama_poli) VALUES ('$id_esc', '$nama_esc')";
        if(mysqli_query($con, $sql)) {
            $success = 'Data poliklinik berhasil ditambahkan.';
            header('Refresh: 2; url=' . base_url('poli/list.php'));
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
                <h2><i class="fa fa-clinic-medical"></i> Tambah Poliklinik</h2>

                <?php if($error): ?>
                    <div class="alert alert-danger"><?=htmlspecialchars($error);?></div>
                <?php endif; ?>

                <?php if($success): ?>
                    <div class="alert alert-success"><?=htmlspecialchars($success);?></div>
                <?php endif; ?>

                <form method="post" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">ID Poli *</label>
                        <div class="col-sm-9">
                            <input type="text" name="id_poli" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Poli *</label>
                        <div class="col-sm-9">
                            <input type="text" name="nama_poli" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                            <a href="<?=base_url('poli/list.php');?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </form>
                </div> <!-- /.card-style -->
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../_footer.php'; ?>
