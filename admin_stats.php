<?php
$conn = new mysqli("localhost", "root", "", "url_db");

// IP yang diizinkan
$allowed_ips = ['127.0.0.1', '::1', '192.168.1.6']; // ganti dengan ip kalian
$client_ip = $_SERVER['REMOTE_ADDR'];
if (!in_array($client_ip, $allowed_ips)) {
    http_response_code(403);
    die("❌ Akses ditolak");
}

if (isset($_POST['reset'])) {
    $conn->query("DELETE FROM short_links");
    $conn->query("ALTER TABLE short_links AUTO_INCREMENT = 1");

    echo "<script>alert('Semua data berhasil direset!'); window.location.href = 'admin_stats.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - Statistics</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f8fafc;
      margin: 0;
      padding: 20px;
      color: #333;
    }

    h2 {
      text-align: center;
      color: #1e3a8a;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background: white;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      border-radius: 10px;
      overflow: hidden;
    }

    th, td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #e2e8f0;
    }

    th {
      background-color: #1e40af;
      color: white;
    }

    tr:hover {
      background-color: #f1f5f9;
    }

    .section {
      margin-bottom: 40px;
    }

    .ip-info {
      background: #fef3c7;
      padding: 10px;
      border-radius: 8px;
      margin: 0 auto;
      text-align: center;
      width: fit-content;
      font-size: 14px;
      color: #92400e;
    }
  </style>
</head>
<body>

  <h2>📊 Admin Statistics Panel</h2>
  <div class="ip-info">IP: <?= $client_ip ?></div>

  <div class="section">
    <h3>🔗 Data Link</h3>
    <table>
      <tr>
        <th>Short Code</th>
        <th>Original URL</th>
        <th>Clicks</th>
        <th>Created At</th>
      </tr>
      <?php
      $links = $conn->query("SELECT * FROM short_links ORDER BY created_at DESC");
      while ($row = $links->fetch_assoc()):
      ?>
      <tr>
        <td><?= htmlspecialchars($row['short_code']) ?></td>
        <td><a href="<?= htmlspecialchars($row['original_url']) ?>" target="_blank"><?= htmlspecialchars($row['original_url']) ?></a></td>
        <td><?= $row['clicks'] ?></td>
        <td><?= $row['created_at'] ?></td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>

	<form method="post" onsubmit="return confirm('Yakin ingin mereset semua data?');" style="margin-bottom: 20px;">
	<button type="submit" name="reset" style="padding: 10px 20px; background-color: red; color: white; border: none; border-radius: 8px; cursor: pointer;">
    🔄 Reset Semua Data
    </button>
    </form>

</body>
</html>
