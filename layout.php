<?php include 'dbconnection/db_connection.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Amaphil Admin Panel</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">📡 Devices</a>
    <a href="settings.php" class="<?= basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : '' ?>">⚙️ Settings</a>
    <a href="radius_auth_log.php" class="<?= basename($_SERVER['PHP_SELF']) == 'radius_auth_log.php' ? 'active' : '' ?>">📜 Auth Log</a>
  </div>
</body>
</html>
