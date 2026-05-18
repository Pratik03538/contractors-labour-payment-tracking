<?php
include 'includes/session.php';
include 'includes/db.php';

$contractorID = $_SESSION['user_id'] ?? null;
if (!$contractorID) die("Unauthorized access");

date_default_timezone_set("Asia/Kolkata");

$selectedDate = $_GET['date'] ?? date('Y-m-d');
$prevDate = date('Y-m-d', strtotime($selectedDate . ' -1 day'));
$nextDate = date('Y-m-d', strtotime($selectedDate . ' +1 day'));
$today = date('Y-m-d');

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "UPDATE attendance SET bonus = NULL WHERE id = '$id'");
    echo "<script>alert('हटा दिया गया'); window.location='view_bonus.php?date=$selectedDate';</script>";
    exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $id = $_POST['edit_id'];
    $amount = (float) $_POST['edit_bonus'];
    $type = $_POST['edit_type'];
    $amount = ($type === 'deduction') ? -abs($amount) : abs($amount);
    mysqli_query($conn, "UPDATE attendance SET bonus = '$amount' WHERE id = '$id'");
    echo "<script>alert('अपडेट हो गया'); window.location='view_bonus.php?date=$selectedDate';</script>";
    exit;
}

// Fetch bonuses
$result = mysqli_query($conn, "
    SELECT a.id, l.name, l.mobile, a.bonus 
    FROM attendance a 
    JOIN labours l ON l.id = a.labour_id 
    WHERE a.date = '$selectedDate' 
      AND a.bonus IS NOT NULL 
      AND (a.is_removed IS NULL OR a.is_removed = 0)
      AND l.contractor_id = '$contractorID'
");

?>

<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>बोनस सूची</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; padding: 0; background: #f7f7f7; }
        header {
            background: #007bff; color: white; padding: 12px 10px; text-align: center;
            position: sticky; top: 0; z-index: 10;
            display: flex; justify-content: space-between; align-items: center;
        }
        header h2 { margin: 0; font-size: 18px; flex: 1; text-align: center; }
        header a {
            color: white; text-decoration: none; font-size: 14px; padding: 5px 10px;
            background: rgba(255, 255, 255, 0.2); border-radius: 6px;
        }

        .date-nav {
            display: flex; align-items: center; justify-content: center; margin: 10px 0;
            gap: 10px;
        }
        .date-nav button {
            background: #007bff; color: white; border: none; padding: 6px 12px;
            border-radius: 5px; font-size: 16px; cursor: pointer;
        }

        table {
            width: 100%; border-collapse: collapse;
            margin-top: 10px; background: white;
        }
        th, td {
            padding: 12px 10px; border-bottom: 1px solid #ddd;
            text-align: center; font-size: 15px;
        }
        th { background: #f0f0f0; }

        .actions button {
            padding: 5px 8px; margin: 0 2px; font-size: 13px;
            border: none; border-radius: 5px; cursor: pointer;
        }
        .edit-btn { background: #ffc107; color: black; }
        .delete-btn { background: #dc3545; color: white; }

        form.edit-form {
            display: flex; gap: 5px; justify-content: center; flex-wrap: wrap;
        }
        form.edit-form input, form.edit-form select {
            padding: 4px 6px; border: 1px solid #ccc; border-radius: 4px;
        }
        form.edit-form button {
            background: green; color: white; border: none;
            padding: 5px 10px; border-radius: 4px;
        }
    </style>
</head>
<body>

<header>
    <a href="contractor_dashboard.php">डैशबोर्ड</a>
    <h2>बोनस / कटौती</h2>
    <a href="bonus.php">बोनस जोड़ें</a>
</header>

<div class="date-nav">
    <a href="?date=<?= $prevDate ?>"><button>&larr;</button></a>
    <strong><?= date('d M Y', strtotime($selectedDate)) ?></strong>
    <?php if ($selectedDate < $today): ?>
        <a href="?date=<?= $nextDate ?>"><button>&rarr;</button></a>
    <?php else: ?>
        <button disabled>&rarr;</button>
    <?php endif; ?>
</div>

<table>
    <thead>
        <tr>
            <th>नाम</th>
            <th>मोबाइल</th>
            <th>राशि</th>
            <th>कार्रवाई</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['mobile']) ?></td>
                <td>
                    <?= $row['bonus'] < 0 ? 'कटौती: ₹' . abs($row['bonus']) : 'बोनस: ₹' . $row['bonus'] ?>
                </td>
                <td class="actions">
                    <form method="POST" class="edit-form">
                        <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
                        <select name="edit_type">
                            <option value="bonus" <?= $row['bonus'] >= 0 ? 'selected' : '' ?>>बोनस</option>
                            <option value="deduction" <?= $row['bonus'] < 0 ? 'selected' : '' ?>>कटौती</option>
                        </select>
                        <input type="number" step="0.01" name="edit_bonus" value="<?= abs($row['bonus']) ?>" required>
                        <button type="submit">सेव</button>
                        <a href="?delete=<?= $row['id'] ?>&date=<?= $selectedDate ?>">
                            <button type="button" class="delete-btn">हटाएं</button>
                        </a>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
