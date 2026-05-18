<?php
include 'includes/session.php';
include 'includes/db.php';

$contractor_id = $_SESSION['user_id'];
$group_id = $_GET['group_id'] ?? 0;

// Fetch group
$group_query = mysqli_query($conn, "SELECT * FROM labour_groups WHERE id='$group_id' AND contractor_id='$contractor_id'");
$group = mysqli_fetch_assoc($group_query);
$group_name = $group['group_name'] ?? '';

// Fetch all labours
$all_labours_query = mysqli_query($conn, "SELECT * FROM labours WHERE contractor_id='$contractor_id'");

// Fetch group members
$group_members_query = mysqli_query($conn, "SELECT labour_id FROM labour_group_members WHERE group_id='$group_id'");
$existing_members = [];
while($row = mysqli_fetch_assoc($group_members_query)) {
    $existing_members[] = $row['labour_id'];
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save'])) {
        $changed = false;

        if (!empty($_POST['remove_labours'])) {
            foreach ($_POST['remove_labours'] as $lid) {
                mysqli_query($conn, "DELETE FROM labour_group_members WHERE group_id='$group_id' AND labour_id='$lid'");
            }
            $changed = true;
        }

        if (!empty($_POST['add_labours'])) {
            foreach ($_POST['add_labours'] as $lid) {
                $check = mysqli_query($conn, "SELECT * FROM labour_group_members WHERE group_id='$group_id' AND labour_id='$lid'");
                if (mysqli_num_rows($check) == 0) {
                    mysqli_query($conn, "INSERT INTO labour_group_members (group_id, labour_id) VALUES ('$group_id', '$lid')");
                }
            }
            $changed = true;
        }

        if ($changed) {
          header("Location: view_group.php?group_id=$group_id&updated=1");
          exit();
        }
    }

    if (isset($_POST['delete'])) {
        mysqli_query($conn, "DELETE FROM labour_group_members WHERE group_id='$group_id'");
        mysqli_query($conn, "DELETE FROM labour_groups WHERE id='$group_id'");
        echo "<script>alert('❌ Group deleted successfully.'); window.location.href='groups.php';</script>";
        exit();
    }
}

$updated = isset($_GET['updated']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Group</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding-bottom: 80px;
    }

    .header {
      background: linear-gradient(to right, #f3e5f5, #e1f5fe);
      color: #6a1b9a;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      padding: 10px 15px;
      font-weight: bold;
      font-size: 16px;
      position: sticky;
      top: 0;
    }

    .header .title {
      width: 100%;
      text-align: center;
      font-size: 18px;
      margin-top: 5px;
    }

    .header .note {
      width: 100%;
      text-align: center;
      font-size: 14px;
      color: #888;
    }

    .header a {
      color: #6a1b9a;
      text-decoration: none;
      font-size: 16px;
    }

    .container {
      max-width: 600px;
      margin: 20px auto;
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .section-title {
      font-size: 20px;
      margin-bottom: 10px;
      font-weight: bold;
    }

    .labour-list {
      list-style: none;
      padding: 0;
    }

    .labour-item {
      background: #f9f9f9;
      padding: 10px 15px;
      margin: 8px 0;
      border-radius: 8px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: pointer;
      transition: background 0.3s;
    }

    .labour-item:hover {
      background: #e0f7fa;
    }

    .labour-item input[type="checkbox"] {
      transform: scale(1.2);
      margin-left: auto;
    }

    .btn {
      padding: 10px 15px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      margin: 10px 5px 10px 0;
      transition: all 0.3s ease;
    }

    .btn:hover {
      transform: translateY(-2px);
      opacity: 0.9;
    }

    .btn-remove {
      background-color: #ffb74d;
      color: white;
    }

    .btn-add-toggle {
      background-color: #81c784;
      color: white;
    }

    .btn-save {
      background-color: #42a5f5;
      color: white;
    }

    .btn-delete {
      background-color: #ef5350;
      color: white;
      position: fixed;
      bottom: 10px;
      left: 50%;
      transform: translateX(-50%);
      width: calc(100% - 40px);
      max-width: 500px;
    }

    #add-section {
      display: none;
    }

    .remove-check {
      display: none;
    }

    .popup {
      display: none;
      position: fixed;
      top: 30%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: #ffffff;
      border: 2px solid #4caf50;
      padding: 20px 30px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
      animation: fadeIn 0.5s ease-out forwards;
      z-index: 999;
      font-weight: bold;
      color: #4caf50;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translate(-50%, -60%); }
      to { opacity: 1; transform: translate(-50%, -50%); }
    }
  </style>
</head>
<body>

<div class="header">
  <a href="groups.php">← Back</a>
  <div class="title">✨ Edit Group: <?= htmlspecialchars($group_name) ?> ✨</div>
  <div class="note">Made with ❤️ by Pratik</div>
</div>

<div class="container">
  <form method="post">
    <div class="section-title">✅ Current Labours</div>
    <ul class="labour-list">
      <?php
      mysqli_data_seek($all_labours_query, 0);
      while($labour = mysqli_fetch_assoc($all_labours_query)) {
        if(in_array($labour['id'], $existing_members)) {
          echo "<label class='labour-item' for='remove_{$labour['id']}'>
                  <span>{$labour['name']}</span>
                  <input type='checkbox' id='remove_{$labour['id']}' name='remove_labours[]' value='{$labour['id']}' class='remove-check'>
                </label>";
        }
      }
      ?>
    </ul>

    <button type="button" class="btn btn-remove" onclick="toggleRemove()">🗑 Remove Labour</button>
    <button type="button" class="btn btn-add-toggle" onclick="toggleAdd()">➕ Add Labour</button>

    <div id="add-section">
      <div class="section-title">Add Labours</div>
      <ul class="labour-list">
        <?php
        mysqli_data_seek($all_labours_query, 0);
        while($labour = mysqli_fetch_assoc($all_labours_query)) {
          if(!in_array($labour['id'], $existing_members)) {
            echo "<label class='labour-item' for='add_{$labour['id']}'>
                    <span>{$labour['name']}</span>
                    <input type='checkbox' id='add_{$labour['id']}' name='add_labours[]' value='{$labour['id']}'>
                  </label>";
          }
        }
        ?>
      </ul>
    </div>

    <div style="text-align:center">
      <button type="submit" name="save" class="btn btn-save">💾 Save</button>
    </div>
    <button type="submit" name="delete" class="btn btn-delete" onclick="return confirmDelete()">🗑 Delete Group</button>
  </form>
</div>

<div class="popup" id="popup">✅ Group updated successfully!</div>

<script>
  function toggleRemove() {
    const checkboxes = document.querySelectorAll('.remove-check');
    checkboxes.forEach(cb => {
      cb.style.display = (cb.style.display === 'inline-block') ? 'none' : 'inline-block';
    });
  }

  function toggleAdd() {
    const section = document.getElementById('add-section');
    section.style.display = (section.style.display === 'block') ? 'none' : 'block';
  }

  function confirmDelete() {
    return confirm('Are you sure you want to delete this group?');
  }

  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('updated') === '1') {
    const popup = document.getElementById('popup');
    popup.style.display = 'block';
    setTimeout(() => {
      popup.style.display = 'none';
      urlParams.delete('updated');
      const cleanUrl = window.location.pathname + '?' + urlParams.toString();
      window.history.replaceState({}, document.title, cleanUrl);
    }, 2000);
  }
</script>

</body>
</html>
