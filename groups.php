<?php
include 'includes/session.php';
include 'includes/db.php';

$contractor_id = $_SESSION['user_id'];
$query = "SELECT * FROM labour_groups WHERE contractor_id='$contractor_id'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Groups</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f8f9fa;
    }

    /* Header */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: linear-gradient(to right, #e1f5fe, #fce4ec);
      padding: 15px 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 100;
      border-radius: 0 0 20px 20px;
    }

    .header-left, .header-right {
      width: 80px;
      text-align: center;
    }

    .header-center {
      flex-grow: 1;
      text-align: center;
      font-size: 16px;
      font-weight: bold;
      color: #6a1b9a;
    }

    .menu-icon {
      font-size: 20px;
      cursor: pointer;
      color: #6a1b9a;
    }

    .menu-icon:hover {
      color: #4a148c;
    }

    .header a {
      text-decoration: none;
      color: #6a1b9a;
      font-weight: bold;
    }

    /* Subheading */
    .subheading {
      text-align: center;
      font-size: 20px;
      margin: 20px 0 10px;
      color: #444;
      font-weight: bold;
      animation: fadeIn 1s ease-in-out;
    }

    /* Group List */
    .group-container {
      max-width: 600px;
      margin: auto;
      padding: 10px;
    }

    .group-card {
      background-color: #ffffff;
      padding: 15px 20px;
      margin: 10px 0;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
      cursor: pointer;
      display: flex;
      align-items: center;
    }

    .group-card:hover {
      transform: translateX(5px);
      background-color: #e3f2fd;
    }

    .group-name {
      font-size: 16px;
      font-weight: bold;
      color: #333;
    }

    .no-groups {
      text-align: center;
      color: gray;
      font-style: italic;
      margin-top: 40px;
    }

    /* Add Group Button */
    .add-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #64b5f6;
      color: white;
      border: none;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      font-size: 30px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .add-button:hover {
      background-color: #42a5f5;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 600px) {
      .group-card {
        flex-direction: column;
        align-items: flex-start;
      }

      .header-left, .header-right {
        width: auto;
      }
    }
  </style>
</head>
<body>

<!-- Header with Dashboard & Menu -->
<div class="header">
  <div class="header-left">
    <a href="contractor_dashboard.php">🏠 Dashboard</a>
  </div>
  <div class="header-center">
    Created by Pratik with ❤️
  </div>
  <div class="header-right">
    <span class="menu-icon"> 😎</span>
  </div>
</div>

<div class="subheading">📂 आपके ग्रुप्स</div>

<div class="group-container">
  <?php
  if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      echo "<div class='group-card' onclick=\"window.location.href='view_group.php?group_id=" . $row['id'] . "'\">";
      echo "<div class='group-name'>👥 " . htmlspecialchars($row['group_name']) . "</div>";
      echo "</div>";
    }
  } else {
    echo "<div class='no-groups'>🙁 कोई ग्रुप नहीं मिला। कृपया नया ग्रुप जोड़ें।</div>";
  }
  ?>
</div>

<!-- Add Group Button -->
<a href="add_group.php">
  <button class="add-button">+</button>
</a>

</body>
</html>
