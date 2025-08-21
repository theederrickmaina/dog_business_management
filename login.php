<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connect.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $user = $stmt->fetch();
    
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin'];

        if ($user['is_admin']) {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit();
    } else {
        echo "Invalid username or password";
    }
}



