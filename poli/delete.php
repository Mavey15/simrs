<?php
require_once __DIR__ . '/../_config/config.php';

// Check session
if(!isset($_SESSION['user'])) {
    header('Location: ' . base_url('auth/login.php'));
    exit;
}

$id = isset($_GET['id']) ? trim($_GET['id']) : '';
if($id === '') {
    header('Location: ' . base_url('poli/list.php'));
    exit;
}

$id_esc = mysqli_real_escape_string($con, $id);
$sql = "DELETE FROM poliklinik WHERE id_poli = '$id_esc'";
mysqli_query($con, $sql);

header('Location: ' . base_url('poli/list.php'));
exit;
