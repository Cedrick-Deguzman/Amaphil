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
<?php include 'layout.php'; ?>

<div class="main">
<!-- Settings Section -->
<div id="settingsSection" >
    <h1>Settings</h1>
    <div class="card">
    <form method="POST" action="settings.php">
        <label><strong>Shared RADIUS Password</strong></label>
        <div class="input-wrapper">
        <input type="password" name="radius_secret" id="radius_secret" placeholder="Enter new password" required>
        <button type="button" id="togglePass" class="eye-btn">👁</button>
        </div>
        <button type="submit" name="save_secret" onclick="return confirm('Update Shared RADIUS Password?');">Save Password</button>
    </form>
    </div>
    
</div>
</div>
<script>
const passInput = document.getElementById("radius_secret");
const toggleBtn = document.getElementById("togglePass");
let visible = false;

toggleBtn.addEventListener("click", () => {
  visible = !visible;
  passInput.type = visible ? "text" : "password";
  toggleBtn.style.color = visible ? "#3b82f6" : "#94a3b8";
});
</script>
