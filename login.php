<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM contractors WHERE mobile = ?");
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $contractor = $result->fetch_assoc();

        if (password_verify($password, $contractor['password'])) {
            $_SESSION['user_id'] = $contractor['id'];
            $_SESSION['name'] = $contractor['name'];
            $_SESSION['role'] = 'contractor';

            header("Location: contractor_dashboard.php");
            exit();
        } else {
            echo "<script>alert('❌ Wrong password');</script>";
        }
    } else {
        echo "<script>alert('❌ Mobile number not found');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contractor Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h2>👷 Contractor Login</h2>
    <form method="post" style="max-width: 400px; margin: auto;">
        <label>📱 Mobile:</label><br>
        <input type="text" name="mobile" required style="width:100%; padding:8px;"><br><br>

        <label>🔒 Password:</label><br>
        <input type="password" name="password" required style="width:100%; padding:8px;"><br><br>

        <button type="submit" style="padding:10px 20px;">🔓 Login</button>
    </form>
</body>
</html>
