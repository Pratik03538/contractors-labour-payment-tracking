<?php
$entered = $_POST['entered'];
$stored = $_POST['stored'];

if (password_verify($entered, $stored)) {
    echo "match";
} else {
    echo "no-match";
}
