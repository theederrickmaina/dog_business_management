<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo "Access Denied";
    exit();
}

include 'connect.php';

// Add Dog
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_dog'])) {
    $name = htmlspecialchars($_POST['name']);
    $breed = htmlspecialchars($_POST['breed']);
    $age = (int)$_POST['age'];
    $arrival_date = $_POST['arrival_date'];
    $status = htmlspecialchars($_POST['status']);
    $health_status = htmlspecialchars($_POST['health_status']);
    $price = (float)$_POST['price'];

    $stmt = $conn->prepare("INSERT INTO dogs (name, breed, age, arrival_date, status, health_status, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $breed, $age, $arrival_date, $status, $health_status, $price]);

    echo "Dog added successfully!";
}

// Delete Dog
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_dog'])) {
    $dog_id = (int)$_POST['dog_id'];

    $stmt = $conn->prepare("DELETE FROM dogs WHERE dog_id = ?");
    $stmt->execute([$dog_id]);

    echo "Dog deleted successfully!";
}

// Update Dog
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_dog'])) {
    $dog_id = (int)$_POST['dog_id'];
    $name = htmlspecialchars($_POST['name']);
    $breed = htmlspecialchars($_POST['breed']);
    $age = (int)$_POST['age'];
    $arrival_date = $_POST['arrival_date'];
    $status = htmlspecialchars($_POST['status']);
    $health_status = htmlspecialchars($_POST['health_status']);
    $price = (float)$_POST['price'];

    $stmt = $conn->prepare("UPDATE dogs SET name = ?, breed = ?, age = ?, arrival_date = ?, status = ?, health_status = ?, price = ? WHERE dog_id = ?");
    $stmt->execute([$name, $breed, $age, $arrival_date, $status, $health_status, $price, $dog_id]);

    echo "Dog updated successfully!";
}

// Fetch Dogs
$stmt = $conn->prepare("SELECT * FROM dogs");
$stmt->execute();
$dogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conn = null;
?>

<!-- Manage Dogs Content -->
<header class="dashboard-header">
    <h1>Manage Dogs</h1>
</header>

<div class="dashboard-container">

    <!-- Add Dog Form -->
    <section class="form-section">
        <h2>Add Dog</h2>
        <form action="manage_dogs.php" method="POST" class="form">
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="breed" placeholder="Breed" required>
            <input type="number" name="age" placeholder="Age" required>
            <input type="date" name="arrival_date" placeholder="Arrival Date" required>
            <input type="text" name="status" placeholder="Status (e.g., available, sold)" required>
            <input type="text" name="health_status" placeholder="Health Status" required>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <button type="submit" name="add_dog" class="btn btn-primary">Add Dog</button>
        </form>
    </section>

    <!-- Update Dog Form -->
    <section class="form-section">
        <h2>Update Dog</h2>
        <form action="manage_dogs.php" method="POST" class="form">
            <input type="number" name="dog_id" placeholder="Dog ID" required>
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="breed" placeholder="Breed" required>
            <input type="number" name="age" placeholder="Age" required>
            <input type="date" name="arrival_date" placeholder="Arrival Date" required>
            <input type="text" name="status" placeholder="Status" required>
            <input type="text" name="health_status" placeholder="Health Status" required>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <button type="submit" name="update_dog" class="btn btn-warning">Update Dog</button>
        </form>
    </section>

    <!-- Delete Dog Form -->
    <section class="form-section">
        <h2>Delete Dog</h2>
        <form action="manage_dogs.php" method="POST" class="form">
            <input type="number" name="dog_id" placeholder="Dog ID" required>
            <button type="submit" name="delete_dog" class="btn btn-danger">Delete Dog</button>
        </form>
    </section>

    <!-- Display Dogs -->
    <section class="data-section">
        <h2>Current Dogs</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Breed</th>
                    <th>Age</th>
                    <th>Arrival Date</th>
                    <th>Status</th>
                    <th>Health Status</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dogs as $dog): ?>
                <tr>
                    <td><?php echo htmlspecialchars($dog['dog_id']); ?></td>
                    <td><?php echo htmlspecialchars($dog['name']); ?></td>
                    <td><?php echo htmlspecialchars($dog['breed']); ?></td>
                    <td><?php echo htmlspecialchars($dog['age']); ?></td>
                    <td><?php echo htmlspecialchars($dog['arrival_date']); ?></td>
                    <td><?php echo htmlspecialchars($dog['status']); ?></td>
                    <td><?php echo htmlspecialchars($dog['health_status']); ?></td>
                    <td><?php echo htmlspecialchars($dog['price']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
    <a href="admin_dashboard.php" class="back-to-dashboard">Back to Dashboard</a>


</div>
