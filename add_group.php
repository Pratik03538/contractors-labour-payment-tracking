<?php
include 'includes/session.php';
include 'includes/db.php';

$contractor_id = $_SESSION['user_id'];
$labours = mysqli_query($conn, "SELECT * FROM labours WHERE contractor_id='$contractor_id'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $group_name = mysqli_real_escape_string($conn, $_POST['group_name']);
  $selected_labours = $_POST['labours'] ?? [];

  if (!empty($group_name)) {
    $insert = "INSERT INTO labour_groups (group_name, contractor_id) VALUES ('$group_name', '$contractor_id')";
    if (mysqli_query($conn, $insert)) {
      $group_id = mysqli_insert_id($conn);
      foreach ($selected_labours as $labour_id) {
        mysqli_query($conn, "INSERT INTO labour_group_members (group_id, labour_id) VALUES ('$group_id', '$labour_id')");
      }
      header("Location: groups.php");
      exit();
    } else {
      echo "त्रुटि: " . mysqli_error($conn);
    }
  } else {
    echo "ग्रुप का नाम खाली नहीं हो सकता।";
  }
}
?>

<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="UTF-8">
  <title>ग्रुप बनाएं</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
  * {
    box-sizing: border-box;
  }

  html, body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f4f4f4;
    min-height: 100vh;
  }

  body {
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .container {
    width: 100%;
    max-width: 800px;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  .flex-row {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
  }

  .left, .right {
    flex: 1;
    min-width: 300px;
  }

  .left input[type="text"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
  }

  .search-box input {
    width: 100%;
    padding: 8px 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    margin-bottom: 10px;
  }

  .labour-list {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px;
    background: #fafafa;
  }

  .labour-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
  }

  .labour-item input {
    margin-right: 10px;
  }

  button {
    padding: 12px 20px;
    background: #42a5f5;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
    margin-top: 20px;
  }

  button:hover {
    background: #2196f3;
  }

  @media (max-width: 600px) {
    .flex-row {
      flex-direction: column;
    }
  }
</style>

</head>
<body>

<div class="container">
  <h2>➕ नया मजदूर ग्रुप जोड़ें</h2>
  <form method="post">
    <div class="flex-row">
      <!-- बाईं ओर: ग्रुप का नाम -->
      <div class="left">
        <label><strong>ग्रुप का नाम:</strong></label>
        <input type="text" name="group_name" placeholder="यहाँ ग्रुप का नाम लिखें" required>
        <button type="submit">✅ ग्रुप बनाएं</button>
      </div>

      <!-- दाईं ओर: लेबर लिस्ट -->
      <div class="right">
        <label><strong>मजदूर चुनें:</strong></label>
        <div class="search-box">
          <input type="text" id="labourSearch" placeholder="🔍 मजदूर खोजें...">
        </div>
        <div class="labour-list" id="labourList">
          <?php while($labour = mysqli_fetch_assoc($labours)): ?>
            <label class="labour-item">
              <input type="checkbox" name="labours[]" value="<?= $labour['id'] ?>">
              <?= htmlspecialchars($labour['name']) ?>
            </label>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
  // श्रमिकों की लाइव खोज
  const searchInput = document.getElementById('labourSearch');
  const labourList = document.getElementById('labourList');

  searchInput.addEventListener('input', function () {
    const filter = this.value.toLowerCase();
    const labours = labourList.querySelectorAll('.labour-item');
    labours.forEach(item => {
      const text = item.textContent.toLowerCase();
      item.style.display = text.includes(filter) ? 'flex' : 'none';
    });
  });
</script>

</body>
</html>
