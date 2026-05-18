<?php
include 'includes/session.php';
include 'includes/db.php';

// Restore functionality handled here with error checking
$showRestoreSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restore_id'])) {
    $restore_id = intval($_POST['restore_id']);
    $update_query = "UPDATE labours SET is_deleted = 0 WHERE id = $restore_id";
    if (mysqli_query($conn, $update_query)) {
        $showRestoreSuccess = true;
    } else {
        echo "<script>alert('❌ Restore करने में समस्या: " . mysqli_error($conn) . "'); window.location.href='trash_labours.php';</script>";
        exit();
    }
}

// Fetch deleted labours for this contractor
$contractor_id = $_SESSION['user_id'];
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM labours WHERE contractor_id = $contractor_id AND is_deleted = 1";
if ($search != '') {
    $query .= " AND name LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
}
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>🗑️ डिलीटेड लेबर सूची</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: linear-gradient(to right, #fff, #e0f7fa);
            padding: 20px;
        }
        header {
    background-color: #2196f3;
    color: white;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.header-btn {
    background-color: white;
    color: #2196f3;
    border: none;
    padding: 5px 10px;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    font-size: 14px;
}

.header-center {
    text-align: center;
    flex-grow: 1;
}

.header-center h2 {
    margin: 0;
    font-size: 20px;
}

@media screen and (max-width: 600px) {
    header {
        padding: 6px 10px;
    }

    .header-btn {
        font-size: 12px;
        padding: 4px 8px;
    }

    .header-center h2 {
        font-size: 16px;
    }
}
        .container { padding: 15px; }
        .search-bar {
            text-align: center;
            margin-bottom: 15px;
        }
        .search-bar input {
            padding: 8px;
            width: 80%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }
        .table-responsive { overflow-x: auto; width: 100%; }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
            min-width: 600px;
        }
        th, td {
            padding: 12px 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th { background-color: #80deea; color: #000; }
        tr:hover { background-color: #f1f1f1; }
        .btn {
            padding: 6px 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-restore { background-color: #4caf50; color: white; }
        @media screen and (max-width: 768px) {\n            .header-btn { font-size: 12px; padding: 5px 10px; }\n            .header-center h2 { font-size: 18px; }\n            th, td { font-size: 14px; padding: 10px 6px; }\n            table { min-width: 100%; }\n        }\n        @media screen and (max-width: 480px) {\n            .search-bar input { width: 100%; }\n        }\n    </style>
    <script>
        function searchLabours() {
            let input = document.getElementById("search").value.toUpperCase();
            let rows = document.querySelectorAll("table tbody tr");
            rows.forEach(row => {
                let name = row.cells[0].textContent.toUpperCase();
                row.style.display = name.includes(input) ? "" : "none";
            });
        }
    </script>
    <style>
  #overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    z-index: 999;
  }
  #popupBox {
    position: fixed;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%) scale(0.9);
    background: white;
    padding: 20px 30px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    display: none;
    z-index: 1000;
    text-align: center;
    animation: popupAnim 0.3s ease-out forwards;
  }

  @keyframes popupAnim {
    from { transform: translate(-50%, -50%) scale(0.9); opacity: 0; }
    to { transform: translate(-50%, -50%) scale(1); opacity: 1; }
  }

  #popupBox button {
    margin: 10px 8px;
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    font-size: 14px;
  }

  #popupBox .yes-btn {
    background-color: #4caf50;
    color: white;
  }
  #popupBox .no-btn {
    background-color: #f44336;
    color: white;
  }
</style>

</head>
<body>

<header>
    <a class="header-btn" href="show_labours.php">👥 लेबर सूची</a>
    <div class="header-center">
        <h2>🗑️ डिलीटेड लेबर</h2>
    </div>
    <a class="header-btn" href="contractor_dashboard.php">🏠 डैशबोर्ड</a>
</header>

<div class="container">
    <div class="search-bar">
        <input type="text" id="search" onkeyup="searchLabours()" placeholder="🔍 नाम से खोजें...">
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                     <th>♻️ Restore</th>
                    <th>👤 नाम</th>
                    <th>📱 मोबाइल</th>
                    <th>💼 भूमिका</th>
                    <th>💰 वेतन प्रकार</th>
                    <th>₹ वेतन</th>
                    <th>📅 हटाने की तिथि</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                    <td>
                    <form method="POST" class="restoreForm" style="display:inline-block;">
    <input type="hidden" name="restore_id" value="<?= $row['id']; ?>">
    <button type="button" class="btn btn-restore" onclick="confirmRestore(this)">♻️</button>
</form>

                        </td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td>+91 <?= htmlspecialchars($row['mobile']); ?></td>
                        <td><?= htmlspecialchars($row['role']); ?></td>
                        <td><?= htmlspecialchars($row['salary_type']); ?></td>
                        <td><?= htmlspecialchars($row['salary_amount']); ?></td>
                        <td><?= date('d-m-Y', strtotime($row['timestamp'])); ?></td>
                       
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div id="overlay"></div>
<div id="popupBox">
  <p>क्या आप वाकई इसे restore करना चाहते हैं?</p>
  <button class="yes-btn" onclick="submitRestore()">हाँ</button>
  <button class="no-btn" onclick="closePopup()">नहीं</button>
</div>
<script>
  let activeForm = null;

  function confirmRestore(btn) {
    activeForm = btn.closest('form');
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('popupBox').style.display = 'block';
  }

  function closePopup() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('popupBox').style.display = 'none';
    activeForm = null;
  }

  function submitRestore() {
    if (activeForm) activeForm.submit();
  }
</script>
<div id="restoreSuccessPopup" style="
    display: none;
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: #4caf50;
    color: white;
    padding: 12px 24px;
    border-radius: 30px;
    font-weight: bold;
    font-size: 16px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    z-index: 9999;
    animation: fadeIn 0.3s ease;
">
    ✅ लेबर सफलतापूर्वक restore कर दिया गया है!
</div>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateX(-50%) translateY(10px); }
  to { opacity: 1; transform: translateX(-50%) translateY(0); }
}
</style>
<script>
  <?php if ($showRestoreSuccess): ?>
    document.addEventListener('DOMContentLoaded', function () {
      const popup = document.getElementById('restoreSuccessPopup');
      popup.style.display = 'block';
      setTimeout(() => {
        popup.style.display = 'none';
        window.location.href = 'trash_labours.php';
      }, 2000); // 2 seconds
    });
  <?php endif; ?>
</script>

</body>
</html>
