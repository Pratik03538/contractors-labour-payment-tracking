<?php
session_start();

// अगर user_id सेट है मतलब लॉगिन है, तो redirect कर दो contractor_dashboard.php (या जहाँ भी चाहिए)
if (isset($_SESSION['user_id'])) {
    header("Location: contractor_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>रोल चयन | Labour Management</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to bottom right, #74ebd5, #ACB6E5);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      overflow: hidden;
      position: relative;
      text-align: center;
    }

    h1 {
      font-size: 2em;
      color: #fff;
      margin-bottom: 0.4em;
      text-shadow: 2px 2px #444;
    }

    .quote {
      font-size: 1.1em;
      color: #f1f1f1;
      font-style: italic;
      margin-bottom: 2em;
      padding: 0 20px;
    }

    .btn {
      background: #ffffffcc;
      color: #333;
      padding: 15px 25px;
      margin: 12px;
      border: none;
      border-radius: 30px;
      font-size: 1.1em;
      width: 80%;
      max-width: 300px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .btn:hover {
      transform: scale(1.05);
      background: #fff176;
      color: #000;
    }

    .footer {
      position: absolute;
      bottom: 15px;
      font-size: 1em;
      color: #fff;
      opacity: 0.9;
    }

    .footer span {
      color: red;
      font-size: 1.3em;
    }

    .bubble {
      position: absolute;
      bottom: -100px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      animation: float 12s infinite ease-in;
      z-index: 0;
    }

    @keyframes float {
      0% {
        transform: translateY(0) scale(1);
        opacity: 1;
      }
      100% {
        transform: translateY(-120vh) scale(1.5);
        opacity: 0;
      }
    }

    @media (max-width: 480px) {
      h1 {
        font-size: 1.5em;
      }
      .quote {
        font-size: 1em;
      }
      .btn {
        font-size: 1em;
        padding: 12px 20px;
      }
    }
  </style>
</head>
<body>

  <h1>🙏 आपका स्वागत है!</h1>
  <div class="quote">"जो मेहनत करता है, वही असली नायक होता है।"</div>

  <a href="contractor_login.php" class="btn">👷‍♂️ मैं कॉन्ट्रैक्टर हूँ</a>
  <a href="labour_login.php" class="btn">👨‍🔧 मैं लेबर हूँ</a>

  <div class="footer">Made with <span>❤️</span> by <strong>Pratik</strong></div>

  <!-- Bubble effects -->
  <?php for ($i = 0; $i < 25; $i++): ?>
    <div class="bubble" style="
      width: <?= rand(20, 60) ?>px;
      height: <?= rand(20, 60) ?>px;
      left: <?= rand(0, 100) ?>%;
      animation-delay: <?= rand(0, 15) ?>s;
    "></div>
  <?php endfor; ?>

</body>
</html>
