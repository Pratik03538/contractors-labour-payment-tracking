<?php
include 'includes/session.php';
include 'includes/db.php';

// Use consistent session variable for contractor ID
$contractorID = $_SESSION['user_id'] ?? null;
$contractorName = $_SESSION['name'] ?? 'कॉन्ट्रैक्टर';

if (!$contractorID) {
    die("Unauthorized access");
}

// Set timezone to India
date_default_timezone_set("Asia/Kolkata");

// Get today's date
$today = date("Y-m-d");

// Total active मज़दूर (not deleted) — contractor specific
$resultLabours = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM labours 
    WHERE contractor_id = '$contractorID' 
      AND (is_deleted IS NULL OR is_deleted = 0)
");
$labourCount = mysqli_fetch_assoc($resultLabours)['total'] ?? 0;

// Total attendance today (not removed) — contractor specific
$resultAttendance = mysqli_query($conn, "
    SELECT COUNT(DISTINCT labour_id) as total 
    FROM attendance 
    WHERE contractor_id = '$contractorID' 
      AND `date` = '$today' 
      AND (is_removed IS NULL OR is_removed = 0)
");
$attendanceCount = mysqli_fetch_assoc($resultAttendance)['total'] ?? 0;

// Total groups — contractor specific
$resultGroups = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM labour_groups 
    WHERE contractor_id = '$contractorID'
");
$groupCount = mysqli_fetch_assoc($resultGroups)['total'] ?? 0;

// Profile Image Logic
$profilePic = 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($contractorName);

$stmt = $conn->prepare("SELECT photo FROM contractor WHERE id = ?");
$stmt->bind_param("i", $contractorID);
$stmt->execute();
$stmt->bind_result($pic);
if ($stmt->fetch() && !empty($pic)) {
    $profilePic = $pic;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="UTF-8">
  <title>डैशबोर्ड - <?php echo $contractorName; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #dfe9f3, #ffffff);
      min-height: 100vh;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 20px;
      background-color: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .profile-pic {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      cursor: pointer;
      border: 2px solid #888;
      object-fit: cover;
    }

    .logout-btn {
      background: #ff4d4d;
      color: white;
      padding: 8px 14px;
      border: none;
      border-radius: 20px;
      font-size: 14px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .logout-btn:hover {
      background: #e60000;
    }

    .welcome {
      font-size: 1.6em;
      font-weight: bold;
      color: #333;
      margin: 20px;
      text-align: center;
      text-shadow: 1px 1px 2px #aaa;
    }

    .dashboard {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      padding: 0 20px;
    }

    .card {
      background: #ffffffcc;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 25px 15px;
      text-align: center;
      transition: transform 0.3s ease, background 0.3s ease;
      cursor: pointer;
    }

    .card:hover {
      transform: translateY(-5px);
      background: #ffeaa7;
    }

    .card h3 {
      margin: 0;
      font-size: 1.2em;
      color: #333;
    }

    .card span {
      font-size: 2.2em;
      display: block;
      margin-bottom: 8px;
    }

    .footer {
      text-align: center;
      font-size: 1em;
      color: #777;
      padding: 15px;
    }

    .footer span {
      color: red;
      font-size: 1.3em;
    }

    /* Popup Modal */
    .modal {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.4);
      display: none;
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      animation: fadeIn 0.3s ease;
    }

    .modal-content h2 {
      margin-bottom: 15px;
    }

    .modal-content button {
      margin: 8px;
      padding: 8px 16px;
      border: none;
      border-radius: 20px;
      background-color: #3498db;
      color: white;
      cursor: pointer;
    }

    .modal-content button:hover {
      background-color: #217dbb;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: scale(0.9);}
      to {opacity: 1; transform: scale(1);}
    }

    @media (max-width: 600px) {
      .welcome { font-size: 1.2em; }
    }

    .made-by {
      font-size: 16px;
      color: #444;
      font-weight: 500;
      text-shadow: 1px 1px #eee;
      padding: 8px 12px;
      border-radius: 20px;
      background: #f9f9f9;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      transition: all 0.3s ease-in-out;
      cursor: default;
    }

    .made-by:hover {
      background: #ffeaa7;
      transform: scale(1.05);
    }
   
    .highlight-badge {
    font-size: 16px;
    margin-top: 5px;
    padding: 6px 12px;
    border-radius: 20px;
    background: #fff4e6;
    color: #d35400;
    font-weight: bold;
    display: inline-block;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    transition: all 0.3s ease-in-out;
    animation: pulseBadge 2s infinite;
    cursor: default;
  }

  .highlight-badge:hover {
    background: #ffe0c2;
    color: #e67e22;
    transform: scale(1.05);
    box-shadow: 0 0 12px rgba(255, 153, 51, 0.5);
  }

  @keyframes pulseBadge {
    0% { transform: scale(1); }
    50% { transform: scale(1.06); }
    100% { transform: scale(1); }
  }
  </style>
</head>
<body>

<!-- Header -->
<div class="header">
  <img src="<?php echo $profilePic; ?>" alt="Profile" class="profile-pic" onclick="openProfileModal()">
  <div class="made-by">❤️ Made by <strong>Pratik</strong></div>
  <button class="logout-btn" onclick="openLogoutModal()">🚪 लॉगआउट</button>
</div>

<div class="welcome">🙏 स्वागत है, <strong><?php echo htmlspecialchars($contractorName); ?></strong> जी!</div>

<!-- Dashboard Cards -->
<div class="dashboard">
  <div class="card" onclick="window.location.href='attendance.php'">
    <span>📅</span><h3>उपस्थिति</h3>
    <div class="highlight-badge"><?php echo $attendanceCount; ?></div>
  </div>
  <div class="card" onclick="window.location.href='payment.php'">
    <span>➕</span><h3>payments</h3>
  </div>
  <div class="card" onclick="window.location.href='groups.php'">
  <span>👥</span><h3>ग्रुप्स</h3>
  <div class="highlight-badge"><?php echo $groupCount; ?></div>
</div>

<div class="card" onclick="window.location.href='labour_details.php'">
    <span>🗓️</span><h3>Activity</h3>
        <div class="highlight-badge"></div>
  </div>

  <div class="card" onclick="window.location.href='add_labour.php'">
    <span>➕</span><h3>लेबर जोड़ें</h3>
  </div>
  <div class="card" onclick="window.location.href='show_labours.php'">
    <span>📋</span><h3>लेबर सूची</h3>
    <div class="highlight-badge"><?php echo $labourCount; ?></div>
  </div>
  


   <div class="card" onclick="window.location.href='bonus.php'">
    <span>!</span><h3>Bonus/deduction</h3>
        <div class="highlight-badge"></div>

  </div>
  
  </div>
 
  
  
</div>

<!-- Footer -->
<div class="footer">Made with <span>❤️</span> by <strong>Pratik</strong></div>

<!-- Profile Modal -->
<div class="modal" id="profileModal">
  <div class="modal-content">
    <h2>📝 प्रोफ़ाइल अपडेट करें</h2>
    <p>आप यहाँ से अपनी प्रोफ़ाइल जानकारी अपडेट कर सकते हैं।</p>
    <button onclick="window.location.href='update_profile.php'">प्रोफ़ाइल अपडेट करें</button>
    <button onclick="closeProfileModal()">❌ बंद करें</button>
  </div>
</div>

<!-- Logout Modal -->
<div class="modal" id="logoutModal">
  <div class="modal-content">
    <h2>🚪 क्या आप बाहर निकलना चाहते हैं?</h2>
    <p>आप यहाँ से लॉगआउट कर सकते हैं या वापस जा सकते हैं।</p>
    <form method="post" action="logout.php">
      <button type="submit">हाँ, लॉगआउट करें</button>
      <button type="button" onclick="closeLogoutModal()">❌ वापस जाएं</button>
    </form>
  </div>
</div>

<script>
  function openProfileModal() {
    document.getElementById('profileModal').style.display = 'flex';
  }

  function closeProfileModal() {
    document.getElementById('profileModal').style.display = 'none';
  }

  function openLogoutModal() {
    document.getElementById('logoutModal').style.display = 'flex';
  }

  function closeLogoutModal() {
    document.getElementById('logoutModal').style.display = 'none';
  }
</script>

</body>
</html>
