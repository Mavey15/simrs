<?php
require_once "_config/config.php";
if(isset($_SESSION['user'])) {
    // redirect to dashboard.php explicitly
    echo "<script>window.location='".base_url('dashboard.php')."'</script>";
} else {
     echo "<script>window.location='".base_url('auth/login.php')."'</script>";
}
?>