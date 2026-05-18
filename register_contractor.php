<?php
include 'includes/db.php';
include 'includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if mobile already exists
    $check = $conn->prepare("SELECT * FROM contractors WHERE mobile = ?");
    $check->bind_param("s", $mobile);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $error = "Mobile number already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO contractors (name, mobile, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $mobile, $password);

        if ($stmt->execute()) {
            $success = "Account created successfully! You can now login.";
        } else {
            $error = "Something went wrong.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Contractor</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h2>📝 Create Contractor Account</h2>

    <?php if (isset($success)) showAlert("success", $success); ?>
    <?php if (isset($error)) showAlert("danger", $error); ?>

    <form method="POST">
        <label>Full Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Mobile Number:</label><br>
        <input type="text" name="mobile" pattern="\d{10}" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>
