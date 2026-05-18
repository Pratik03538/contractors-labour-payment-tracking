<?php
include 'includes/session.php';
include 'includes/db.php';

$group_id = $_GET['group_id'];
$contractor_id = $_SESSION['user_id'];

// Delete group members first
mysqli_query($conn, "DELETE FROM labour_group_members WHERE group_id='$group_id'");

// Then delete the group
mysqli_query($conn, "DELETE FROM labour_groups WHERE id='$group_id' AND contractor_id='$contractor_id'");

header("Location: groups.php");
exit();
?>
