<?php
require_once "_config/config.php";
if(!isset($_SESSION['user'])) {
    echo "<script>window.location='".base_url('auth/login')."'</script>";
} ?>   
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Aplikasi Rumah Sakit</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url('_assets/css/bootstrap.min.css');?>" rel="stylesheet">
    <link href="<?=base_url('_assets/css/simple-sidebar.css');?>" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="<?=base_url('_assets/css/custom.css');?>" rel="stylesheet">
</head>
<body>
   <div id="wrapper">
        <div id="sidebar-wrapper">
            <?php
            $req = $_SERVER['REQUEST_URI'];
            // Determine active section by checking URL segments
            $is_dashboard = (strpos($req, '/dashboard') !== false) || preg_match('#/simrs/?$#', $req) || preg_match('#/simrs/index\.php#', $req);
            $is_pasien = (strpos($req, '/pasien/') !== false);
            $is_dokter = (strpos($req, '/dokter/') !== false);
            $is_poli = (strpos($req, '/poli/') !== false) || (strpos($req, '/poliklinik') !== false);
            $is_pendaftaran = (strpos($req, '/pendaftaran/') !== false);
            ?>
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="<?=base_url();?>"><span class="text-primary"><b>Rumah Sakit</b></span></a>
                </li>
                <li class="<?= $is_dashboard ? 'active' : '';?>">
                    <a href="<?=base_url('dashboard')?>">Dashboard</a>
                </li>
                <li class="<?= $is_pasien ? 'active' : '';?>">
                    <a href="<?=base_url('pasien/list.php');?>">Data Pasien</a>
                </li>
                <li class="<?= $is_dokter ? 'active' : '';?>">
                    <a href="<?=base_url('dokter/list.php');?>">Data Dokter</a>
                </li>
                <li class="<?= $is_poli ? 'active' : '';?>">
                    <a href="<?=base_url('poli/list.php');?>">Data Poliklinik</a>
                </li>
                <li class="<?= $is_pendaftaran ? 'active' : '';?>">
                    <a href="<?=base_url('pendaftaran/list.php');?>">Data Pendaftaran</a>
                </li>
                <li>
                    <a href="#">Data Obat</a>
                </li>
                <li>
                    <a href="#">Rekam Medis</a>
                </li>
                <li>
                    <a href="<?=base_url('auth/logout.php')?>"><span class="text-danger">Logout</span></a>
                </li>
            </ul>
        </div>
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <a href="#" class="btn btn-default" id="menu-toggle" style="margin:10px 0;">
                    <i class="fa fa-bars"></i> Menu
                </a>
