<?php
include 'includes/session.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$contractor_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, name FROM labours WHERE contractor_id = ? AND is_deleted = 0 ORDER BY name ASC");
$stmt->bind_param("i", $contractor_id);
$stmt->execute();
$labours = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$selected_labour_id = isset($_GET['labour_id']) ? (int)$_GET['labour_id'] : (count($labours) ? $labours[0]['id'] : 0);

$default_from = date('Y-m-d', strtotime('-30 days'));
$default_to = date('Y-m-d');

$from_date = $_GET['from_date'] ?? $default_from;
$to_date = $_GET['to_date'] ?? $default_to;

$dates = [];
$data_by_date = [];

if ($selected_labour_id) {
    $stmt = $conn->prepare("SELECT date, status, IFNULL(bonus,0) AS bonus FROM attendance WHERE labour_id = ? AND date BETWEEN ? AND ? AND (is_removed IS NULL OR is_removed = 0) ORDER BY date DESC");
    $stmt->bind_param("iss", $selected_labour_id, $from_date, $to_date);
    $stmt->execute();
    $attendance_rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $stmt = $conn->prepare("SELECT from_date, to_date, payed_amount FROM payments WHERE labour_id = ? AND ((from_date BETWEEN ? AND ?) OR (to_date BETWEEN ? AND ?)) ORDER BY to_date DESC");
    $stmt->bind_param("issss", $selected_labour_id, $from_date, $to_date, $from_date, $to_date);
    $stmt->execute();
    $payment_rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $period = new DatePeriod(
        new DateTime($from_date),
        new DateInterval('P1D'),
        (new DateTime($to_date))->modify('+1 day')
    );
    foreach ($period as $date) {
        $dates[] = $date->format('Y-m-d');
    }
    rsort($dates);

    foreach ($attendance_rows as $att) {
        $date = $att['date'];
        if (!isset($data_by_date[$date])) {
            $data_by_date[$date] = [
                'attendance_status' => [],
                'bonus' => 0,
                'deduction' => 0,
                'payment' => 0
            ];
        }
        $data_by_date[$date]['attendance_status'][] = $att['status'];

        $bonus = (float)$att['bonus'];
        if ($bonus >= 0) {
            $data_by_date[$date]['bonus'] += $bonus;
        } else {
            $data_by_date[$date]['deduction'] += abs($bonus);
        }
    }

    foreach ($payment_rows as $pay) {
        $pay_to = $pay['to_date'];
        $payed_amount = (float)$pay['payed_amount'];

        if (!isset($data_by_date[$pay_to])) {
            $data_by_date[$pay_to] = [
                'attendance_status' => [],
                'bonus' => 0,
                'deduction' => 0,
                'payment' => 0
            ];
        }
        $data_by_date[$pay_to]['payment'] += $payed_amount;
    }

    $dates = array_filter($dates, function($d) use ($data_by_date) {
        return isset($data_by_date[$d]) && (
            !empty($data_by_date[$d]['attendance_status']) ||
            $data_by_date[$d]['bonus'] > 0 ||
            $data_by_date[$d]['deduction'] > 0 ||
            $data_by_date[$d]['payment'] > 0
        );
    });
}

// --- हिंदी दिन के नाम वाला फंक्शन ---
function getHindiDayName($date_str) {
    $days = [
        'Sunday' => 'रविवार',
        'Monday' => 'सोमवार',
        'Tuesday' => 'मंगलवार',
        'Wednesday' => 'बुधवार',
        'Thursday' => 'गुरुवार',
        'Friday' => 'शुक्रवार',
        'Saturday' => 'शनिवार'
    ];
    $day_en = date('l', strtotime($date_str)); // अंग्रेज़ी दिन का पूरा नाम
    return $days[$day_en] ?? $day_en;
}
?>

<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>मजदूर गतिविधि</title>
  <link rel="stylesheet" href="activity.css" />

<script>
window.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('labourDropdown');
  if (!container) return;

  const input = container.querySelector('.custom-select-input');
  const dropdown = container.querySelector('.custom-select-dropdown');
  const hiddenInput = container.querySelector('#labour_id');

  const options = <?= json_encode(array_map(function($labour) {
    return ['id' => $labour['id'], 'name' => $labour['name']];
  }, $labours)); ?>;

  function showDropdown(filteredOptions) {
    dropdown.innerHTML = '';
    if (filteredOptions.length === 0) {
      dropdown.innerHTML = '<div style="color:#999; padding: 8px;">कोई परिणाम नहीं मिला</div>';
    } else {
      filteredOptions.forEach(opt => {
        const div = document.createElement('div');
        div.textContent = opt.name;
        div.dataset.id = opt.id;
        dropdown.appendChild(div);
      });
    }
    dropdown.classList.add('active');
  }

  showDropdown(options);

  input.addEventListener('input', () => {
    const val = input.value.trim().toLowerCase();
    const filtered = options.filter(o => o.name.toLowerCase().includes(val));
    showDropdown(filtered);
  });

  input.addEventListener('focus', () => {
    showDropdown(options);
  });

  dropdown.addEventListener('click', (e) => {
    if (e.target.dataset.id) {
      input.value = e.target.textContent;
      hiddenInput.value = e.target.dataset.id;
      dropdown.classList.remove('active');
      document.getElementById('filterForm').submit();
    }
  });

  document.addEventListener('click', (e) => {
    if (!container.contains(e.target)) {
      dropdown.classList.remove('active');
    }
  });

  if (hiddenInput.value) {
    const selected = options.find(o => o.id == hiddenInput.value);
    if (selected) {
      input.value = selected.name;
    }
  }
});
</script>
</head>
<body>

<div class="back-button">
  <a href="contractor_dashboard.php">&larr;  वापस जाएं</a>
</div>

<h2>मजदूर गतिविधि विवरण</h2>

<form method="get" action="labour_details.php" id="filterForm" autocomplete="off">
  <div class="custom-select-container" id="labourDropdown">
    <input type="text" class="custom-select-input" placeholder="मजदूर चुनें..." autocomplete="off" />
    <div class="custom-select-dropdown"></div>
    <input type="hidden" name="labour_id" id="labour_id" value="<?= htmlspecialchars($selected_labour_id) ?>" />
  </div>

  <div class="date-row">
    <input type="date" name="from_date" value="<?= htmlspecialchars($from_date) ?>" required />
    <input type="date" name="to_date" value="<?= htmlspecialchars($to_date) ?>" required />
  </div>

  <button type="submit">फ़िल्टर करें</button>
</form>


<?php if ($selected_labour_id): ?>
<table>
  <thead>
    <tr>
      <th>तारीख़</th>
      <th>हाज़िरी</th>
      <th>बोनस</th>
      <th>कटौती</th>
      <th>भुगतान</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($dates as $date): ?>
    <tr>
      <td><?= getHindiDayName($date) . ', ' . date('d M Y', strtotime($date)) ?></td>
      <td>
        <?php 
          if (!empty($data_by_date[$date]['attendance_status'])) {
            foreach ($data_by_date[$date]['attendance_status'] as $status) {
              echo '<span class="green">&#10004;</span> ';
            }
          } else {
            echo '<span class="empty">-</span>';
          }
        ?>
      </td>
      <td class="green">
        <?= $data_by_date[$date]['bonus'] > 0 ? '+₹' . number_format($data_by_date[$date]['bonus'], 2) : '<span class="empty">-</span>' ?>
      </td>
      <td class="red">
        <?= $data_by_date[$date]['deduction'] > 0 ? '-₹' . number_format($data_by_date[$date]['deduction'], 2) : '<span class="empty">-</span>' ?>
      </td>
      <td class="yellow">
        <?= $data_by_date[$date]['payment'] > 0 ? 'Paid ₹' . number_format($data_by_date[$date]['payment'], 2) : '<span class="empty">-</span>' ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
<p style="text-align:center; font-style: italic; color: #777;">कृपया ऊपर मजदूर और तारीख़ सीमा चुनें ताकि गतिविधि देखें।</p>
<?php endif; ?>

</body>
</html>
