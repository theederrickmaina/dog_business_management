<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo "Access Denied";
    exit();
}

include 'connect.php';

// Add Sale
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_sale'])) {
    $dog_id = (int)$_POST['dog_id'];
    $user_id = (int)$_POST['user_id'];
    $client_payment_id = (int)$_POST['client_payment_id'];
    $sale_date = htmlspecialchars($_POST['sale_date']);
    $amount = (float)$_POST['amount'];
    $payment_status = htmlspecialchars($_POST['payment_status']);
    $price = (float)$_POST['price'];

    $stmt = $conn->prepare("INSERT INTO sales (dog_id, user_id, client_payment_id, sale_date, amount, payment_status, price) 
                            VALUES (:dog_id, :user_id, :client_payment_id, :sale_date, :amount, :payment_status, :price)");
    $stmt->bindParam(':dog_id', $dog_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':client_payment_id', $client_payment_id);
    $stmt->bindParam(':sale_date', $sale_date);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':payment_status', $payment_status);
    $stmt->bindParam(':price', $price);
    $stmt->execute();

    echo "Sale added successfully!";
}

// Update Sale
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_sale'])) {
    $sale_id = (int)$_POST['sale_id'];
    $dog_id = (int)$_POST['dog_id'];
    $user_id = (int)$_POST['user_id'];
    $client_payment_id = (int)$_POST['client_payment_id'];
    $sale_date = htmlspecialchars($_POST['sale_date']);
    $amount = (float)$_POST['amount'];
    $payment_status = htmlspecialchars($_POST['payment_status']);
    $price = (float)$_POST['price'];

    $stmt = $conn->prepare("UPDATE sales 
                            SET dog_id = :dog_id, user_id = :user_id, client_payment_id = :client_payment_id, 
                                sale_date = :sale_date, amount = :amount, payment_status = :payment_status, price = :price 
                            WHERE id = :sale_id");
    $stmt->bindParam(':dog_id', $dog_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':client_payment_id', $client_payment_id);
    $stmt->bindParam(':sale_date', $sale_date);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':payment_status', $payment_status);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':sale_id', $sale_id);
    $stmt->execute();

    echo "Sale updated successfully!";
}

// Delete Sale
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_sale'])) {
    $sale_id = (int)$_POST['sale_id'];

    $stmt = $conn->prepare("DELETE FROM sales WHERE id = :sale_id");
    $stmt->bindParam(':sale_id', $sale_id);
    $stmt->execute();

    echo "Sale deleted successfully!";
}

// Fetch Sales
$stmt = $conn->prepare("SELECT s.*, d.name AS dog_name, u.username AS user_name 
                        FROM sales s 
                        JOIN dogs d ON s.dog_id = d.dog_id 
                        JOIN users u ON s.user_id = u.id");
$stmt->execute();
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Main Content: Manage Sales -->
<header>
    <h1>Manage Sales</h1>
</header>

<!-- Form for Adding Sale -->
<h2>Add Sale</h2>
<form action="manage_sales.php" method="POST">
    <input type="number" name="dog_id" placeholder="Dog ID" required>
    <input type="number" name="user_id" placeholder="User ID" required>
    <input type="number" name="client_payment_id" placeholder="Client Payment ID" required>
    <input type="date" name="sale_date" placeholder="Sale Date" required>
    <input type="number" step="0.01" name="amount" placeholder="Amount" required>
    <input type="text" name="payment_status" placeholder="Payment Status" required>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <button type="submit" name="add_sale" class="btn btn-primary">Add Sale</button>
</form>

<!-- Form for Updating Sale -->
<h2>Update Sale</h2>
<form action="manage_sales.php" method="POST">
    <input type="number" name="sale_id" placeholder="Sale ID" required>
    <input type="number" name="dog_id" placeholder="Dog ID" required>
    <input type="number" name="user_id" placeholder="User ID" required>
    <input type="number" name="client_payment_id" placeholder="Client Payment ID" required>
    <input type="date" name="sale_date" placeholder="Sale Date" required>
    <input type="number" step="0.01" name="amount" placeholder="Amount" required>
    <input type="text" name="payment_status" placeholder="Payment Status" required>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <button type="submit" name="update_sale" class="btn btn-warning">Update Sale</button>
</form>

<!-- Form for Deleting Sale -->
<h2>Delete Sale</h2>
<form action="manage_sales.php" method="POST">
    <input type="number" name="sale_id" placeholder="Sale ID" required>
    <button type="submit" name="delete_sale" class="btn btn-danger">Delete Sale</button>
</form>

<!-- Display Existing Sales -->
<h2>Current Sales</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Dog Name</th>
        <th>User Name</th>
        <th>Client Payment ID</th>
        <th>Sale Date</th>
        <th>Amount</th>
        <th>Payment Status</th>
        <th>Price</th>
    </tr>
    <?php foreach ($sales as $sale): ?>
    <tr>
        <td><?php echo isset($sale['id']) ? htmlspecialchars($sale['id']) : 'N/A'; ?></td>
        <td><?php echo htmlspecialchars($sale['dog_name']); ?></td>
        <td><?php echo htmlspecialchars($sale['user_name']); ?></td>
        <td><?php echo htmlspecialchars($sale['client_payment_id']); ?></td>
        <td><?php echo htmlspecialchars($sale['sale_date']); ?></td>
        <td><?php echo htmlspecialchars($sale['amount']); ?></td>
        <td><?php echo htmlspecialchars($sale['payment_status']); ?></td>
        <td><?php echo htmlspecialchars($sale['price']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="admin_dashboard.php" class="back-to-dashboard">Back to Dashboard</a>
