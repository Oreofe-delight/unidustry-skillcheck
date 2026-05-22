<?php

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

include("db.php");

if(
!isset($_SESSION['user_id'])
||
$_SESSION['role'] != 'admin'
){
    header("Location: ../login.php");
    exit();
}
?>