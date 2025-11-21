<?php
include_once 'cors.php';
include '../dbconnection/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['radius_secret']) || empty(trim($input['radius_secret']))) {
        echo json_encode(["success" => false, "message" => "Missing or empty radius_secret."]);
        exit;
    }

    $newSecret = trim($input['radius_secret']);

    $conn->begin_transaction();

    try {
        $conn->query("DELETE FROM radius_secret");

        $stmt = $conn->prepare("INSERT INTO radius_secret (password) VALUES (?)");
        $stmt->bind_param("s", $newSecret);
        $stmt->execute();
        $stmt->close();

        $stmt2 = $conn->prepare("UPDATE radcheck SET value = ? WHERE attribute = 'Cleartext-Password'");
        $stmt2->bind_param("s", $newSecret);
        $stmt2->execute();
        $stmt2->close();

        $conn->commit();

        echo json_encode(["success" => true, "message" => "✅ Shared RADIUS Password updated successfully."]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "❌ Error: " . $e->getMessage()]);
    }

    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method. Use POST."]);
}
?>
