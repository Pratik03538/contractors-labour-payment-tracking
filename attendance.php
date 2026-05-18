<?php
include 'includes/session.php';
include 'includes/db.php';

$contractor_id = $_SESSION['user_id'];
date_default_timezone_set('Asia/Kolkata');
$today = date('Y-m-d');

$labours = mysqli_query($conn, "
  SELECT l.*, 
    (SELECT COUNT(*) FROM attendance 
     WHERE labour_id = l.id AND date = '$today') AS has_attendance 
  FROM labours l 
  WHERE l.contractor_id = '$contractor_id' AND (l.is_deleted IS NULL OR l.is_deleted = 0)
");


$groups = mysqli_query($conn, "SELECT * FROM labour_groups WHERE contractor_id='$contractor_id'");
?>

<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="UTF-8">
  <title>मज़दूर उपस्थिति</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f2f5;
      margin: 0;
      padding: 20px;
    }
    .container {
      max-width: 1000px;
      margin: auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h2 { margin-bottom: 20px; }
    .tabs {
      display: flex;
      gap: 20px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }
    .tab-button {
      padding: 10px 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      background-color: #e0e0e0;
    }
    .tab-button.active {
      background-color: #42a5f5;
      color: white;
    }
    .tab-content { display: none; animation: fadeIn 0.4s ease-in-out; }
    .tab-content.active { display: block; }
    .search-box { margin-bottom: 10px; }
    .search-box input {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }
    .labour-list, .group-member-list {
      max-height: 300px;
      overflow-y: auto;
      border: 1px solid #ddd;
      padding: 10px;
      border-radius: 8px;
      background: #fafafa;
      margin-top: 10px;
    }
    .labour-item {
      display: flex;
      align-items: center;
      margin-bottom: 8px;
    }
    .labour-item input { margin-right: 10px; }
    .group-select {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    button.submit {
      padding: 12px 24px;
      background: #4caf50;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      margin-top: 20px;
    }
    button.submit:hover { background: #43a047; }
    .locked { opacity: 0.6; }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @media (max-width: 768px) {
      .tabs { flex-direction: column; }
    }
    /* Modal */
    .modal-overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 999;
      animation: fadeIn 0.3s ease;
    }
    .modal-box {
      background: #fff;
      padding: 30px;
      border-radius: 16px;
      text-align: center;
      max-width: 400px;
      width: 90%;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      animation: popUp 0.3s ease;
    }
    .modal-box h3 { margin-bottom: 10px; }
    .modal-box p { margin-bottom: 20px; font-size: 16px; }
    .modal-buttons {
      display: flex;
      justify-content: center;
      gap: 20px;
    }
    .modal-buttons button {
      padding: 10px 20px;
      border: none;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      font-size: 15px;
    }
    .modal-buttons .yes { background: #4caf50; color: white; }
    .modal-buttons .no { background: #f44336; color: white; }
    @keyframes popUp {
      0% { transform: scale(0.7); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }
  </style>
</head>
<body>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
  <a href="contractor_dashboard.php" style="text-decoration: none; background-color: #ccc; padding: 8px 16px; border-radius: 8px; color: black; font-weight: bold;">⬅️ डैशबोर्ड</a>
  <a href="view_attendance.php" style="text-decoration: none; background-color: #42a5f5; color: white; padding: 8px 16px; border-radius: 8px; font-weight: bold;">📋 उपस्थिति देखें</a>
</div>

<div class="container">
  <h2>🗓️ मज़दूर उपस्थिति</h2>
  <div class="tabs">
    <button class="tab-button active" onclick="switchTab('individual')">व्यक्तिगत मज़दूर</button>
    <button class="tab-button" onclick="switchTab('group')">ग्रुप द्वारा</button>
  </div>
  <form method="post" action="submit_attendance.php" onsubmit="return showConfirmationModal();">
    <div id="individual" class="tab-content active">
      <div class="search-box">
        <input type="text" placeholder="🔍 मज़दूर खोजें..." onkeyup="filterList(this.value, 'labourList')">
      </div>
      <div id="labourList" class="labour-list">
        <?php while ($row = mysqli_fetch_assoc($labours)): ?>
          <label class="labour-item<?= $row['has_attendance'] ? ' locked' : '' ?>">
            <input type="checkbox" name="labour_ids[]" value="<?= $row['id'] ?>" <?= $row['has_attendance'] ? 'checked disabled' : '' ?>>
            <?= htmlspecialchars($row['name']) ?>
          </label>
        <?php endwhile; ?>
      </div>
    </div>
    <div id="group" class="tab-content">
      <label><strong>ग्रुप चुनें:</strong></label>
      <select id="groupSelect" class="group-select" onchange="fetchGroupMembers(this.value)">
        <option value="">-- ग्रुप चुनें --</option>
        <?php mysqli_data_seek($groups, 0); while ($g = mysqli_fetch_assoc($groups)): ?>
          <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['group_name']) ?></option>
        <?php endwhile; ?>
      </select>
      <div id="groupMembers" class="group-member-list" style="display: none;"></div>
    </div>
    <button type="submit" class="submit">✅ उपस्थिति दर्ज करें</button>
  </form>
</div>

<div id="confirmationModal" class="modal-overlay">
  <div class="modal-box">
    <h3>📝 पुष्टि करें</h3>
    <p>क्या आप इन मज़दूरों की उपस्थिति दर्ज करना चाहते हैं?</p>
    <div class="modal-buttons">
      <button onclick="submitForm()" class="yes">✅ हाँ</button>
      <button onclick="closeModal()" class="no">❌ नहीं</button>
    </div>
  </div>
</div>

<script>
  function switchTab(tabId) {
    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    document.querySelector(`[onclick="switchTab('${tabId}')"]`).classList.add('active');
    document.getElementById(tabId).classList.add('active');
  }
  function filterList(searchText, listId) {
    const filter = searchText.toLowerCase();
    const items = document.getElementById(listId).querySelectorAll('.labour-item');
    items.forEach(item => {
      const name = item.textContent.toLowerCase();
      item.style.display = name.includes(filter) ? 'flex' : 'none';
    });
  }
  function fetchGroupMembers(groupId) {
    const groupMembersDiv = document.getElementById('groupMembers');
    if (!groupId) {
      groupMembersDiv.style.display = 'none';
      groupMembersDiv.innerHTML = '';
      return;
    }
    fetch(`get_group_members.php?group_id=${groupId}`)
      .then(res => res.json())
      .then(data => {
        groupMembersDiv.innerHTML = '';
        if (data.length > 0) {
          data.forEach(labour => {
            const label = document.createElement('label');
            label.className = 'labour-item' + (labour.already_marked ? ' locked' : '');
            label.innerHTML = `
              <input type="checkbox" name="labour_ids[]" value="${labour.id}" ${labour.already_marked ? 'checked disabled' : ''}>
              ${labour.name}
            `;
            groupMembersDiv.appendChild(label);
          });
          groupMembersDiv.style.display = 'block';
        } else {
          groupMembersDiv.innerHTML = '⚠️ इस ग्रुप में कोई मज़दूर नहीं है।';
          groupMembersDiv.style.display = 'block';
        }
      });
  }
  function showConfirmationModal() {
    document.getElementById('confirmationModal').style.display = 'flex';
    return false;
  }
  function closeModal() {
    document.getElementById('confirmationModal').style.display = 'none';
  }
  function submitForm() {
    closeModal();
    document.querySelector('form').submit();
  }
</script>

<footer style="margin-top: 40px; text-align: center; font-size: 14px; color: #777;">
  ❤️ Made by Pratik
</footer>
</body>
</html>
