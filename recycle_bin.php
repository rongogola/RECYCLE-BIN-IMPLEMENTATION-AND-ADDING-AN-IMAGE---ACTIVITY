<?php
include 'db.php';


$sql = "SELECT w.order_id, t.tech_name, w.customer_name, w.issue_description, w.deleted_by, w.deleted_at 
        FROM work_orders w 
        JOIN technicians t ON w.tech_id = t.tech_id 
        WHERE w.is_deleted = 1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recycle Bin - Service Tracker</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; width: 90%; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #ffe6e6; }
        .btn-restore { color: green; text-decoration: none; font-weight: bold; }
        .nav { margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="nav">
    <strong>Logged in as:</strong> <?php echo $_SESSION['username']; ?> | 
    <a href="index.php">Back to Active Work Orders</a>
</div>

<h2>Recycle Bin (Deleted Work Orders)</h2>

<table>
    <tr>
        <th>Order ID</th>
        <th>Technician</th>
        <th>Customer Name</th>
        <th>Issue Description</th>
        <th>Deleted By</th>
        <th>Deleted At</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['order_id']; ?></td>
        <td><?php echo $row['tech_name']; ?></td>
        <td><?php echo $row['customer_name']; ?></td>
        <td><?php echo $row['issue_description']; ?></td>
        <td><?php echo $row['deleted_by']; ?></td>
        <td><?php echo $row['deleted_at']; ?></td>
        <td>
            <a class="btn-restore" href="restore.php?id=<?php echo $row['order_id']; ?>" 
               onclick="return confirm('Restore this record?');">Restore</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
