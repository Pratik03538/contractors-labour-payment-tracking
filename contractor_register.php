<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="UTF-8">
  <title>कॉन्ट्रैक्टर रजिस्ट्रेशन</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to bottom right, #f4e2d8, #ba5370);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
      text-align: center;
    }

    h2 {
      color: #fff;
      margin-bottom: 20px;
      text-shadow: 1px 1px 3px #444;
    }

    form {
      background-color: #ffffffcc;
      padding: 25px;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      width: 90%;
      max-width: 400px;
    }

    input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 10px;
      border: 1px solid #ccc;
      font-size: 16px;
    }

    button {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      background-color: #ff9a9e;
      border: none;
      border-radius: 10px;
      font-size: 18px;
      color: white;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    button:hover {
      background-color: #ff6a88;
    }

    .login-link {
      margin-top: 15px;
      font-size: 0.95em;
      color: #fff;
    }

    .login-link a {
      color: #000;
      font-weight: bold;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    .error {
      color: red;
      margin: 10px 0;
    }

    .success {
      color: green;
      margin: 10px 0;
    }

  </style>
</head>
<body>

  <h2>🔐 कॉन्ट्रैक्टर अकाउंट बनाएं</h2>

  <?php
    include 'includes/db.php';

    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $mobile = trim($_POST['mobile'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');

        if ($password !== $confirm_password) {
            $message = "<div class='error'>❌ पासवर्ड मेल नहीं खाते!</div>";
        } else {
            // Check if mobile already exists
            $check = $conn->prepare("SELECT * FROM contractor WHERE mobile = ?");
            $check->bind_param("s", $mobile);
            $check->execute();
            $result = $check->get_result();
            if ($result->num_rows > 0) {
                $message = "<div class='error'>⚠️ यह मोबाइल नंबर पहले से रजिस्टर्ड है।</div>";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO contractor (name, mobile, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $mobile, $hashed_password);
                if ($stmt->execute()) {
                    $message = "<div class='success'>✅ रजिस्ट्रेशन सफल! अब आप लॉगिन कर सकते हैं।</div>";
                } else {
                    $message = "<div class='error'>❌ कुछ गलत हुआ, कृपया फिर से प्रयास करें।</div>";
                }
            }
        }
    }
    echo $message;
  ?>

  <form method="post">
    <input type="text" name="name" placeholder="पूरा नाम" required>
    <input type="text" name="mobile" placeholder="मोबाइल नंबर" required pattern="[0-9]{10}">
    <input type="password" name="password" placeholder="पासवर्ड" required>
    <input type="password" name="confirm_password" placeholder="पासवर्ड दोबारा लिखें" required>
    <button type="submit">📝 रजिस्टर करें</button>
  </form>

  <div class="login-link">
    पहले से अकाउंट है? <a href="contractor_login.php">यहाँ लॉगिन करें</a> 🔑
  </div>

</body>
</html>
