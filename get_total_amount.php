<?php
include 'includes/session.php';        // ✅ SESSION सबसे पहले
include 'includes/db.php';
date_default_timezone_set("Asia/Kolkata");

$contractor_id = $_SESSION['user_id'] ?? 0;
$labourID      = $_POST['labour_id'] ?? 0;
$today         = date('Y-m-d');

if (!$contractor_id || !$labourID) {
    echo json_encode(['success'=>false,'message'=>'Invalid labour ID or contractor ID']);
    exit;
}

/* 1️⃣ — लेबर का वेरिफिकेशन */
$check = $conn->prepare(
    "SELECT id FROM labours WHERE id = ? AND contractor_id = ? AND is_deleted = 0"
);
$check->bind_param("ii", $labourID, $contractor_id);
$check->execute();
if ($check->get_result()->num_rows === 0) {
    echo json_encode(['success'=>false,'message'=>'मज़दूर नहीं मिला']);
    exit;
}

/* 2️⃣ — पिछली payment detail */
$last = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT to_date, remaining_amount
     FROM payments
     WHERE labour_id = '$labourID' AND contractor_id = '$contractor_id'
     ORDER BY to_date DESC LIMIT 1"
));

$previousRemaining = (float)($last['remaining_amount'] ?? 0);
$fromDate = $last ? date('Y-m-d', strtotime($last['to_date'].' +1 day')) : '2024-01-01';

/* 3️⃣ — Attendance total */
$attendanceTotal = (float)mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT SUM(COALESCE(custom_amount, salary_amount) + IFNULL(a.bonus,0)) AS total
     FROM attendance a
     WHERE a.labour_id = '$labourID'
       AND a.contractor_id = '$contractor_id'
       AND a.date BETWEEN '$fromDate' AND '$today'
       AND (a.is_removed IS NULL OR a.is_removed = 0)"
))['total'];

/* 4️⃣ — Payments in this period (🛠️ FIXED DATE LOGIC) */
$paidThisPeriod = (float)mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT SUM(amount) AS paid
     FROM payments
     WHERE labour_id = '$labourID'
       AND contractor_id = '$contractor_id'
       AND to_date >= '$fromDate'
       AND from_date <= '$today'"
))['paid'];

/* 5️⃣ — Final calculation */
$totalAmount     = $attendanceTotal + $previousRemaining;
$remainingAmount = $totalAmount - $paidThisPeriod;

echo json_encode([
    'success'          => true,
    'total_amount'     => round($totalAmount,2),
    'remaining_amount' => round($remainingAmount,2),
    'from_date'        => $fromDate,
    'to_date'          => $today
]);
?>
