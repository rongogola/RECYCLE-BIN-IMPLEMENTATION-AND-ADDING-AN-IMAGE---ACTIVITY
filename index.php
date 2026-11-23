<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_order'])) {
    $tech_id = $_POST['tech_id'];
    $customer_name = $_POST['customer_name'];
    $issue_description = $_POST['issue_description'];
    $image_path = NULL;

    if (isset($_FILES['issue_image']) && $_FILES['issue_image']['error'] === 0) {
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }
        $file_name = time() . "_" . basename($_FILES['issue_image']['name']);
        $image_path = "uploads/" . $file_name;
        move_uploaded_file($_FILES['issue_image']['tmp_name'], $image_path);
    }

    $stmt = $conn->prepare("INSERT INTO work_orders (tech_id, customer_name, issue_description, issue_image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $tech_id, $customer_name, $issue_description, $image_path);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit();
}

$techs = $conn->query("SELECT tech_id, tech_name FROM technicians");

$sql = "SELECT w.order_id, t.tech_name, w.customer_name, w.issue_description, w.issue_image 
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
        table { border-collapse: collapse; width: 85%; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .btn-delete { color: red; text-decoration: none; font-weight: bold; }
        .nav { margin-bottom: 20px; }
        .form-box { border: 1px solid #ccc; padding: 15px; width: 400px; margin-bottom: 25px; background: #fafafa; }
        .form-box label { display: block; margin-top: 10px; font-weight: bold; }
        .form-box input, .form-box select { width: 95%; padding: 6px; margin-top: 4px; }
        .form-box button { margin-top: 15px; padding: 8px 15px; background: #28a745; color: white; border: none; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>

<div class="nav">
    <strong>Logged in as:</strong> <?php echo $_SESSION['username']; ?> | 
    <a href="recycle_bin.php">View Recycle Bin</a>
</div>

<div class="form-box">
    <h3>Add New Work Order</h3>
    <form action="index.php" method="POST" enctype="multipart/form-data">
        <label>Technician (Parent):</label>
        <select name="tech_id" required>
            <?php while ($t = $techs->fetch_assoc()): ?>
                <option value="<?php echo $t['tech_id']; ?>">
                    <?php echo htmlspecialchars($t['tech_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Customer Name:</label>
        <input type="text" name="customer_name" required>

        <label>Issue Description:</label>
        <input type="text" name="issue_description" required>

        <label>Issue Image:</label>
        <input type="file" name="issue_image" accept="image/*" required>

        <button type="submit" name="add_order">Add Work Order</button>
    </form>
</div>

<h2>Active Work Orders (Children Entity)</h2>

<table>
    <tr>
        <th>Order ID</th>
        <th>Technician (Parent)</th>
        <th>Customer Name</th>
        <th>Issue Description</th>
        <th>Issue Image</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['order_id']; ?></td>
        <td><?php echo htmlspecialchars($row['tech_name']); ?></td>
        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
        <td><?php echo htmlspecialchars($row['issue_description']); ?></td>
        <td>
            <?php if (!empty($row['issue_image'])): ?>
                <img src="<?php echo htmlspecialchars($row['issue_image']); ?>" width="100" alt="Issue Image">
            <?php else: ?>
                No Image
            <?php endif; ?>
        </td>
        <td>
            <a class="btn-delete" href="delete.php?id=<?php echo $row['order_id']; ?>" 
               onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
