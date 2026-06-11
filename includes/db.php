<?php

// ============================================
// PRODUCTION DATABASE SETTINGS (InfinityFree)
// ============================================

// !!! REPLACE THESE WITH YOUR ACTUAL INFINITYFREE CREDENTIALS !!!
com
$host = "sql308.infinityfree.com";        // Find this in vPanel (e.g., sql123.epizy.com)
$user = "if0_42156675";            // Your account username (from vPanel)
$password = "Skillcheck2026"; // Your InfinityFree account password
$database = "if0_42156675_skillcheck_dbg"; // Full database name (prefix + your db name)

// ============================================
// DO NOT CHANGE ANYTHING BELOW THIS LINE
// ============================================

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

// Secure session configuration and initialization
if (session_status() == PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => true,        // InfinityFree has HTTPS
        'cookie_samesite' => 'Lax',
        'use_only_cookies' => true,
    ]);
}

// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// XSS Escaping Helper
if (!function_exists('h')) {
    function h($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}
?>