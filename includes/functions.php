<?php
// Display alerts with Bootstrap-like style
function showAlert($type, $message) {
    $colors = [
        "success" => "#d4edda",
        "danger" => "#f8d7da",
        "warning" => "#fff3cd",
        "info" => "#d1ecf1"
    ];
    $textColors = [
        "success" => "#155724",
        "danger" => "#721c24",
        "warning" => "#856404",
        "info" => "#0c5460"
    ];

    echo "<div style='background-color:{$colors[$type]};color:{$textColors[$type]};padding:10px;margin-bottom:10px;border-radius:5px;'>
            $message
          </div>";
}
?>
