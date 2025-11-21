<?php   
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // allow frontend calls
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

include '../dbconnection/db_connection.php';

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
?>