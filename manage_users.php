<?php
session_start();

// Ensure only admins can access this page
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo "Access Denied";
    exit();
}

include 'connect.php';

// Add User
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $role = htmlspecialchars($_POST['role']);

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);
    $stmt->execute();

    echo "User added successfully!";
}

// Delete User
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $user_id = (int)$_POST['user_id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    echo "User deleted successfully!";
}

// Update User
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $user_id = (int)$_POST['user_id'];
    $username = htmlspecialchars($_POST['username']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null; // Hash the password if provided
    $role = htmlspecialchars($_POST['role']);

    $sql = "UPDATE users SET username = :username, role = :role";
    if ($password) {
        $sql .= ", password = :password";
    }
    $sql .= " WHERE id = :user_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':user_id', $user_id);
    if ($password) {
        $stmt->bindParam(':password', $password);
    }
    $stmt->execute();

    echo "User updated successfully!";
}

// Fetch Users
$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Main Content: Manage Users -->
<header>
    <h1>Manage Users</h1>
</header>

<!-- Form for Adding a User -->
<h2>Add User</h2>
<form action="manage_users.php" method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="text" name="role" placeholder="Role (admin or user)" required>
    <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
</form>

<!-- Form for Updating a User -->
<h2>Update User</h2>
<form action="manage_users.php" method="POST">
    <input type="number" name="user_id" placeholder="User ID" required>
    <input type="text" name="username" placeholder="New Username" required>
    <input type="password" name="password" placeholder="New Password (optional)">
    <input type="text" name="role" placeholder="New Role (admin or user)" required>
    <button type="submit" name="update_user" class="btn btn-warning">Update User</button>
</form>

<!-- Form for Deleting a User -->
<h2>Delete User</h2>
<form action="manage_users.php" method="POST">
    <input type="number" name="user_id" placeholder="User ID" required>
    <button type="submit" name="delete_user" class="btn btn-danger">Delete User</button>
</form>

<!-- Display Existing Users -->
<h2>Current Users</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Is Admin</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?php echo htmlspecialchars($user['id']); ?></td>
        <td><?php echo htmlspecialchars($user['username']); ?></td>
        <td><?php echo htmlspecialchars($user['is_admin']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="admin_dashboard.php" class="back-to-dashboard">Back to Dashboard</a>
