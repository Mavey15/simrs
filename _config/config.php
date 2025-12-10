<?php
//set default timezone
date_default_timezone_set('Asia/Jakarta');

session_start();
//koneksi
$con = mysqli_connect('localhost', 'root', '', 'simrs');
if(mysqli_connect_errno()) {
    echo mysqli_connect_errno();
}

//fungsi base url
function base_url($url = null) {
    $base_url = "http://localhost/simrs";
    if($url != null) {
        return $base_url."/".$url;
    } else{
        return $base_url;
    }
}
?>