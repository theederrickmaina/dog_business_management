<?php
require_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Prepare an SQL statement to insert the new user data
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $conn->prepare($sql);

        // Bind parameters to the SQL statement
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashed_password);

        if ($stmt->execute()) {
            // Redirect to login page upon successful registration
            header("Location: login.html");
            exit();
        } else {
            echo "Error: Could not complete registration. Please try again.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
