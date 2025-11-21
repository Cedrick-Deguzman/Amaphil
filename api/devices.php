<?php
include_once 'cors.php';

// Fetch current shared secret
$current_secret = "";
$result = $conn->query("SELECT password FROM radius_secret ORDER BY id DESC LIMIT 1");
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $current_secret = $row['password'];
}

// ---- GET: fetch devices ----
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $devices = [];
    $result = $conn->query("SELECT * FROM authorized_devices ORDER BY created_at DESC");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $devices[] = $row;
        }
    }
    echo json_encode($devices);
    exit;
}

// ---- POST: add new device ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    if (!isset($input['device_name']) || !isset($input['mac_address'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    $name = $conn->real_escape_string($input['device_name']);
    $mac = strtoupper($conn->real_escape_string($input['mac_address']));
    $shared_password = $current_secret ?: $mac;

    $conn->query("INSERT INTO authorized_devices (device_name, mac_address) VALUES ('$name', '$mac')");

    $stmt = $conn->prepare("INSERT INTO radcheck (username, attribute, op, value) VALUES (?, 'Cleartext-Password', ':=', ?)");
    $stmt->bind_param("ss", $mac, $shared_password);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Device added successfully']);
    exit;
}

// ---- DELETE: remove device ----
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid device ID']);
        exit;
    }

    $result = $conn->query("SELECT mac_address FROM authorized_devices WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $device = $result->fetch_assoc();
        $mac = $device['mac_address'];
        $conn->query("DELETE FROM authorized_devices WHERE id = $id");
        $conn->query("DELETE FROM radcheck WHERE username = '$mac'");
        echo json_encode(['success' => true, 'message' => 'Device deleted successfully']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Device not found']);
    }
    exit;
}

// Default if unknown method
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
exit;
?>
