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

// Get registration data
$id_esc = mysqli_real_escape_string($con, $id);
$sql = "SELECT p.id_daftar, p.nomor_rm, p.tanggal_masuk, p.cara_bayar, p.id_dokter, p.id_poli,
               pas.nama_pasien, d.nama_dokter, po.nama_poli
        FROM pendaftaran p
        LEFT JOIN pasien pas ON p.nomor_rm = pas.nomor_rm
        LEFT JOIN dokter d ON p.id_dokter = d.id_dokter
        LEFT JOIN poliklinik po ON p.id_poli = po.id_poli
        WHERE p.id_daftar = '$id_esc'";
$result = mysqli_query($con, $sql) or die(mysqli_error($con));
$row = mysqli_fetch_assoc($result);

if(!$row) {
    header('Location: ' . base_url('pendaftaran/list.php'));
    exit;
}

// Handle confirmation
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    $del_sql = "DELETE FROM pendaftaran WHERE id_daftar = '$id_esc'";
    if(mysqli_query($con, $del_sql)) {
        header('Location: ' . base_url('pendaftaran/list.php?msg=deleted'));
    } else {
        $error = 'Error: ' . mysqli_error($con);
    }
    exit;
}
?>
<?php require_once __DIR__ . '/../_header.php'; ?>

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="card-style">
                <h2 style="color: #d9534f;"><i class="fa fa-trash-alt"></i> Konfirmasi Penghapusan Data Pendaftaran</h2>
                
                <?php if($error): ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?=htmlspecialchars($error);?>
                    </div>
                <?php endif; ?>

                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <strong>Data Pendaftaran yang Akan Dihapus</strong>
                    </div>
                    <div class="panel-body">
                        <table class="table table-condensed">
                            <tr>
                                <td><strong>ID Daftar</strong></td>
                                <td>: <?=htmlspecialchars($row['id_daftar']);?></td>
                            </tr>
                            <tr>
                                <td><strong>No. RM</strong></td>
                                <td>: <?=htmlspecialchars($row['nomor_rm']);?></td>
                            </tr>
                            <tr>
                                <td><strong>Nama Pasien</strong></td>
                                <td>: <?=htmlspecialchars($row['nama_pasien'] ?? '-');?></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Masuk</strong></td>
                                <td>: <?=htmlspecialchars($row['tanggal_masuk']);?></td>
                            </tr>
                            <tr>
                                <td><strong>Dokter</strong></td>
                                <td>: <?=htmlspecialchars($row['nama_dokter'] ?? '-');?></td>
                            </tr>
                            <tr>
                                <td><strong>Poli</strong></td>
                                <td>: <?=htmlspecialchars($row['nama_poli'] ?? '-');?></td>
                            </tr>
                            <tr>
                                <td><strong>Cara Bayar</strong></td>
                                <td>: <?=htmlspecialchars($row['cara_bayar']);?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <strong>⚠️ Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Semua data pendaftaran akan dihapus secara permanen.
                </div>

                <form method="post">
                    <button type="submit" name="confirm" value="yes" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data pendaftaran ini?');">
                        <i class="fa fa-trash-alt"></i> Hapus Data
                    </button>
                    <a href="<?=base_url('pendaftaran/list.php');?>" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </form>
                </div> <!-- /.card-style -->
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../_footer.php'; ?>
