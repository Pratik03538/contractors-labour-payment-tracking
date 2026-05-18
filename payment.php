<?php
include 'includes/db.php';
include 'includes/session.php';

// Use session user_id, else default 1 (better to handle unauthorized access separately)
$contractor_id = $_SESSION['user_id'] ?? 1;
?>

<!DOCTYPE html>
<html lang="hi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>मजदूरी भुगतान</title>
  <!-- Link your external CSS file -->
  <link rel="stylesheet" href="payment_css.css" />
</head>
<body>

<header>
  <h2>मजदूरी भुगतान</h2>
  <div class="nav-btns">
    <button onclick="window.location.href='contractor_dashboard.php'">डैशबोर्ड</button>
    <button onclick="window.location.href='payment_history.php'">भुगतान हिस्ट्री</button>
  </div>
</header>

<div class="container">
  <div class="search-box">
    <input type="text" id="search" placeholder="मजदूर का नाम खोजें..." autocomplete="off" />
    <button id="clearSearch" title="सर्च क्लियर करें">❌</button>
  </div>

  <div class="labour-list" id="labourList">
    <?php
    // Secure query with prepared statements (recommended), but here simple query for demo
    $labourQuery = mysqli_query($conn, "SELECT id, name, mobile FROM labours WHERE contractor_id='$contractor_id' AND is_deleted=0 ORDER BY name ASC");
    if ($labourQuery) {
        while ($labour = mysqli_fetch_assoc($labourQuery)) {
            // Escape output for safety
            $id = htmlspecialchars($labour['id']);
            $name = htmlspecialchars($labour['name']);
            $mobile = htmlspecialchars($labour['mobile']);
            echo "<div class='labour-item' data-id='$id' data-name='$name' data-mobile='$mobile'>$name ($mobile)</div>";
        }
    } else {
        echo "<div>मजदूरों को लाने में त्रुटि हुई।</div>";
    }
    ?>
  </div>

  <div class="form-section" id="paymentForm" style="display:none;">
    <div class="amount-box">नाम: <span id="labourName">-</span></div>
    <div class="amount-box">मोबाइल: <span id="labourMobile">-</span></div>
    <div class="amount-box">Total Payable: <span id="payable">₹0</span></div>
    <div class="amount-box">Remaining: <span id="remaining">₹0</span></div>
    <div class="amount-box" style="color: blue;">Updated Remaining (after entry): ₹<span id="tempRemaining">0</span></div>
    <input type="number" id="amount" placeholder="दिया गया amount ₹" min="0" step="0.01" />
    <textarea id="note" placeholder="कोई टिप्पणी (optional)"></textarea>
    <button onclick="submitPayment()">भुगतान करें</button>
  </div>
</div>

<!-- Confirmation Popup -->
<div id="confirmationPopup" class="popup" style="display:none;">
  <div class="popup-content">
    <p>✅ भुगतान सफलतापूर्वक हो गया!</p>
  </div>
</div>

<script>
let selectedLabour = null;
let remaining = 0;

// Live search filtering
document.getElementById('search').addEventListener('input', function () {
  const term = this.value.toLowerCase();
  document.querySelectorAll('.labour-item').forEach(item => {
    item.style.display = item.textContent.toLowerCase().includes(term) ? 'block' : 'none';
  });
});

// Select labour on click
document.querySelectorAll('.labour-item').forEach(item => {
  item.addEventListener('click', function () {
    selectedLabour = this.dataset.id;
    document.getElementById('search').value = this.dataset.name + " (" + this.dataset.mobile + ")";
    document.getElementById('labourName').innerText = this.dataset.name;
    document.getElementById('labourMobile').innerText = this.dataset.mobile;
    document.getElementById('labourList').style.display = 'none';
    document.getElementById('paymentForm').style.display = 'block';
    fetchPayableAmount(selectedLabour);
  });
});

// Show labour list on search input focus
document.getElementById('search').addEventListener('focus', () => {
  if (!selectedLabour) {
    document.getElementById('labourList').style.display = 'block';
  }
});

// Clear search and reset form
document.getElementById('clearSearch').addEventListener('click', function () {
  document.getElementById('search').value = '';
  selectedLabour = null;
  document.getElementById('labourList').style.display = 'block';
  document.getElementById('paymentForm').style.display = 'none';
});

// Fetch payment info via AJAX
function fetchPayableAmount(labourId) {
  fetch('get_labour_payment_info.php?labour_id=' + encodeURIComponent(labourId))
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        document.getElementById('payable').innerText = `₹${data.total_amount}`;
        document.getElementById('remaining').innerText = `₹${data.remaining_amount}`;
        remaining = parseFloat(data.remaining_amount);
        updateTempRemaining();
      } else {
        alert("Error: " + data.message);
      }
    })
    .catch(err => {
      console.error(err);
      alert("Server error! कृपया बाद में पुनः प्रयास करें।");
    });
}

// Update temp remaining on amount input
document.getElementById('amount').addEventListener('input', updateTempRemaining);
function updateTempRemaining() {
  const entered = parseFloat(document.getElementById('amount').value);
  const temp = isNaN(entered) ? remaining : (remaining - entered);
  document.getElementById('tempRemaining').innerText = temp.toFixed(2);
}

// Submit payment via AJAX
function submitPayment() {
  const amount = parseFloat(document.getElementById('amount').value);
  const note = document.getElementById('note').value.trim();

  if (!selectedLabour || isNaN(amount) || amount <= 0) {
    alert("कृपया सही amount भरें और मज़दूर चुनें!");
    return;
  }

  fetch('process_payment.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: new URLSearchParams({
      labour_id: selectedLabour,
      amount: amount,
      note: note
    })
  })
  .then(res => res.text())
  .then(response => {
    if (response.includes("✅")) {
      document.getElementById('confirmationPopup').style.display = 'flex';
      setTimeout(() => {
        window.location.href = 'payment_history.php';
      }, 1500);
    } else {
      alert(response);
    }
  })
  .catch(err => {
    console.error(err);
    alert("सर्वर त्रुटि! कृपया बाद में पुनः प्रयास करें।");
  });
}
</script>

</body>
</html>
