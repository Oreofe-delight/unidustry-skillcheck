<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$code = $data['code'] ?? '';
$language = $data['language'] ?? 'python';
$challenge_id = $data['challenge_id'] ?? 0;

// Create temporary file
$temp_dir = sys_get_temp_dir();
$temp_file = $temp_dir . '/code_' . uniqid();

$output = '';
$error = '';

switch($language) {
    case 'python':
        file_put_contents($temp_file . '.py', $code);
        $output = shell_exec("python " . escapeshellarg($temp_file . '.py') . " 2>&1");
        break;
        
    case 'javascript':
        file_put_contents($temp_file . '.js', $code);
        $output = shell_exec("node " . escapeshellarg($temp_file . '.js') . " 2>&1");
        break;
        
    case 'php':
        file_put_contents($temp_file . '.php', $code);
        $output = shell_exec("php " . escapeshellarg($temp_file . '.php') . " 2>&1");
        break;
        
    default:
        $error = "Unsupported language";
}

// Cleanup
@unlink($temp_file . '.py');
@unlink($temp_file . '.js');
@unlink($temp_file . '.php');

if($error) {
    echo json_encode(['success' => false, 'error' => $error]);
} else {
    echo json_encode(['success' => true, 'output' => trim($output)]);
}
?>