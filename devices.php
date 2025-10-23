<?php
include 'dbconnection/db_connection.php';

// Fetch shared password
$current_secret = "";
$result = $conn->query("SELECT password FROM radius_secret ORDER BY id DESC LIMIT 1");
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $current_secret = $row['password'];
}

// Add new device
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['device_name']) && isset($_POST['mac_address'])) {
    $name = $_POST['device_name'];
    $mac = strtoupper($_POST['mac_address']);
    $shared_password = $current_secret ?: $mac;

    $conn->query("INSERT INTO authorized_devices (device_name, mac_address) VALUES ('$name', '$mac')");
    $stmt = $conn->prepare("INSERT INTO radcheck (username, attribute, op, value) VALUES (?, 'Cleartext-Password', ':=', ?)");
    $stmt->bind_param("ss", $mac, $shared_password);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

// Delete device
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $result = $conn->query("SELECT mac_address FROM authorized_devices WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $device = $result->fetch_assoc();
        $mac = $device['mac_address'];
        $conn->query("DELETE FROM authorized_devices WHERE id = $id");
        $conn->query("DELETE FROM radcheck WHERE username = '$mac'");
    }
    header("Location: index.php");
    exit;
}

// Get devices
$devices = $conn->query("SELECT * FROM authorized_devices ORDER BY created_at DESC");
?>
