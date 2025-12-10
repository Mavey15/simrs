<?php
require_once __DIR__ . '/../_config/config.php';

// Check session
if(!isset($_SESSION['user'])) {
    header('Location: ' . base_url('auth/login.php'));
    exit;
}

$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if($id === '') {
    header('Location: ' . base_url('dokter/list.php'));
    exit;
}

$id_esc = mysqli_real_escape_string($con, $id);
$sql = "DELETE FROM dokter WHERE id_dokter = '$id_esc'";

if(mysqli_query($con, $sql)) {
    header('Location: ' . base_url('dokter/list.php?msg=deleted'));
} else {
    echo "Error: " . mysqli_error($con);
}
?>