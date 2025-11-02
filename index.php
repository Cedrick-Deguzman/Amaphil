<?php include 'layout.php'; ?>
<?php include 'devices.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <!-- <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="#" id="navDevices" class="active">📡 Devices</a>
    <a href="#" id="navSettings">⚙️ Settings</a>
  </div> -->
  <div class="main">
    <!-- Devices Section -->
    <div id="devicesSection">
      <h1>Authorized Devices</h1>
      <div class="card">
        <form method="POST" action="devices.php">
          <label><strong>Add New Device</strong></label>
          <input type="text" name="device_name" placeholder="Device Name" required>
          <input type="text" name="mac_address" placeholder="MAC Address (e.g. AA:BB:CC:DD:EE:FF)" required>
          <button type="submit">Add Device</button>
        </form>
      </div>

      <table>
        <tr><th>ID</th><th>Device Name</th><th>MAC Address</th><th>Date Added</th><th>Action</th></tr>
        <?php if ($devices && $devices->num_rows > 0): ?>
          <?php while($row = $devices->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['device_name']) ?></td>
              <td><?= htmlspecialchars($row['mac_address']) ?></td>
              <td><?= htmlspecialchars($row['created_at']) ?></td>
              <td><a class="delete" href="devices.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this device?');">🗑 Delete</a></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" style="text-align:center;">No devices found.</td></tr>
        <?php endif; ?>
      </table>
    </div>

    <!-- Settings Section -->
    <div id="settingsSection" style="display:none;">
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
</body>
</html>
