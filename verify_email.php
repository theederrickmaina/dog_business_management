<?php
require_once "connect.php";
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

header('Content-Type: application/json');

$response = ["valid" => false, "exists" => false, "smtpVerified" => false];

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Validate email format
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["valid"] = true;

        try {
            // Check if the email exists in the database
            $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $response["exists"] = true;
            } else {
                // Use PHPMailer to validate email via SMTP
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; 
                $mail->SMTPAuth = true;
                $mail->Username = 'youremail@gmail.com'; 
                $mail->Password = 'yourpassword';         
                $mail->setFrom('your_email@example.com', 'GuardianPaws Kennels');
                $mail->addAddress($email);

                if ($mail->validateAddress($email)) {
                    $response["smtpVerified"] = true;
                }
            }
        } catch (Exception $e) {
            $response["error"] = "Error: " . $e->getMessage();
        }
    }
}

echo json_encode($response);
