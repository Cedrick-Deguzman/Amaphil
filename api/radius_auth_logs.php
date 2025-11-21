<?php
include_once 'cors.php';

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Count total
$countQuery = $conn->query("SELECT COUNT(*) AS total FROM radpostauth");
$totalRows = $countQuery->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch logs
$sql = "SELECT username, reply, authdate 
        FROM radpostauth 
        ORDER BY authdate DESC 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$logs = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
}

echo json_encode([
    "logs" => $logs,
    "totalPages" => $totalPages,
    "currentPage" => $page
]);
exit;
?>
