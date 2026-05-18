<?php
session_start();
include 'includes/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mobile = trim($_POST['mobile']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM contractor WHERE mobile = ?");
    if ($stmt) {
        $stmt->bind_param("s", $mobile);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = 'contractor';

                header("Location: contractor_dashboard.php");
                exit();
            } else {
                $error = "❌ पासवर्ड गलत है।";
            }
        } else {
            $error = "❌ मोबाइल नंबर गलत है या अकाउंट नहीं है।";
        }

        $stmt->close();
    } else {
        $error = "❌ सर्वर त्रुटि, कृपया बाद में प्रयास करें।";
    }
}
?>



<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="UTF-8">
  <title>कॉण्ट्रैक्टर लॉगिन</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      background: linear-gradient(to bottom right, #a1c4fd, #c2e9fb);
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      text-align: center;
    }

    h2 {
      color: #333;
    }

    .form-box {
      background: #fff;
      padding: 25px;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      max-width: 350px;
      width: 90%;
    }

    input {
      width: 90%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 10px;
      border: 1px solid #ccc;
      font-size: 16px;
    }

    button {
      padding: 12px 30px;
      background: #4CAF50;
      border: none;
      color: #fff;
      font-size: 16px;
      border-radius: 10px;
      cursor: pointer;
    }

    button:hover {
      background: #45a049;
    }

    .link {
      margin-top: 15px;
      font-size: 0.9em;
    }

    .error {
      color: red;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="form-box">
    <h2>👷‍♂️ कॉण्ट्रैक्टर लॉगिन</h2>
    <?php if($error): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="mobile" placeholder="📱 मोबाइल नंबर" required><br>
      <input type="password" name="password" placeholder="🔒 पासवर्ड" required><br>
      <button type="submit">लॉगिन करें</button>
    </form>
    <div class="link">
      🙋 नया अकाउंट? <a href="contractor_register.php">रजिस्टर करें</a>
    </div>
  </div>
</body>
</html>
