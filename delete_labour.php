<?php
include 'includes/session.php';
include 'includes/db.php';

header('Content-Type: application/json');

$contractorID = $_SESSION['user_id'] ?? null;

// Check if POST request and ID is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Optional: validate if this labour belongs to current contractor
    // You can uncomment this if needed:
    // $check = mysqli_query($conn, "SELECT id FROM labours WHERE id = $id AND contractor_id = $contractorID AND is_deleted IS NULL");
    // if (mysqli_num_rows($check) === 0) {
    //     echo json_encode(['status' => 'error', 'message' => 'अनुमति नहीं है।']);
    //     exit;
    // }

    // Soft delete
    $query = "UPDATE labours SET is_deleted = 1 WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'डिलीट नहीं हुआ।']);
    }
} else {
    // Not a valid POST request
    echo json_encode(['status' => 'error', 'message' => 'अमान्य अनुरोध।']);
}
?>
