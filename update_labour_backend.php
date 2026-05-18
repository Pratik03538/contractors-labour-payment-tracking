<?php
include 'includes/session.php';
include 'includes/db.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $role = $_POST['role'];
    $salary_type = $_POST['salary_type'];
    $salary_amount = $_POST['salary_amount'];

    $query = "UPDATE labours SET name=?, mobile=?, role=?, salary_type=?, salary_amount=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssdi", $name, $mobile, $role, $salary_type, $salary_amount, $id);
    $stmt->execute();

    // अगर पासवर्ड भेजा गया है तभी अपडेट करें
    if (!empty($_POST['password'])) {
        $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt2 = $conn->prepare("UPDATE labours SET password=? WHERE id=?");
        $stmt2->bind_param("si", $hashed, $id);
        $stmt2->execute();
    }

    $get = mysqli_query($conn, "SELECT * FROM labours WHERE id = $id");
    $labour = mysqli_fetch_assoc($get);

    $response['success'] = true;
    $response['labour'] = $labour;
}

echo json_encode($response);
?>
