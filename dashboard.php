<?php
require_once '_config/config.php';

// Check session
if(!isset($_SESSION['user'])) {
    header('Location: ' . base_url('auth/login.php'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - SIMRS</title>
    <link href="<?=base_url('_assets/css/bootstrap.min.css');?>" rel="stylesheet">
    <style>
        .dashboard-card {
            padding: 30px;
            margin: 20px 0;
            border-radius: 5px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            text-align: center;
        }
        .dashboard-card h3 {
            margin-top: 0;
        }
        .dashboard-card a {
            display: inline-block;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-default" style="margin-bottom:20px;">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?=base_url();?>">SIMRS</a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="<?=base_url('auth/logout.php');?>">Logout (<?=$_SESSION['user'];?>)</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Selamat Datang di SIMRS</h1>
                <p>Sistem Informasi Manajemen Rumah Sakit</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="dashboard-card">
                    <h3><i class="fa fa-user" style="font-size:32px;"></i></h3>
                    <h3>Data Pasien</h3>
                    <p>Kelola data pasien, tambah, ubah, atau hapus data pasien.</p>
                    <a href="<?=base_url('pasien/list.php');?>" class="btn btn-primary">Buka</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dashboard-card">
                    <h3><i class="fa fa-stethoscope" style="font-size:32px;"></i></h3>
                    <h3>Data Dokter</h3>
                    <p>Kelola data dokter dan jadwal praktik.</p>
                    <a href="<?=base_url('dokter/list.php');?>" class="btn btn-primary">Buka</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dashboard-card">
                    <h3><i class="fa fa-calendar" style="font-size:32px;"></i></h3>
                    <h3>Jadwal Praktik</h3>
                    <p>Kelola jadwal praktik dokter.</p>
                    <a href="#" class="btn btn-primary" disabled>Segera Hadir</a>
                </div>
            </div>
        </div>
    </div>

    <script src="<?=base_url('_assets/js/jquery.js');?>"></script>
    <script src="<?=base_url('_assets/js/bootstrap.min.js');?>"></script>
</body>
</html>
