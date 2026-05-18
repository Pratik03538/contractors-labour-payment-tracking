<?php 
date_default_timezone_set("Asia/Kolkata");
include 'includes/session.php';
include 'includes/db.php';

$contractor_id = $_SESSION['user_id'];
$today = date('Y-m-d');
$date = isset($_GET['date']) ? $_GET['date'] : $today;
$selected_labour_id = isset($_GET['labour_id']) ? $_GET['labour_id'] : '';

// ✅ FIXED: a.id को attendance_id के रूप में SELECT किया
$query = "SELECT a.id AS attendance_id, a.date, l.name, a.type, l.salary_amount, a.custom_amount, l.id, a.is_removed 
          FROM attendance a 
          JOIN labours l ON a.labour_id = l.id 
          WHERE a.contractor_id='$contractor_id'";

if ($selected_labour_id) {
    $query .= " AND l.id = '$selected_labour_id'";
} else {
    $query .= " AND a.date = '$date'";
}

$query .= " ORDER BY a.date DESC";
$result = mysqli_query($conn, $query);

$name_result = mysqli_query($conn, "SELECT id, name FROM labours WHERE contractor_id='$contractor_id'");
$names = [];
while ($row = mysqli_fetch_assoc($name_result)) {
    $names[] = $row;
}
?>

<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="UTF-8">
  <title>उपस्थिति रिकॉर्ड</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f6f8fa;
    }
    .header {
      background: #2c3e50;
      color: white;
      padding: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }
    .header h2 {
      margin: 0 auto;
      font-weight: normal;
      animation: fadeIn 1s ease-in-out;
      font-size: 18px;
    }
    .btn {
      background: #ecf0f1;
      color: #2c3e50;
      border: none;
      padding: 8px 14px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s ease;
    }
    .btn:hover {
      background: #d0d3d4;
    }
    .container {
      padding: 20px;
      max-width: 900px;
      margin: auto;
      background: white;
      margin-top: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      animation: slideIn 0.6s ease;
    }
    .controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 15px;
    }
    .calendar {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .calendar input {
      padding: 6px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .search-box {
      position: relative;
    }
    .search-box input {
      padding: 8px;
      width: 200px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    .search-dropdown {
      position: absolute;
      background: white;
      border: 1px solid #ccc;
      width: 100%;
      max-height: 150px;
      overflow-y: auto;
      z-index: 10;
      border-radius: 6px;
    }
    .search-dropdown div {
      padding: 8px;
      cursor: pointer;
    }
    .search-dropdown div:hover {
      background: #eee;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      table-layout: fixed;
      word-wrap: break-word;
    }
    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #eee;
      font-size: 14px;
    }
    th {
      background: #ecf0f1;
    }
    .salary-strike {
      text-decoration: line-through;
      color: #888;
    }
    .footer {
      text-align: center;
      color: #555;
      margin-top: 30px;
      font-size: 14px;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @media (max-width: 600px) {
  .controls {
    flex-direction: column;
    gap: 10px;
  }
  .calendar input, .search-box input {
    width: 100%;
  }
  .header h2 {
    font-size: 16px;
    width: 100%;
    text-align: center;
    margin-top: 10px;
  }
  th, td {
    font-size: 12px;
    padding: 6px 4px;
  }
  .container {
    padding: 10px;
    overflow-x: auto;
  }
  table {
    display: block;
    width: 100%;
    overflow-x: auto;
    white-space: nowrap;
  }
}

  </style>
</head>
<body>

<div class="header">
  <button class="btn" onclick="location.href='contractor_dashboard.php'">← डैशबोर्ड</button>
  <h2>❤️ Made by Pratik</h2>
  <button class="btn" style="margin-left:auto;" onclick="location.href='restore_attendance.php'">Restore 🔄</button>
</div>

<div class="container">
  <div class="controls">
    <div class="calendar">
      <button class="btn" onclick="navigateDate(-1)">←</button>
      <input type="date" id="datePicker" value="<?= $date ?>" onchange="onDateChange(this.value)">
      <button class="btn" onclick="navigateDate(1)" id="nextBtn">→</button>
    </div>
    <div class="search-box">
      <input type="text" id="searchInput" placeholder="👷 मज़दूर खोजें..." oninput="filterNames(this.value)">
      <div class="search-dropdown" id="nameDropdown" style="display:none;"></div>
    </div>
  </div>

  <h3 style="text-align:center;">
    <?php if ($selected_labour_id): ?>
      👷 इस मज़दूर की उपस्थिति
    <?php else: ?>
      📅 <?= date('d M Y', strtotime($date)) ?> की उपस्थिति
    <?php endif; ?>
  </h3>

  <table>
    <tr>
      <th>तारीख</th>
      <th>मज़दूर</th>
      <th>प्रकार</th>
      <th>वेतन</th>
      <th>एडिट</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <tr style="<?= $row['is_removed'] == 1 ? 'color: #999; text-decoration: line-through; pointer-events: none;' : '' ?>">
        <td><?= htmlspecialchars($row['date']) ?></td>
        <td><?= htmlspecialchars($row['name']) ?> <?= $row['is_removed'] == 1 ? '❌' : '' ?></td>
        <td><?= $row['type'] === 'full' ? 'पूर्ण दिन' : ($row['type'] === 'half' ? 'आधा दिन' : 'कस्टम') ?></td>
        <td>
          <?php if ($row['custom_amount'] !== null): ?>
            <span class="salary-strike">₹<?= $row['salary_amount'] ?></span> → ₹<?= $row['custom_amount'] ?>
          <?php else: ?>
            ₹<?= $row['salary_amount'] ?>
          <?php endif; ?>
        </td>
        <td>
          <?php if ($row['is_removed'] == 1): ?>
            ❌
          <?php else: ?>
            <!-- ✅ FIXED: using correct attendance_id -->
            <a href="edit_attendance.php?id=<?= $row['attendance_id'] ?>" id="edit_<?= $row['attendance_id'] ?>">✏️</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<div class="footer">
  💖 Made with Love by Pratik
</div>

<script>
  const today = "<?= $today ?>";

  function onDateChange(date) {
    window.location.href = '?date=' + date;
  }

  function navigateDate(offset) {
    const current = new Date(document.getElementById("datePicker").value);
    current.setDate(current.getDate() + offset);
    const formatted = current.toISOString().split('T')[0];
    window.location.href = '?date=' + formatted;
  }

  const input = document.getElementById("searchInput");
  const dropdown = document.getElementById("nameDropdown");
  const names = <?= json_encode($names) ?>;

  function filterNames(query) {
    dropdown.innerHTML = '';
    if (query.trim() === '') {
      dropdown.style.display = 'none';
      return;
    }

    const matches = names.filter(n => n.name.toLowerCase().includes(query.toLowerCase()));
    if (matches.length === 0) {
      dropdown.style.display = 'none';
      return;
    }

    matches.forEach(n => {
      const div = document.createElement("div");
      div.textContent = n.name;
      div.onclick = () => {
        window.location.href = "?labour_id=" + n.id;
      };
      dropdown.appendChild(div);
    });

    dropdown.style.display = 'block';
  }

  window.onload = () => {
    const selectedDate = document.getElementById("datePicker").value;
    const nextBtn = document.getElementById("nextBtn");
    if (selectedDate >= today) {
      nextBtn.disabled = true;
      nextBtn.style.opacity = 0.5;
    }
  };
</script>

</body>
</html>
