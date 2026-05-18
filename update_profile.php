<?php
include 'includes/session.php';
include 'includes/db.php';

$userId = $_SESSION['user_id'];
$message = "";

// Get user data
$stmt = $conn->prepare("SELECT * FROM contractor WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Success message after update
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $message = "✅ प्रोफ़ाइल सफलतापूर्वक अपडेट हो गई!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $fieldsToUpdate = [];
    $params = [];
    $paramTypes = "";

    if (!empty($name)) {
        $fieldsToUpdate[] = "name=?";
        $params[] = $name;
        $paramTypes .= "s";
    }

    if (!empty($mobile)) {
        $fieldsToUpdate[] = "mobile=?";
        $params[] = $mobile;
        $paramTypes .= "s";
    }

    // Password change logic
    if (!empty($oldPassword) && !empty($newPassword) && !empty($confirmPassword)) {
        if (password_verify($oldPassword, $user['password'])) {
            if ($newPassword === $confirmPassword && strlen($newPassword) >= 6) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $fieldsToUpdate[] = "password=?";
                $params[] = $hashedPassword;
                $paramTypes .= "s";
            } else {
                $message = "❌ नया पासवर्ड मेल नहीं खा रहा या छोटा है।";
            }
        } else {
            $message = "❌ पुराना पासवर्ड गलत है।";
        }
    }

    if (empty($message) && count($fieldsToUpdate) > 0) {
        $paramTypes .= "i";
        $params[] = $userId;
        $updateSql = "UPDATE contractor SET " . implode(", ", $fieldsToUpdate) . " WHERE id=?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param($paramTypes, ...$params);

        if ($updateStmt->execute()) {
            header("Location: update_profile.php?success=1");
            exit;
        } else {
            $message = "❌ कुछ गलत हुआ।";
        }
    } elseif (empty($message)) {
        $message = "⚠️ कोई बदलाव नहीं किया गया।";
    }
}
?>

<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="UTF-8">
  <title>📝 प्रोफ़ाइल अपडेट करें</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(120deg, #f2f9ff, #e1f5fe);
      margin: 0;
      padding: 20px;
    }

    .container {
      max-width: 500px;
      margin: 60px auto;
      background: #fff;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      position: relative;
      animation: fadeIn 0.8s ease;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(30px);}
      to {opacity: 1; transform: translateY(0);}
    }

    h2 {
      text-align: center;
      color: #444;
      margin-bottom: 25px;
    }

    .form-group {
      margin-bottom: 16px;
    }

    label {
      display: block;
      font-weight: bold;
      margin-bottom: 6px;
    }

    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 10px;
      border-radius: 10px;
      border: 1px solid #ccc;
      font-size: 15px;
      transition: all 0.3s ease;
    }

    input:focus {
      border-color: #3498db;
      outline: none;
    }

    button {
      background: #3498db;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 25px;
      font-size: 16px;
      width: 100%;
      margin-top: 10px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: #2980b9;
    }

    .msg {
      text-align: center;
      margin-top: 10px;
      color: green;
    }

    .error {
      color: red;
    }

    .profile-pic {
      text-align: center;
      margin-bottom: 15px;
    }

    .profile-pic img {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      object-fit: cover;
    }

    .top-bar {
      position: absolute;
      top: 10px;
      left: 10px;
    }

    .top-bar a {
      text-decoration: none;
      background: #f0f0f0;
      padding: 8px 14px;
      border-radius: 10px;
      color: #444;
      font-weight: bold;
      transition: background 0.3s;
    }

    .top-bar a:hover {
      background: #dfe6e9;
    }

    .password-toggle {
      margin-top: 10px;
      text-align: center;
    }

    .password-popup {
      display: none;
      margin-top: 15px;
      border-top: 1px solid #ccc;
      padding-top: 10px;
    }

    .check-btn {
      background: #2ecc71;
      margin-top: 5px;
    }

    .verify-msg {
      font-size: 14px;
      margin-top: 5px;
    }

    @media (max-width: 600px) {
      .container {
        padding: 20px;
        margin-top: 40px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="top-bar">
    <a href="contractor_dashboard.php">⬅️ डैशबोर्ड</a>
  </div>

  <h2>📝 प्रोफ़ाइल अपडेट करें</h2>

  <?php if (!empty($message)) echo '<div class="msg ' . (str_starts_with($message, '❌') ? 'error' : '') . '">' . $message . '</div>'; ?>

  <div class="profile-pic">
    <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>" alt="Profile">
  </div>

  <form method="POST" id="profileForm">
    <div class="form-group">
      <label>👤 नाम</label>
      <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>">
    </div>
    <div class="form-group">
      <label>📞 मोबाइल</label>
      <input type="text" name="mobile" value="<?= htmlspecialchars($user['mobile']) ?>">
    </div>

    <div class="password-toggle">
      <button type="button" onclick="togglePasswordPopup()">🔒 पासवर्ड बदलें</button>
    </div>

    <div class="password-popup" id="passwordPopup">
      <div class="form-group">
        <label>🔑 पुराना पासवर्ड</label>
        <input type="password" id="oldPassword" name="old_password">
        <button type="button" class="check-btn" onclick="verifyOldPassword()">✅ सत्यापित करें</button>
        <div class="verify-msg" id="verifyMsg"></div>
      </div>
      <div class="form-group" id="newPasswordFields" style="display:none;">
        <label>🆕 नया पासवर्ड</label>
        <input type="password" name="new_password">
        <label>🔁 कन्फर्म पासवर्ड</label>
        <input type="password" name="confirm_password">
      </div>
    </div>

    <button type="submit">✅ अपडेट करें</button>
  </form>
</div>

<script>
function togglePasswordPopup() {
  const popup = document.getElementById('passwordPopup');
  popup.style.display = popup.style.display === 'block' ? 'none' : 'block';
}

function verifyOldPassword() {
  const input = document.getElementById("oldPassword");
  const msg = document.getElementById("verifyMsg");
  const newFields = document.getElementById("newPasswordFields");
  const entered = input.value;
  const real = <?= json_encode($user['password']) ?>;

  fetch('verify_password.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `entered=${encodeURIComponent(entered)}&stored=${encodeURIComponent(real)}`
  })
  .then(res => res.text())
  .then(data => {
    if (data === 'match') {
      msg.innerHTML = "✅ पासवर्ड सही है";
      msg.style.color = "green";
      newFields.style.display = "block";
    } else {
      msg.innerHTML = "❌ पासवर्ड गलत है";
      msg.style.color = "red";
      newFields.style.display = "none";
    }
  });
}
</script>

</body>
</html>
