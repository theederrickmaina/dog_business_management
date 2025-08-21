<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo "Access Denied";
    exit();
}

include 'connect.php';

// Add Vet Visit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_vet_visit'])) {
    $dog_id = (int)$_POST['dog_id'];
    $visit_date = $_POST['visit_date'];
    $vet_name = htmlspecialchars($_POST['vet_name']);
    $notes = htmlspecialchars($_POST['notes']);

    $stmt = $conn->prepare("INSERT INTO vet_visits (dog_id, visit_date, vet_name, notes) VALUES (?, ?, ?, ?)");
    $stmt->execute([$dog_id, $visit_date, $vet_name, $notes]);

    echo "Vet visit added successfully!";
}

// Delete Vet Visit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_vet_visit'])) {
    $visit_id = (int)$_POST['visit_id'];

    $stmt = $conn->prepare("DELETE FROM vet_visits WHERE id = ?");
    $stmt->execute([$visit_id]);

    echo "Vet visit deleted successfully!";
}

// Update Vet Visit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_vet_visit'])) {
    $visit_id = (int)$_POST['visit_id'];
    $dog_id = (int)$_POST['dog_id'];
    $visit_date = $_POST['visit_date'];
    $vet_name = htmlspecialchars($_POST['vet_name']);
    $notes = htmlspecialchars($_POST['notes']);

    $stmt = $conn->prepare("UPDATE vet_visits SET dog_id = ?, visit_date = ?, vet_name = ?, notes = ? WHERE id = ?");
    $stmt->execute([$dog_id, $visit_date, $vet_name, $notes, $visit_id]);

    echo "Vet visit updated successfully!";
}

// Fetch Vet Visits
$stmt = $conn->prepare("SELECT v.*, d.name AS dog_name FROM vet_visits v JOIN dogs d ON v.dog_id = d.dog_id");
$stmt->execute();
$vet_visits = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close the connection
$conn = null;
?>

<!-- Main Content: Manage Vet Visits -->
<header>
    <h1>Manage Vet Visits</h1>
</header>

<!-- Form for Adding a Vet Visit -->
<h2>Add Vet Visit</h2>
<form action="manage_vet_visits.php" method="POST">
    <label for="dog_id">Dog ID:</label>
    <input type="number" name="dog_id" id="dog_id" placeholder="Dog ID" required>
    
    <label for="visit_date">Visit Date:</label>
    <input type="date" name="visit_date" id="visit_date" required>
    
    <label for="vet_name">Vet Name:</label>
    <input type="text" name="vet_name" id="vet_name" placeholder="Vet Name" required>
    
    <label for="notes">Notes:</label>
    <textarea name="notes" id="notes" placeholder="Notes about the visit"></textarea>
    
    <button type="submit" name="add_vet_visit" class="btn btn-primary">Add Vet Visit</button>
</form>

<!-- Form for Updating a Vet Visit -->
<h2>Update Vet Visit</h2>
<form action="manage_vet_visits.php" method="POST">
    <label for="visit_id">Visit ID:</label>
    <input type="number" name="visit_id" id="visit_id" placeholder="Visit ID" required>
    
    <label for="dog_id">Dog ID:</label>
    <input type="number" name="dog_id" id="dog_id" placeholder="New Dog ID" required>
    
    <label for="visit_date">Visit Date:</label>
    <input type="date" name="visit_date" id="visit_date" required>
    
    <label for="vet_name">Vet Name:</label>
    <input type="text" name="vet_name" id="vet_name" placeholder="New Vet Name" required>
    
    <label for="notes">Notes:</label>
    <textarea name="notes" id="notes" placeholder="New Notes about the visit"></textarea>
    
    <button type="submit" name="update_vet_visit" class="btn btn-warning">Update Vet Visit</button>
</form>

<!-- Form for Deleting a Vet Visit -->
<h2>Delete Vet Visit</h2>
<form action="manage_vet_visits.php" method="POST">
    <label for="visit_id">Visit ID:</label>
    <input type="number" name="visit_id" id="visit_id" placeholder="Visit ID" required>
    
    <button type="submit" name="delete_vet_visit" class="btn btn-danger">Delete Vet Visit</button>
</form>

<!-- Display Existing Vet Visits -->
<h2>Current Vet Visits</h2>
<table border="1">
    <tr>
        <th>Visit ID</th>
        <th>Dog Name</th>
        <th>Visit Date</th>
        <th>Vet Name</th>
        <th>Notes</th>
    </tr>
    <?php foreach ($vet_visits as $visit): ?>
    <tr>
        <td><?php echo htmlspecialchars($visit['id']); ?></td>
        <td><?php echo htmlspecialchars($visit['dog_name']); ?></td>
        <td><?php echo htmlspecialchars($visit['visit_date']); ?></td>
        <td><?php echo htmlspecialchars($visit['vet_name']); ?></td>
        <td><?php echo htmlspecialchars($visit['notes']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="admin_dashboard.php" class="back-to-dashboard">Back to Dashboard</a>
