<?php
session_start();

if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = 'admin_user';
}

$conn = new mysqli("localhost", "root", "", "service_tracker_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
