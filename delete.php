<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $deleted_by = $_SESSION['username'];

    $sql = "UPDATE work_orders SET is_deleted = 1, deleted_by = ?, deleted_at = NOW() WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $deleted_by, $id);
    $stmt->execute();
}

header("Location: index.php");
exit();
?>
