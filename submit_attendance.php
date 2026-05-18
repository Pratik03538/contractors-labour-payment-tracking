<?php
include 'includes/session.php';
include 'includes/db.php';

date_default_timezone_set('Asia/Kolkata'); // ✅ Set correct timezone

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['labour_ids'])) {
    $contractor_id = $_SESSION['user_id'];
    $labour_ids = $_POST['labour_ids'];
    $date = date('Y-m-d');
    $newly_marked = [];
    $already_marked = [];

    foreach ($labour_ids as $labour_id) {
        $labour_id = intval($labour_id); // ✅ sanitize input
        $check = mysqli_query($conn, "SELECT id FROM attendance WHERE labour_id='$labour_id' AND date='$date'");
        
        if (mysqli_num_rows($check) > 0) {
            $already_marked[] = $labour_id;
        } else {
            mysqli_query($conn, "INSERT INTO attendance (labour_id, date, contractor_id, type) VALUES ('$labour_id', '$date', '$contractor_id', 'full')");
            $newly_marked[] = $labour_id;
        }
    }

    // ✅ Fetch names of marked labours
    $marked_ids = array_merge($newly_marked, $already_marked);
    $names = [];

    if (count($marked_ids)) {
        $ids = implode("','", array_map('intval', $marked_ids));
        $result = mysqli_query($conn, "SELECT id, name FROM labours WHERE id IN ('$ids')");
        while ($row = mysqli_fetch_assoc($result)) {
            $names[$row['id']] = $row['name'];
        }
    }

    $_SESSION['attendance_popup'] = [
        'new' => $newly_marked,
        'already' => $already_marked,
        'names' => $names
    ];
}

header("Location: attendance.php");
exit;
?>
