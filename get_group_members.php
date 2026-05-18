<?php
include 'includes/session.php';
include 'includes/db.php';

$contractor_id = $_SESSION['user_id'];
$group_id = $_GET['group_id'];
date_default_timezone_set('Asia/Kolkata');
// $today = date('Y-m-d');

$today = date('Y-m-d');

$members = [];

$query = mysqli_query($conn, "
  SELECT l.*, 
    (SELECT COUNT(*) FROM attendance 
     WHERE labour_id = l.id AND date = '$today') AS has_attendance
  FROM labours l
  JOIN labour_group_members gm ON l.id = gm.labour_id
  WHERE gm.group_id = '$group_id' AND l.contractor_id = '$contractor_id'
");

while ($row = mysqli_fetch_assoc($query)) {
  $members[] = [
    'id' => $row['id'],
    'name' => $row['name'],
    'already_marked' => $row['has_attendance'] > 0
  ];
}

echo json_encode($members);
