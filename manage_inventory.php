<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo "Access Denied";
    exit();
}

include 'connect.php';

// Add Inventory Item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_inventory'])) {
    $item_name = htmlspecialchars($_POST['item_name']);
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];

    $stmt = $conn->prepare("INSERT INTO inventory (item_name, quantity, price) VALUES (:item_name, :quantity, :price)");
    $stmt->bindParam(':item_name', $item_name);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':price', $price);
    $stmt->execute();

    echo "Item added successfully!";
}

// Update Inventory Item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_inventory'])) {
    $item_id = (int)$_POST['item_id'];
    $item_name = htmlspecialchars($_POST['item_name']);
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];

    // Fixed query by removing the extra comma
    $stmt = $conn->prepare("UPDATE inventory 
                            SET item_name = :item_name, quantity = :quantity, price = :price 
                            WHERE id = :item_id");
    $stmt->bindParam(':item_name', $item_name);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':item_id', $item_id);
    $stmt->execute();

    echo "Item updated successfully!";
}

// Delete Inventory Item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_inventory'])) {
    $item_id = (int)$_POST['item_id'];

    $stmt = $conn->prepare("DELETE FROM inventory WHERE id = :item_id");
    $stmt->bindParam(':item_id', $item_id);
    $stmt->execute();

    echo "Item deleted successfully!";
}

// Fetch Inventory Data
$stmt = $conn->prepare("SELECT * FROM inventory");
$stmt->execute();
$inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Main Content: Manage Inventory -->
<header>
    <h1>Manage Inventory</h1>
</header>

<!-- Form for Adding Inventory Item -->
<h2>Add Inventory Item</h2>
<form action="manage_inventory.php" method="POST">
    <input type="text" name="item_name" placeholder="Item Name" required>
    <input type="number" name="quantity" placeholder="Quantity" required>
    <input type="text" name="price" placeholder="Price" required>
    <button type="submit" name="add_inventory" class="btn btn-primary">Add Item</button>
</form>

<!-- Form for Updating Inventory Item -->
<h2>Update Inventory Item</h2>
<form action="manage_inventory.php" method="POST">
    <input type="number" name="item_id" placeholder="Item ID" required>
    <input type="text" name="item_name" placeholder="Item Name" required>
    <input type="number" name="quantity" placeholder="Quantity" required>
    <input type="text" name="price" placeholder="Price" required>
    <button type="submit" name="update_inventory" class="btn btn-warning">Update Item</button>
</form>

<!-- Form for Deleting Inventory Item -->
<h2>Delete Inventory Item</h2>
<form action="manage_inventory.php" method="POST">
    <input type="number" name="item_id" placeholder="Item ID" required>
    <button type="submit" name="delete_inventory" class="btn btn-danger">Delete Item</button>
</form>

<!-- Display Existing Inventory Items -->
<h2>Current Inventory</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Item Name</th>
        <th>Quantity</th>
        <th>Price</th>
    </tr>
    <?php foreach ($inventory as $item): ?>
    <tr>
        <td><?php echo htmlspecialchars($item['id']); ?></td>
        <td><?php echo htmlspecialchars($item['item_name']); ?></td>
        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
        <td><?php echo htmlspecialchars($item['price']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="admin_dashboard.php" class="back-to-dashboard">Back to Dashboard</a>
