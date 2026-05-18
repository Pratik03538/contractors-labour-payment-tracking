<?php
include 'includes/session.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$contractor_id = $_SESSION['user_id'];

// Get labour list for contractor
$stmt = $conn->prepare("SELECT id, name, mobile FROM labours WHERE contractor_id = ? AND is_deleted = 0 ORDER BY name ASC");
$stmt->bind_param("i", $contractor_id);
$stmt->execute();
$labours = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Labour Search</title>
<style>
    /* Reset */
    * {
        box-sizing: border-box;
    }
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 20px;
        background: #f9fafb;
        color: #333;
    }
    h2 {
        text-align: center;
        margin-bottom: 30px;
        font-weight: 700;
        color: #2c3e50;
    }
    form {
        max-width: 480px;
        margin: 0 auto;
        background: white;
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        transition: box-shadow 0.3s ease;
    }
    form:hover {
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    }
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #34495e;
    }
    select {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        border: 1.8px solid #d1d5db;
        font-size: 1rem;
        transition: border-color 0.3s ease;
        outline: none;
    }
    select:focus {
        border-color: #3498db;
        box-shadow: 0 0 6px #3498db;
    }
    button {
        width: 100%;
        background: #3498db;
        border: none;
        padding: 14px;
        font-size: 1.1rem;
        color: white;
        font-weight: 700;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        user-select: none;
    }
    button:hover {
        background: #2980b9;
    }
    @media (max-width: 500px) {
        body {
            padding: 15px;
        }
        form {
            padding: 20px;
        }
    }
</style>
</head>
<body>

<h2>Search Labour Activity</h2>

<form method="GET" action="labour_details.php">
    <label for="labour_id">Select Labour:</label>
    <select name="labour_id" id="labour_id" required>
        <option value="">--Select Labour--</option>
        <?php foreach ($labours as $labour): ?>
            <option value="<?= htmlspecialchars($labour['id']) ?>"
                <?= (isset($_GET['labour_id']) && $_GET['labour_id'] == $labour['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($labour['name']) ?> (<?= htmlspecialchars($labour['mobile']) ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Search</button>
</form>

</body>
</html>
