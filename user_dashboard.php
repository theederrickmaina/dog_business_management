<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login if the user is not logged in
    header("Location: login.html");
    exit();
}

// Redirect to the homepage
header("Location: doghome.html?logged_in=true");
exit();