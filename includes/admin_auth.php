<?php

session_start();

include("../../includes/db.php");

if(!isset($_SESSION['user_id'])){

    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != 'admin'){

    header("Location: ../dashboard.php");
    exit();
}
?>