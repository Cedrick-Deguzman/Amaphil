<?php
include_once 'cors.php';

$limit = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$query = "
  SELECT username, nasipaddress, acctstarttime, acctstoptime, acctsessiontime, acctinputoctets, acctoutputoctets
  FROM radacct
  ORDER BY acctstarttime DESC
  LIMIT $limit OFFSET $offset
";
$result = $conn->query($query);

$logs = [];
while ($row = $result->fetch_assoc()) {
  $logs[] = $row;
}

$totalQuery = $conn->query("SELECT COUNT(*) AS total FROM radacct");
$totalRows = $totalQuery->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

header('Content-Type: application/json');
echo json_encode(['logs' => $logs, 'totalPages' => $totalPages]);
