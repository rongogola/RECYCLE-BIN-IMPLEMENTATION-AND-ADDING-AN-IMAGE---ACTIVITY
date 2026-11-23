<?php
include 'db.php';

$sql = "SELECT w.order_id, t.tech_name, w.customer_name, w.issue_description 
        FROM work_orders w 
        JOIN technicians t ON w.tech_id = t.tech_id 
        WHERE w.is_deleted = 0";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Work Orders - Service Tracker</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; width: 80%; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .btn-delete { color: red; text-decoration: none; font-weight: bold; }
        .nav { margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="nav">
    <strong>Logged in as:</strong> <?php echo $_SESSION['username']; ?> | 
    <a href="recycle_bin.php">View Recycle Bin</a>
</div>

<h2>Active Work Orders (Children Entity)</h2>

<table>
    <tr>
        <th>Order ID</th>
        <th>Technician (Parent)</th>
        <th>Customer Name</th>
        <th>Issue Description</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['order_id']; ?></td>
        <td><?php echo $row['tech_name']; ?></td>
        <td><?php echo $row['customer_name']; ?></td>
        <td><?php echo $row['issue_description']; ?></td>
        <td>
            <a class="btn-delete" href="delete.php?id=<?php echo $row['order_id']; ?>" 
               onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
