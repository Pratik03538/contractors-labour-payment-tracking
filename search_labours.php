<?php
include 'includes/db.php';
// session_start();
$term = $_GET['term'] ?? '';
$contractor_id = $_SESSION['contractor_id'] ?? 1;

$result = mysqli_query($conn, "SELECT id, name FROM labours WHERE is_deleted = 0 AND contractor_id = '$contractor_id' AND name LIKE '%$term%'");

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
echo json_encode($data);
?>
