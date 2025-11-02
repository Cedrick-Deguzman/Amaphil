<?php
include 'layout.php';

// Set how many records per page
$limit = 15;

// Get the current page number from the query string (default = 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Calculate the OFFSET for SQL
$offset = ($page - 1) * $limit;

// Count total records
$countResult = $conn->query("SELECT COUNT(*) AS total FROM radpostauth");
$totalRows = $countResult->fetch_assoc()['total'];

// Calculate total pages
$totalPages = ceil($totalRows / $limit);

// Fetch limited data
$sql = "SELECT username, reply, authdate 
        FROM radpostauth 
        ORDER BY authdate DESC 
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);
?>

<div class="main">
  <h1>FreeRADIUS Authentication Log</h1>

  <table>
    <tr>
      <th>Username</th>
      <th>Reply</th>
      <th>Date</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['username']) ?></td>
          <td class="<?= $row['reply'] == 'Access-Accept' ? 'success' : 'fail' ?>">
            <?= htmlspecialchars($row['reply']) ?>
          </td>
          <td><?= htmlspecialchars($row['authdate']) ?></td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="3">No records found</td></tr>
    <?php endif; ?>
  </table>

  <!-- Pagination Links -->
  <div class="pagination">
    <?php if ($page > 1): ?>
      <a href="?page=<?= $page - 1 ?>">&laquo; Prev</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
      <a href="?page=<?= $page + 1 ?>">Next &raquo;</a>
    <?php endif; ?>
  </div>
</div>

<style>
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    padding: 8px 12px;
    border-bottom: 1px solid #ddd;
  }
  .success { color: green; }
  .fail { color: red; }

  .pagination {
    margin-top: 20px;
    text-align: center;
  }
  .pagination a {
    display: inline-block;
    padding: 6px 12px;
    margin: 0 3px;
    border: 1px solid #ddd;
    border-radius: 4px;
    color: #333;
    text-decoration: none;
  }
  .pagination a.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
  }
  .pagination a:hover {
    background-color: #0056b3;
    color: white;
  }
</style>
