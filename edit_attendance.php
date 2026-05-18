<?php
date_default_timezone_set("Asia/Kolkata");
include 'includes/session.php';
include 'includes/db.php';

if (!isset($_GET['id'])) {
  header("Location: view_attendance.php");
  exit;
}

$id = $_GET['id'];

// Fetch attendance record first
$sql = "SELECT a.*, l.name, l.salary_amount FROM attendance a 
        JOIN labours l ON a.labour_id = l.id 
        WHERE a.id = '$id'";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

if (!$data) {
  echo "रिकॉर्ड नहीं मिला!";
  exit;
}

$salary = $data['salary_amount'];
$date = $data['date'];
$labour_name = $data['name'];
$current_type = $data['type'];
$current_custom_amount = $data['custom_amount'];
$is_today = date('Y-m-d') === $date;

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
  $type = $_POST['type'];
  $custom_amount = $_POST['custom_amount'] !== "" ? $_POST['custom_amount'] : null;

  if ($type === 'full') {
    $custom_amount = null;
  }

  $update = $conn->prepare("UPDATE attendance SET type=?, status=?, custom_amount=? WHERE id=?");
  $status = 'updated';
  $update->bind_param("ssdi", $type, $status, $custom_amount, $id);
  $update->execute();

  echo "<script>
    alert('✅ उपस्थिति सफलतापूर्वक अपडेट की गई!');
    window.location.href='view_attendance.php';
  </script>";
  exit;
}

// Handle Delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
  if ($is_today) {
    mysqli_query($conn, "DELETE FROM attendance WHERE id = '$id'");
  } else {
    mysqli_query($conn, "UPDATE attendance SET is_removed = 1 WHERE id = '$id'");
  }

  echo "<script>
    alert('❌ उपस्थिति रिकॉर्ड हटाया गया!');
    window.location.href='view_attendance.php';
  </script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="UTF-8">
  <title>उपस्थिति संपादित करें</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f5f7fa;
      padding: 20px;
    }

    .container {
      background: white;
      max-width: 500px;
      margin: auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #2c3e50;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
    }

    input[type="number"], select {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      margin-top: 5px;
      border-radius: 8px;
      font-size: 14px;
    }

    button {
      margin-top: 20px;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      width: 100%;
      font-weight: bold;
      font-size: 15px;
    }

    .save-btn {
      background: #2ecc71;
      color: white;
    }

    .save-btn:hover {
      background: #27ae60;
    }

    .delete-btn {
      background: #e74c3c;
      color: white;
      margin-top: 10px;
    }

    .delete-btn:hover {
      background: #c0392b;
    }

    .custom-box {
      display: none;
    }

    .info {
      background: #ecf0f1;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 8px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>📝 उपस्थिति संपादन</h2>

    <div class="info">
      <strong>👷 मज़दूर:</strong> <?= htmlspecialchars($labour_name) ?><br>
      <strong>📅 तारीख:</strong> <?= date('d M Y', strtotime($date)) ?><br>
      <strong>💰 वेतन:</strong> ₹<?= $salary ?>
    </div>

    <form method="post" onsubmit="return confirm('क्या आप वाकई इसे अपडेट करना चाहते हैं?');">
      <label for="type">उपस्थिति प्रकार:</label>
      <select name="type" id="type" required onchange="handleTypeChange()">
        <option value="full" <?= $current_type == 'full' ? 'selected' : '' ?>>पूर्ण दिन</option>
        <option value="half" <?= $current_type == 'half' ? 'selected' : '' ?>>आधा दिन</option>
        <option value="custom" <?= $current_type == 'custom' ? 'selected' : '' ?>>कस्टम</option>
      </select>

      <div class="custom-box" id="customBox">
        <label for="custom_amount">वेतन राशि (₹):</label>
        <input type="number" name="custom_amount" id="custom_amount" step="0.01" min="0" value="<?= $current_custom_amount ?>">
      </div>

      <button type="submit" name="save" class="save-btn">💾 सहेजें</button>
    </form>

    <form method="post" onsubmit="return confirm('क्या आप इस उपस्थिति को हटाना चाहते हैं?');">
      <button type="submit" name="delete" class="delete-btn">🗑️ उपस्थिति हटाएं</button>
    </form>
  </div>

  <script>
    const type = document.getElementById("type");
    const customBox = document.getElementById("customBox");
    const customAmountInput = document.getElementById("custom_amount");
    const salary = <?= $salary ?>;

    function handleTypeChange() {
      if (type.value === "half") {
        customBox.style.display = "block";
        customAmountInput.value = (salary / 2).toFixed(2);
        customAmountInput.readOnly = true;
      } else if (type.value === "custom") {
        customBox.style.display = "block";
        customAmountInput.value = "";
        customAmountInput.readOnly = false;
      } else {
        customBox.style.display = "none";
        customAmountInput.value = "";
      }
    }

    window.onload = handleTypeChange;
  </script>
</body>
</html>
