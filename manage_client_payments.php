<?php
session_start(); 

// Check if the user is an admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo "Access Denied"; 
    exit();
}

include 'connect.php';

// Add Client Payment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_client_payment'])) {
    $client_name = htmlspecialchars($_POST['client_name']);
    $amount = (float)$_POST['amount'];
    $payment_date = $_POST['payment_date'];  
    $notes = htmlspecialchars($_POST['notes']);

    $stmt = $conn->prepare("INSERT INTO client_payments (client_name, amount, payment_date, notes) 
                            VALUES (:client_name, :amount, :payment_date, :notes)");
    $stmt->bindParam(':client_name', $client_name);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':payment_date', $payment_date);
    $stmt->bindParam(':notes', $notes);
    $stmt->execute();

    echo "Payment added successfully!";
}

// Update Client Payment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_client_payment'])) {
    $payment_id = (int)$_POST['payment_id'];
    $client_name = htmlspecialchars($_POST['client_name']);
    $amount = (float)$_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $notes = htmlspecialchars($_POST['notes']);

    $stmt = $conn->prepare("UPDATE client_payments 
                            SET client_name = :client_name, amount = :amount, payment_date = :payment_date, notes = :notes 
                            WHERE id = :payment_id");
    $stmt->bindParam(':client_name', $client_name);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':payment_date', $payment_date);
    $stmt->bindParam(':notes', $notes);
    $stmt->bindParam(':payment_id', $payment_id);
    $stmt->execute();

    echo "Payment updated successfully!";
}

// Delete Client Payment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_client_payment'])) {
    $payment_id = (int)$_POST['payment_id'];

    $stmt = $conn->prepare("DELETE FROM client_payments WHERE id = :payment_id");
    $stmt->bindParam(':payment_id', $payment_id);
    $stmt->execute();

    echo "Payment deleted successfully!";
}

// Fetch Client Payments
$stmt = $conn->prepare("SELECT * FROM client_payments");
$stmt->execute();
$client_payments = $stmt->fetchAll(PDO::FETCH_ASSOC); // Use fetchAll() to fetch data
?>

<!-- Main Content: Manage Client Payments -->
<header>
<h1>Manage Client Payments</h1>
</header>

<!-- Form for Adding Client Payment -->
<h2>Add Client Payment</h2>
<form action="manage_client_payments.php" method="POST">
    <input type="text" name="client_name" placeholder="Client Name" required>
    <input type="number" step="0.01" name="amount" placeholder="Amount" required>
    <input type="date" name="payment_date" placeholder="Payment Date" required>
    <input type="text" name="notes" placeholder="Notes">
    <button type="submit" name="add_client_payment" class="btn btn-primary">Add Payment</button>
</form>

<!-- Form for Updating Client Payment -->
<h2>Update Client Payment</h2>
<form action="manage_client_payments.php" method="POST">
    <input type="number" name="payment_id" placeholder="Payment ID" required>
    <input type="text" name="client_name" placeholder="Client Name" required>
    <input type="number" step="0.01" name="amount" placeholder="Amount" required>
    <input type="date" name="payment_date" placeholder="Payment Date" required>
    <input type="text" name="notes" placeholder="Notes">
    <button type="submit" name="update_client_payment" class="btn btn-warning">Update Payment</button>
</form>

<!-- Form for Deleting Client Payment -->
<h2>Delete Client Payment</h2>
<form action="manage_client_payments.php" method="POST">
    <input type="number" name="payment_id" placeholder="Payment ID" required>
    <button type="submit" name="delete_client_payment" class="btn btn-danger">Delete Payment</button>
</form>

<!-- Display Existing Client Payments -->
<h2>Current Client Payments</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Client Name</th>
        <th>Amount</th>
        <th>Payment Date</th>
        <th>Notes</th>
    </tr>
    <?php foreach ($client_payments as $payment): ?>
    <tr>
        <td><?php echo htmlspecialchars($payment['id']); ?></td>
        <td><?php echo htmlspecialchars($payment['client_name']); ?></td>
        <td><?php echo htmlspecialchars($payment['amount']); ?></td>
        <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
        <td><?php echo htmlspecialchars($payment['notes']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="admin_dashboard.php" class="back-to-dashboard">Back to Dashboard</a>
