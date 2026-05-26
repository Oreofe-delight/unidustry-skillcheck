<?php
include_once __DIR__ . "/db.php";

if(!isset($_SESSION['user_id'])){
    $redirect_path = file_exists("login.php") ? "login.php" : "../login.php";
    header("Location: " . $redirect_path);
    exit();
}
?>