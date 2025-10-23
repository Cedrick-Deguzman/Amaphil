<?php
include 'dbconnection/db_connection.php';

if (isset($_POST['save_secret'])) {
    $newSecret = $_POST['radius_secret'];

    $conn->query("DELETE FROM radius_secret");
    $stmt = $conn->prepare("INSERT INTO radius_secret (password) VALUES (?)");
    $stmt->bind_param("s", $newSecret);
    $stmt->execute();

    $stmt2 = $conn->prepare("UPDATE radcheck SET value = ? WHERE attribute = 'Cleartext-Password'");
    $stmt2->bind_param("s", $newSecret);
    $stmt2->execute();

    header("Location: index.php");
    exit;
}
?>
