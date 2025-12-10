<?php
require_once "../_config/config.php";
if(isset($_SESSION['user'])) {
    echo "<script>window.location='".base_url()."'</script>";
} else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login - Rumah Sakit Buana Husada</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url('_assets/css/bootstrap.min.css');?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="<?=base_url('_assets/css/custom.css');?>" rel="stylesheet">
</head>
<body>
    <div id="wrapper" style="min-height:100vh; display:flex; align-items:center; justify-content:center;">
        <div class="container">
            <div class="row" style="width:100%;">
                <div class="col-xs-12" style="max-width:420px; margin:0 auto;">
                    <div class="card-style">
                                <?php
                                if(isset($_POST['login'])) {
                                    $user = trim(mysqli_real_escape_string($con, $_POST['user']));
                                    $pass = sha1(trim(mysqli_real_escape_string($con, $_POST['pass'])));
                                    $sql_login = mysqli_query($con, "SELECT * FROM akun_petugas WHERE username = '$user' AND password = '$pass'") or die(mysqli_error($con));
                                    if(mysqli_num_rows($sql_login) > 0) {
                                        $_SESSION['user'] = $user;
                                        echo "<script>window.location='".base_url()."'</script>";
                                    } else {
                                        ?>
                                        <div class="alert alert-danger alert-dismissable" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="close">&times;</button>
                                            <i class="fa fa-exclamation-circle"></i>
                                            <strong> Login gagal!</strong> Username atau password salah.
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                <form action="" method="post" class="navbar-form">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input type="text" name="user" class="form-control" placeholder="Username" required autofocus>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:10px;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            <input type="password" name="pass" class="form-control" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:15px;">
                        <input type="submit" name="login" class="btn btn-primary btn-block" value="Login">
                    </div>
                </form>
                </div> <!-- /.card-style -->
            </div>
        </div>
    </div>
    <script src="<?=base_url('_assets/js/jquery.js');?>"></script>
    <script src="<?=base_url('_assets/js/bootstrap.min.js');?>"></script>
</body>
</html>
<?php
}
?>