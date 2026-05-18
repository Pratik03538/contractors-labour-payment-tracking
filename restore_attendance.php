<?php
include 'includes/session.php';
include 'includes/db.php';

$contractor_id = $_SESSION['user_id'];

if (isset($_GET['restore_id'])) {
    $id = $_GET['restore_id'];
    mysqli_query($conn, "UPDATE attendance SET is_removed = NULL WHERE id = '$id' AND contractor_id='$contractor_id'");
    header("Location: view_attendance.php?restored=1");
    exit();
}

$result = mysqli_query($conn, "SELECT a.id, a.date, l.name FROM attendance a JOIN labours l ON a.labour_id = l.id WHERE a.contractor_id='$contractor_id' AND a.is_removed = 1 ORDER BY a.date DESC");
?>

<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>पुनर्स्थापना</title>
  <style>
    body {
      font-family: sans-serif;
      background: #f9f9f9;
      margin: 0;
      padding: 0;
    }

    .header {
      background: #007bff;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 14px;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .header a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      font-size: 14px;
    }

    .header .center {
      font-size: 14px;
      text-align: center;
      flex: 1;
    }

    .container {
      max-width: 600px;
      margin: 20px auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-top: 0;
      font-size: 18px;
    }

    .table-container {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      min-width: 400px;
    }

    th, td {
      padding: 10px;
      border-bottom: 1px solid #ddd;
      text-align: left;
      font-size: 14px;
    }

    .btn {
      background: #27ae60;
      color: white;
      padding: 6px 10px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      text-decoration: none;
      font-size: 13px;
    }

    .btn:hover {
      background: #219150;
    }

    @media (max-width: 600px) {
      .header {
        padding: 6px 10px;
      }

      .header a, .header .center {
        font-size: 12px;
      }

      .container {
        margin: 15px;
        padding: 15px;
      }

      h2 {
        font-size: 16px;
      }

      th, td {
        font-size: 13px;
        padding: 8px;
      }

      .btn {
        font-size: 12px;
        padding: 5px 8px;
      }
    }
  </style>
</head>
<body>

  <div class="header">
    <div><a href="view_attendance.php">⬅️ Back</a></div>
    <div class="center">🧑‍💻 Created by Pratik</div>
    <div><a href="contractor_dashboard.php">🏠 Dashboard</a></div>
  </div>

  <div class="container">
    <h2>🧾 हटाई गई उपस्थिति पुनर्स्थापित करें</h2>
    <div class="table-container">
      <table>
        <tr>
          <th>तारीख</th>
          <th>मज़दूर</th>
          <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $row['date'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><a class="btn" href="?restore_id=<?= $row['id'] ?>">🔄 Restore</a></td>
          </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </div>

</body>
</html>
