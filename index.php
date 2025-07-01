<?php
$conn = new mysqli("localhost", "root", "", "url_db");

function isValidUrl($url) {
    return filter_var($url, FILTER_VALIDATE_URL);
}

function generateUUID() {
    return bin2hex(random_bytes(4));
}

$shortURL = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['url'])) {
    $url = trim($_POST['url']);

    if (!isValidUrl($url)) {
        $error = "URL tidak valid!";
    } else {
        $shortCode = generateUUID();
        $stmt = $conn->prepare("INSERT INTO short_links (short_code, original_url) VALUES (?, ?)");
        $stmt->bind_param("ss", $shortCode, $url);
        $stmt->execute();
        $shortURL = "http://192.168.1.6/sl/redirect.php?c=" . $shortCode;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>URL Shortener</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f1f5f9;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
    }

    .container {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      text-align: center;
      width: 90%;
      max-width: 400px;
    }

    h2 {
      color: #1e40af;
      margin-bottom: 20px;
    }

    input[type="url"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
      margin-bottom: 15px;
    }

    button {
      background-color: #2563eb;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #1d4ed8;
    }

    .message {
      margin-top: 15px;
    }

    .message a {
      color: #1d4ed8;
      text-decoration: none;
    }

    .error {
      color: red;
    }

    .fade-out {
      animation: fadeOut 2s forwards;
    }

    @keyframes fadeOut {
      to {
        opacity: 0;
        visibility: hidden;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>URL Shortener</h2>

    <form method="post">
      <input type="url" name="url" placeholder="Masukkan URL" required>
      <button type="submit">Shorten</button>
    </form>

    <div id="result" class="message">
      <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
      <?php if (!empty($shortURL)) echo "<p>Short Link: <a href='$shortURL' target='_blank'>$shortURL</a></p>"; ?>
    </div>
  </div>

  <script>
    // ⛔ Cegah submit ulang saat refresh
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    // 🧼 Hapus hasil link setelah 7 detik
    const resultDiv = document.getElementById("result");
    if (resultDiv && resultDiv.innerText.trim() !== "") {
        setTimeout(() => {
            resultDiv.classList.add("fade-out");
        }, 7000);
    }
  </script>
</body>
</html>