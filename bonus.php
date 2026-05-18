<?php

include 'includes/session.php';
include 'includes/db.php';

$contractorID = $_SESSION['user_id'] ?? null;
if (!$contractorID) die("Unauthorized access");
date_default_timezone_set("Asia/Kolkata");
$today = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['selected'] as $labourID) {
        $type = $_POST['type'][$labourID] ?? 'bonus';
        $bonus = (float) $_POST['bonus'][$labourID];

        if ($type === 'deduction') {
            $bonus = -abs($bonus);
        } else {
            $bonus = abs($bonus);
        }

        if ($bonus !== 0) {
            mysqli_query($conn, "
                UPDATE attendance 
                SET bonus = '$bonus' 
                WHERE labour_id = '$labourID' 
                  AND date = '$today'
                  AND (is_removed IS NULL OR is_removed = 0)
            ");
        }
    }

    echo "<script>alert('बोनस / कटौती सेव हो गई'); window.location='contractor_dashboard.php';</script>";
    exit;
}

$presentLaboursQuery = "
    SELECT l.id, l.name, l.mobile
    FROM labours l
    JOIN attendance a ON l.id = a.labour_id
    WHERE a.date = '$today'
      AND (a.is_removed IS NULL OR a.is_removed = 0)
      AND (l.is_deleted IS NULL OR l.is_deleted = 0)
      AND l.contractor_id = '$contractorID'
    GROUP BY l.id
";
$labourResult = mysqli_query($conn, $presentLaboursQuery);
?>

<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>बोनस / कटौती</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: #f9f9f9;
        }
        header {
            background: #4CAF50;
            padding: 16px;
            color: white;
            font-size: 20px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .dashboard-btn {
            background: white;
            color: #4CAF50;
            padding: 6px 10px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
        .container {
            padding: 16px;
        }
        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .labour-row {
            background: white;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            cursor: pointer;
            animation: fadeIn 0.4s ease;
        }
        .labour-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }
        .labour-left input[type="checkbox"] {
            transform: scale(1.4);
        }
        .labour-info {
            display: flex;
            flex-direction: column;
        }
        .labour-name {
            font-weight: bold;
            font-size: 16px;
        }
        .labour-mobile {
            font-size: 13px;
            color: #555;
        }
        .labour-controls {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 6px;
        }
        .bonus-input {
            padding: 6px;
            width: 100px;
            border-radius: 6px;
            font-size: 15px;
            text-align: right;
        }
        select {
            padding: 6px;
            border-radius: 6px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            background: #4CAF50;
            border: none;
            color: white;
            border-radius: 10px;
            margin-top: 20px;
            cursor: pointer;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .save-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .header-container {
            position: relative;
            text-align: center;
            padding: 10px 0;
            background-color: #f1f1f1;
            border-bottom: 1px solid #ddd;
        }
        /* header {
  position: sticky;
  top: 0;
  background: #4CAF50;
  color: white;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 10px;
  z-index: 1000;
  animation: slideDown 0.3s ease-in-out;
}

.header-left,
.header-right {
  flex: 1;
}

.header-center {
  flex: 2;
  text-align: center;
  font-size: 17px;
  font-weight: bold;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.header-left button,
.header-right a {
  background: none;
  border: none;
  color: white;
  font-size: 15px;
  cursor: pointer;
  text-decoration: none;
  font-weight: 500;
}

@media (max-width: 480px) {
  .header-center {
    font-size: 15px;
  }
  .header-left button,
  .header-right a {
    font-size: 14px;
  }
}

@keyframes slideDown {
  from {
    transform: translateY(-100%);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
} */

    </style>
</head>
<body>

<!-- <header>
    <button onclick="history.back()" class="header-btn left">🔙</button>
    <h1 class="header-title">बोनस / कटौती जोड़ें (ज़रूरत होने पर)</h1>
    <a href="contractor_dashboard.php" class="header-btn right">🏠</a>
</header> -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
  <a href="contractor_dashboard.php" style="text-decoration: none; background-color: #ccc; padding: 8px 16px; border-radius: 8px; color: black; font-weight: bold;">⬅️ डैशबोर्ड</a>
  <a href="view_bonus.php" style="text-decoration: none; background-color: #42a5f5; color: white; padding: 8px 16px; border-radius: 8px; font-weight: bold;">📋 बोनस/कटौती देखें</a>
</div>
<h4 class="header-title">बोनस / कटौती जोड़ें (ज़रूरत होने पर)</h1>

<div class="container">
    <form method="POST" onsubmit="return validateForm()">
        <input type="text" id="search" class="search-bar" placeholder="मज़दूर खोजें...">

        <?php while ($row = mysqli_fetch_assoc($labourResult)): ?>
        <div class="labour-row" onclick="toggleCheckbox(this)">
            <div class="labour-left">
                <input type="checkbox" name="selected[]" value="<?= $row['id'] ?>" class="labour-check">
                <div class="labour-info">
                    <span class="labour-name"><?= htmlspecialchars($row['name']) ?></span>
                    <span class="labour-mobile"><?= htmlspecialchars($row['mobile']) ?></span>
                </div>
            </div>
            <div class="labour-controls">
                <input type="number" step="0.01" name="bonus[<?= $row['id'] ?>]" class="bonus-input" placeholder="₹0">
                <select name="type[<?= $row['id'] ?>]">
                    <option value="bonus">बोनस</option>
                    <option value="deduction">कटौती</option>
                </select>
            </div>
        </div>
        <?php endwhile; ?>

        <button type="submit" class="save-button">सेव करें</button>
    </form>
</div>

<script>
function toggleCheckbox(row) {
    const checkbox = row.querySelector('input[type="checkbox"]');
    checkbox.checked = !checkbox.checked;
}

document.getElementById("search").addEventListener("input", function () {
    const query = this.value.toLowerCase();
    document.querySelectorAll(".labour-row").forEach(function (row) {
        const name = row.querySelector(".labour-name").textContent.toLowerCase();
        const mobile = row.querySelector(".labour-mobile").textContent.toLowerCase();
        row.style.display = (name.includes(query) || mobile.includes(query)) ? "flex" : "none";
    });
});

function validateForm() {
    const checkboxes = document.querySelectorAll(".labour-check:checked");
    if (checkboxes.length === 0) {
        alert("कृपया कम से कम एक मज़दूर चुनें।");
        return false;
    }
    return true;
}
</script>

</body>
</html>
