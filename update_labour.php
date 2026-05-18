<?php
include 'includes/session.php';
include 'includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: show_labours.php");
    exit();
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM labours WHERE id = $id");
$labour = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>✏️ मज़दूर संपादित करें</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: #f2faff;
        }

        header {
            background-color: #2196f3;
            color: white;
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .header-btn {
            background: white;
            color: #2196f3;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
        }

        .header-title {
            text-align: center;
            flex-grow: 1;
            font-weight: bold;
        }

        .container {
            padding: 15px;
        }

        form {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        .btn {
            background-color: #4caf50;
            color: white;
            padding: 10px;
            margin-top: 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
        }

        .password-toggle {
            position: relative;
        }

        .toggle-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .change-password-toggle {
            margin-top: 15px;
            background: #2196f3;
            color: white;
            padding: 8px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        #change-password-section {
            display: none;
            margin-top: 10px;
        }

        .popup {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #4caf50;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            z-index: 9999;
            display: none;
        }

        @media(max-width: 500px) {
            .header-title {
                font-size: 14px;
            }
            .header-btn {
                font-size: 12px;
                padding: 4px 8px;
            }
        }
    </style>
</head>
<body>

<header>
    <a href="show_labours.php" class="header-btn">⬅️ Back</a>
    <div class="header-title">❤️ Made by Pratik ❤️</div>
    <a href="contractor_dashboard.php" class="header-btn">🏠 Dashboard</a>
</header>

<div class="container">
    <form id="updateForm">
        <input type="hidden" name="id" value="<?= $labour['id'] ?>">

        <label>👤 नाम</label>
        <input type="text" name="name" value="<?= $labour['name'] ?>" required>

        <label>📱 मोबाइल</label>
<div style="display: flex; align-items: center;">
    <span style="padding: 10px; background: #eee; border: 1px solid #ccc; border-radius: 8px 0 0 8px;">+91</span>
    <input type="tel" name="mobile" maxlength="10" pattern="\d{10}" value="<?= $labour['mobile'] ?>" required style="border-radius: 0 8px 8px 0; border-left: none; flex: 1;">
</div>
<small style="margin-left: 5px;">10 अंकों का मोबाइल नंबर डालें</small>


        <label>💼 भूमिका</label>
        <input type="text" name="role" value="<?= $labour['role'] ?>" required>

        <label>💰 वेतन प्रकार</label>
        <select name="salary_type" required>
            <option value="प्रतिदिन" <?= $labour['salary_type'] == 'प्रतिदिन' ? 'selected' : '' ?>>प्रतिदिन</option>
            <option value="प्रतिमाह" <?= $labour['salary_type'] == 'प्रतिमाह' ? 'selected' : '' ?>>प्रतिमाह</option>
        </select>

        <label>₹ वेतन</label>
        <input type="number" name="salary_amount" value="<?= $labour['salary_amount'] ?>" required>

        <button type="button" class="change-password-toggle" onclick="togglePassword()">🔒 पासवर्ड बदलें</button>

        <div id="change-password-section">
            <label>🔑 नया पासवर्ड</label>
            <div class="password-toggle">
                <input type="password" id="new_password" name="password">
                <span class="toggle-icon" onclick="toggleVisibility('new_password')">👁️</span>
            </div>

            <label>✅ कन्फर्म पासवर्ड</label>
            <div class="password-toggle">
                <input type="password" id="confirm_password">
                <span class="toggle-icon" onclick="toggleVisibility('confirm_password')">👁️</span>
            </div>
        </div>

        <button type="submit" class="btn">💾 अपडेट करें</button>
    </form>
</div>

<div class="popup" id="popup">✅ सफलतापूर्वक अपडेट हो गया!</div>

<script>
    function togglePassword() {
        const section = document.getElementById('change-password-section');
        section.style.display = section.style.display === 'none' ? 'block' : 'none';
    }

    function toggleVisibility(id) {
        const input = document.getElementById(id);
        input.type = input.type === "password" ? "text" : "password";
    }

    document.getElementById("updateForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const passwordSection = document.getElementById('change-password-section');
        const password = document.getElementById('new_password').value;
        const confirm = document.getElementById('confirm_password').value;

        // Check if password section is visible and validate passwords
        if (passwordSection.style.display === 'block') {
            if (password === '' || confirm === '' || password !== confirm) {
                alert("🔁 पासवर्ड मेल नहीं खा रहा है!");
                return;
            } else {
                formData.append("password", password); // Add only if valid
            }
        }

        fetch('update_labour_backend.php', {
            method: 'POST',
            body: formData
        }).then(res => res.json()).then(data => {
            if (data.success) {
                // Update fields in form
                document.querySelector("input[name='name']").value = data.labour.name;
                document.querySelector("input[name='mobile']").value = data.labour.mobile;
                document.querySelector("input[name='role']").value = data.labour.role;
                document.querySelector("select[name='salary_type']").value = data.labour.salary_type;
                document.querySelector("input[name='salary_amount']").value = data.labour.salary_amount;

                // Reset password section
                document.getElementById('new_password').value = '';
                document.getElementById('confirm_password').value = '';
                passwordSection.style.display = 'none'; // Hide again

                // Show popup
                const popup = document.getElementById('popup');
                popup.innerText = "✅ सफलतापूर्वक अपडेट हो गया!";
                popup.style.display = 'block';
                setTimeout(() => {
                    popup.style.display = 'none';
                }, 3000);
            } else {
                alert("❌ कुछ गड़बड़ हुई!");
            }
        }).catch(err => {
            console.error(err);
            alert("⚠️ सर्वर से कनेक्ट नहीं हो पाया।");
        });
    });
</script>


</body>
</html>
