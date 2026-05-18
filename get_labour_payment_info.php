<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/session.php';
include 'includes/db.php';

header('Content-Type: application/json; charset=utf-8');

$labour_id = isset($_GET['labour_id']) ? (int)$_GET['labour_id'] : 0;
$contractor_id = $_SESSION['user_id'] ?? 0;

if (!$labour_id || !$contractor_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid labour ID or contractor ID']);
    exit;
}

date_default_timezone_set("Asia/Kolkata");
$today = date("Y-m-d");

// Step 1: Get labour details
$stmt = $conn->prepare("SELECT name, mobile, salary_amount FROM labours WHERE id = ? AND contractor_id = ? AND is_deleted = 0");
$stmt->bind_param("ii", $labour_id, $contractor_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'मज़दूर नहीं मिला']);
    exit;
}
$labour = $result->fetch_assoc();
$salary = (float)$labour['salary_amount'];
$stmt->close();

// Step 2: Get last payment info
$stmt = $conn->prepare("SELECT to_date, remaining_amount FROM payments WHERE labour_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("i", $labour_id);
$stmt->execute();
$result = $stmt->get_result();
$last_payment = $result->fetch_assoc();
$stmt->close();

$previousRemaining = 0;
$fromDate = '2024-01-01'; // default if no payment

if ($last_payment) {
    $previousRemaining = (float)$last_payment['remaining_amount'];
    if (!empty($last_payment['to_date'])) {
        $fromDate = date('Y-m-d', strtotime($last_payment['to_date'] . ' +1 day'));
    }
}

// Step 3: Calculate attendance-based salary
$stmt = $conn->prepare("
    SELECT SUM(
        (CASE 
            WHEN a.status = 'full' THEN ?
            WHEN a.custom_amount IS NOT NULL AND a.custom_amount > 0 THEN a.custom_amount
            ELSE 0
        END) + COALESCE(a.bonus, 0)
    ) AS total
    FROM attendance a
    WHERE a.labour_id = ? AND a.contractor_id = ?
      AND a.date BETWEEN ? AND ?
      AND (a.is_removed IS NULL OR a.is_removed = 0)
");
$stmt->bind_param("diiss", $salary, $labour_id, $contractor_id, $fromDate, $today);
$stmt->execute();
$result = $stmt->get_result();
$attRow = $result->fetch_assoc();
$stmt->close();

$attendanceTotal = (float)($attRow['total'] ?? 0);

// Step 4: Get total already paid in that period (safety check)
$stmt = $conn->prepare("SELECT SUM(payed_amount) AS payed FROM payments WHERE labour_id = ? AND from_date >= ? AND to_date <= ?");
$stmt->bind_param("iss", $labour_id, $fromDate, $today);
$stmt->execute();
$paidResult = $stmt->get_result()->fetch_assoc();
$stmt->close();

$payed = (float)($paidResult['payed'] ?? 0);

// Step 5: Final calculation
$totalAmount = $attendanceTotal + $previousRemaining;
$remainingAmount = $totalAmount - $payed;

// Final Response
echo json_encode([
    'success' => true,
    'name' => $labour['name'],
    'mobile' => $labour['mobile'],
    'total_amount' => round($totalAmount, 2),
    'remaining_amount' => round($remainingAmount, 2),
    'from_date' => $fromDate,
    'to_date' => $today
]);
?>
