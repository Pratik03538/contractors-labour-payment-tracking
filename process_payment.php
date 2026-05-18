<?php
include 'includes/session.php';
include 'includes/db.php';
date_default_timezone_set("Asia/Kolkata");

$labourID = (int) ($_POST['labour_id'] ?? 0);
$payedAmount = (float) ($_POST['amount'] ?? 0);
$note = trim($_POST['note'] ?? '');
$today = date('Y-m-d');
$contractorID = $_SESSION['user_id'] ?? 0;

// Validation
if ($labourID <= 0 || $payedAmount <= 0 || $contractorID <= 0) {
    echo "❌ कृपया मान्य मज़दूर और भुगतान राशि दें!";
    exit;
}

// Step 1: Get Labour Salary
$stmt = $conn->prepare("SELECT salary_amount FROM labours WHERE id = ? AND contractor_id = ?");
$stmt->bind_param("ii", $labourID, $contractorID);
$stmt->execute();
$result = $stmt->get_result();
$labour = $result->fetch_assoc();
$stmt->close();

if (!$labour) {
    echo "❌ मज़दूर नहीं मिला!";
    exit;
}
$salary = (float) $labour['salary_amount'];

// Step 2: Get Last Payment (if any)
$stmt = $conn->prepare("SELECT to_date, remaining_amount FROM payments WHERE labour_id = ? AND contractor_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("ii", $labourID, $contractorID);
$stmt->execute();
$lastPayment = $stmt->get_result()->fetch_assoc();
$stmt->close();

$previousRemaining = 0;
$fromDate = '2024-01-01';

if ($lastPayment) {
    $previousRemaining = (float) $lastPayment['remaining_amount'];
    $fromDate = date('Y-m-d', strtotime($lastPayment['to_date'] . ' +1 day'));
}

// Step 3: Attendance Calculation
$stmt = $conn->prepare("
    SELECT SUM(
        CASE 
            WHEN a.status = 'full' THEN ?
            WHEN a.custom_amount IS NOT NULL AND a.custom_amount > 0 THEN a.custom_amount
            ELSE 0
        END + COALESCE(a.bonus, 0)
    ) AS total
    FROM attendance a
    WHERE a.labour_id = ? AND a.contractor_id = ? 
        AND a.date BETWEEN ? AND ? 
        AND (a.is_removed IS NULL OR a.is_removed = 0)
");
$stmt->bind_param("diiss", $salary, $labourID, $contractorID, $fromDate, $today);
$stmt->execute();
$attRow = $stmt->get_result()->fetch_assoc();
$stmt->close();

$attendanceTotal = (float) ($attRow['total'] ?? 0);
$totalAmount = $attendanceTotal + $previousRemaining;
$remainingAmount = $totalAmount - $payedAmount;

// Step 4: Check Limits
if ($remainingAmount < -200) {
    echo "❌ भुगतान ₹-200 से ज्यादा माइनस नहीं हो सकता!";
    exit;
}

// Step 5: Save Payment
$stmt = $conn->prepare("
    INSERT INTO payments (labour_id, contractor_id, total_amount, payed_amount, remaining_amount, from_date, to_date, created_at, note)
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)
");
$stmt->bind_param("iidddsss", $labourID, $contractorID, $totalAmount, $payedAmount, $remainingAmount, $fromDate, $today, $note);

if ($stmt->execute()) {
    echo "✅ भुगतान सफल! कुल: ₹$totalAmount, बकाया: ₹$remainingAmount";
} else {
    echo "❌ DB त्रुटि: " . $stmt->error;
}
$stmt->close();
?>
