<?php
include 'includes/session.php';
include 'includes/db.php';

$message = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $mobile = trim($_POST['mobile']);
    $role = $_POST['role'];
    $salary_type = $_POST['salary_type'];
    $salary_amount = $_POST['salary_amount'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $contractor_id = $_SESSION['user_id'];
    $timestamp = date('Y-m-d H:i:s');

    if ($password !== $confirm_password) {
        $message = "❌ पासवर्ड मेल नहीं खा रहे हैं!";
    } elseif (!preg_match('/^\d{10}$/', $mobile)) {
        $message = "📱 मोबाइल नंबर 10 अंकों का होना चाहिए!";
    } else {
        // Check if mobile already exists
        $check_query = "SELECT id FROM labours WHERE mobile = '$mobile' AND contractor_id = '$contractor_id'";
        $check_result = mysqli_query($conn, $check_query);
    
        if (mysqli_num_rows($check_result) > 0) {
            $message = "❌ यह मोबाइल नंबर पहले से रजिस्टर है, कृपया कोई और नंबर डालें!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
            $query = "INSERT INTO labours (name, mobile, role, salary_type, salary_amount, timestamp, password, contractor_id) 
                      VALUES ('$name', '$mobile', '$role', '$salary_type', '$salary_amount', '$timestamp', '$hashed_password', '$contractor_id')";
    
            if (mysqli_query($conn, $query)) {
                $success = "🎉 मज़दूर सफलतापूर्वक जोड़ा गया!";
            } else {
                $message = "❌ कुछ गलत हो गया! " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>मज़दूर जोड़ें</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f0f8ff;
            margin: 0;
            padding: 0;
        }
        .header {
            background: #aee1f9;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .header a {
            text-decoration: none;
            background: #ffffffaa;
            padding: 10px 16px;
            margin: 5px;
            border-radius: 12px;
            font-weight: bold;
            color: #444;
            transition: 0.3s ease;
            white-space: nowrap;
        }
        .header a:hover {
            background: #fff;
            transform: scale(1.05);
        }
        .container {
            max-width: 600px;
            background: #fff;
            margin: 40px auto;
            padding: 25px 30px;
            border-radius: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-size: 1.8rem;
        }
        label {
            font-size: 17px;
            margin-bottom: 6px;
            display: block;
            color: #444;
        }
        input, select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 18px;
            border-radius: 12px;
            border: 1px solid #ccc;
            font-size: 15px;
            box-sizing: border-box;
        }
        button {
            background: #28a745;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: 0.3s ease;
        }
        button:hover {
            background: #218838;
            transform: scale(1.03);
        }
        .msg {
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }
        @media (max-width: 640px) {
            .container {
                margin: 20px 15px;
                padding: 20px 15px;
                border-radius: 15px;
            }
            
            h2 {
                font-size: 1.5rem;
            }
            
            .header {
                flex-direction: column;
                align-items: stretch;
                padding: 15px;
            }
            
            .header a {
                width: 100%;
                text-align: center;
                margin: 6px 0;
                white-space: normal;
                font-size: 1.1rem;
            }

            /* Mobile: Mobile input group adjustment */
            .mobile-group {
                display: flex;
                gap: 0;
                margin-bottom: 18px;
            }
            
            .mobile-group span {
                padding: 10px 14px;
                background: #eee;
                border-radius: 12px 0 0 12px;
                border: 1px solid #ccc;
                border-right: none;
                font-size: 16px;
                line-height: 1.2;
                user-select: none;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .mobile-group input {
                flex: 1;
                border-radius: 0 12px 12px 0;
                border: 1px solid #ccc;
                padding: 10px 12px;
                font-size: 16px;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <a href="contractor_dashboard.php">🏠 डैशबोर्ड पर जाएं</a>
    <a href="show_labours.php">📋 मज़दूर सूची देखें</a>
</div>

<div class="container">
    <h2>➕ नया मज़दूर जोड़ें</h2>

    <?php if ($message): ?>
        <div class="msg" style="color:red;"><?= htmlspecialchars($message) ?></div>
    <?php elseif ($success): ?>
        <div class="msg" style="color:green;"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <label>👤 नाम:</label>
        <input type="text" name="name" required autocomplete="off">

        <label>📱 मोबाइल नंबर:</label>
        <div class="mobile-group">
            <span>+91</span>
            <input type="text" name="mobile" required maxlength="10" pattern="\d{10}" autocomplete="off">
        </div>

        <label>🎯 भूमिका:</label>
        <input type="text" name="role" required autocomplete="off">

        <label>💰 वेतन प्रकार:</label>
        <select name="salary_type" required>
            <option value="Daily">📆 प्रति दिन</option>
            <option value="Monthly">📅 मासिक</option>
        </select>

        <label>💵 वेतन राशि:</label>
        <input type="number" name="salary_amount" required autocomplete="off" placeholder="₹ राशि दर्ज करें">

        <label>🔑 पासवर्ड:</label>
        <input type="password" name="password" required autocomplete="new-password">

        <label>🔁 कन्फर्म पासवर्ड:</label>
        <input type="password" name="confirm_password" required autocomplete="new-password">

        <button type="submit" name="submit">👷 मज़दूर जोड़ें</button>
    </form>
</div>

</body>
</html>
