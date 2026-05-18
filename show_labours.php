<?php
include 'includes/session.php';
include 'includes/db.php';

$contractor_id = $_SESSION['user_id'];
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM labours WHERE is_deleted = 0 AND contractor_id = $contractor_id";
if ($search != '') {
    $query .= " AND name LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
}
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>👷 लेबर सूची</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #e3f2fd, #fff);
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


        .container {
            padding: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .search-bar {
            text-align: center;
            margin-bottom: 15px;
            width: 100%;
        }

        .search-bar input {
            padding: 8px;
            width: 80%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        .table-wrapper {
            width: 100%;
            max-width: 1000px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #90caf9;
            color: #000;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            padding: 6px 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-update { background-color: #4caf50; color: white; }
        .btn-delete { background-color: #f44336; color: white; }

        @media screen and (max-width: 768px) {
            th, td {
                font-size: 14px;
                padding: 10px 6px;
            }

            table { font-size: 14px; }
        }

        @media screen and (max-width: 480px) {
            .search-bar input { width: 100%; }
        }
        .popup-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex; align-items: center; justify-content: center;
    z-index: 1000;
    animation: fadeIn 0.3s ease-in-out;
}

.popup {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    animation: scaleIn 0.3s ease-in-out;
    text-align: center;
}

.btn-confirm, .btn-cancel {
    padding: 8px 16px;
    margin: 10px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
}

.btn-confirm {
    background-color: #e74c3c;
    color: white;
}

.btn-cancel {
    background-color: #bdc3c7;
    color: black;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes scaleIn {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.4);
  display: flex;
  justify-content: center;
  align-items: center;
  animation: fadeIn 0.3s ease;
  z-index: 999;
}

.popup-content {
  background: white;
  padding: 20px 30px;
  border-radius: 12px;
  text-align: center;
  animation: slideUp 0.3s ease;
}

.popup-buttons {
  margin-top: 15px;
  display: flex;
  justify-content: center;
  gap: 15px;
}

@keyframes fadeIn {
  from { opacity: 0; } to { opacity: 1; }
}

@keyframes slideUp {
  from { transform: translateY(20px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

    </style>

    <script>
        function searchLabours() {
            let input = document.getElementById("search").value.toLowerCase();
            let rows = document.querySelectorAll("table tbody tr");
            let found = false;

            rows.forEach(row => {
                if (row.id === "noDbRow") return; // skip default message row
                let name = row.querySelector("td").innerText.toLowerCase();
                if (name.includes(input)) {
                    row.style.display = "";
                    found = true;
                } else {
                    row.style.display = "none";
                }
            });

            const noResultRow = document.getElementById("noResultRow");
            if (noResultRow) {
                noResultRow.style.display = found ? "none" : "";
            }
        }

        function confirmDelete(id) {
    deleteId = id;
    document.getElementById("deletePopup").style.display = "flex";
}

function closePopup() {
    deleteId = null;
    document.getElementById("deletePopup").style.display = "none";
}

document.getElementById("confirmDeleteBtn").addEventListener("click", function() {
    if (deleteId !== null) {
        fetch('delete_labour.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(deleteId)
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const row = document.querySelector(`tr[data-id="${deleteId}"]`);
                if (row) row.remove(); // row हटाओ frontend से
                closePopup(); // popup बंद करो
            } else {
                alert("डिलीट नहीं हो पाया।");
                closePopup();
            }
        })
        .catch(() => {
            alert("सर्वर से संपर्क नहीं हो सका।");
            closePopup();
        });
    }
});



    </script>
</head>
<body>

<header>
    <a class="header-btn" href="contractor_dashboard.php">🏠 डैशबोर्ड</a>
    <div class="header-center">
        <h2>👷‍♂️ लेबर की सूची</h2>
    </div>
    <a class="header-btn" href="trash_labours.php">🗑️ ट्रैश</a>
</header>

<div class="container">
    <div class="search-bar">
        <input type="text" id="search" onkeyup="searchLabours()" placeholder="🔍 नाम से खोजें...">
    </div>

  <div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>✏️</th>
                <th>🗑️</th>
                <th>👤 नाम</th>
                <th>📱 मोबाइल</th>
                <th>💼 भूमिका</th>
                <th>💰 वेतन प्रकार</th>
                <th>₹ वेतन</th>
                <th>📅 तिथि</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($result) == 0): ?>
                <tr id="noDbRow">
                    <td colspan="8">कोई लेबर नहीं मिला।</td>
                </tr>
            <?php else: ?>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <!-- Update and Delete at the start -->
                        <td>
                            <a class="btn btn-update" href="update_labour.php?id=<?= $row['id']; ?>">✏️</a>
                        </td>
                        <td>
                            <button class="btn btn-delete" onclick="confirmDelete(<?= $row['id']; ?>)">🗑️</button>
                        </td>

                        <!-- Remaining columns -->
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td>+91 <?= htmlspecialchars($row['mobile']); ?></td>
                        <td><?= htmlspecialchars($row['role']); ?></td>
                        <td><?= htmlspecialchars($row['salary_type']); ?></td>
                        <td><?= htmlspecialchars($row['salary_amount']); ?></td>
                        <td><?= date('d-m-Y', strtotime($row['timestamp'])); ?></td>
                    </tr>
                <?php } ?>
                <!-- Hidden row for JS search fallback -->
                <tr id="noResultRow" style="display: none;">
                    <td colspan="8">कोई लेबर नहीं मिला।</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Delete Confirmation Popup -->
<div id="deletePopup" class="popup-overlay" style="display: none;">
  <div class="popup-content">
    <p>क्या आप वाकई इस मजदूर को हटाना चाहते हैं?</p>
    <div class="popup-buttons">
      <button id="confirmDeleteBtn" class="btn btn-danger">हाँ</button>
      <button onclick="closePopup()" class="btn btn-secondary">नहीं</button>
    </div>
  </div>
</div>
<div id="notification" style="
    display:none;
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #4caf50;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: bold;
    z-index: 1001;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    animation: fadeIn 0.3s ease;
"></div>
<script>
    document.getElementById("confirmDeleteBtn").addEventListener("click", function() {
    if (deleteId !== null) {
        fetch('delete_labour.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(deleteId)
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // Show success popup
                const popup = document.getElementById('deleteSuccessPopup');
                popup.style.display = 'block';

                // Hide popup after 2 seconds and reload
                setTimeout(() => {
                    popup.style.display = 'none';
                    location.reload();
                }, 1000);
            } else {
                alert("❌ डिलीट नहीं हो पाया।");
                closePopup();
            }
        })
        .catch(() => {
            alert("❌ सर्वर से संपर्क नहीं हो सका।");
            closePopup();
        });
    }
});

</script>



<div id="notification" style="
    display:none;
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #4caf50;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: bold;
    z-index: 1001;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    animation: fadeIn 0.3s ease;
"></div>
<div id="deleteSuccessPopup" style="
    display: none;
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: #4CAF50;
    color: white;
    padding: 12px 24px;
    border-radius: 30px;
    font-weight: bold;
    font-size: 16px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    z-index: 9999;
    animation: fadeIn 0.3s ease;
">
    ✅ मज़दूर सफलतापूर्वक डिलीट किया गया!
</div>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateX(-50%) translateY(10px); }
  to { opacity: 1; transform: translateX(-50%) translateY(0); }
}
</style>


</body>
</html>
