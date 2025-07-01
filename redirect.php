<?php
$conn = new mysqli("localhost", "root", "", "url_db");

if (isset($_GET['c'])) {
    $code = $conn->real_escape_string($_GET['c']);
    $result = $conn->query("SELECT original_url FROM short_links WHERE short_code = '$code'");

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        $conn->query("UPDATE short_links SET clicks = clicks + 1 WHERE short_code = '$code'");

		$check = $conn->prepare("SELECT id FROM access_log WHERE short_code = ? AND ip_address = ?");
		$check->bind_param("ss", $shortCode, $ip);
		$check->execute();
		$result = $check->get_result();

	if ($result->num_rows === 0) {
		$stmt = $conn->prepare("INSERT INTO access_log (short_code, ip_address, user_agent) VALUES (?, ?, ?)");
		$stmt->bind_param("sss", $shortCode, $ip, $agent);
		$stmt->execute();
	}

        header("Location: " . $data['original_url']);
        exit;
    } else {
        echo "Link tidak ditemukan!";
    }
}
?>