<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Allow CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Include PHPMailer library files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $course = htmlspecialchars(trim($_POST['course']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Input validation
    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/^[0-9]{10}$/', $phone) && !empty($course)) {
        $mail = new PHPMailer();

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'demo.orangeacadamy@gmail.com'; // SMTP username
            $mail->Password = 'vkbagdpfrshlcaf'; // SMTP password
            $mail->SMTPSecure = 'ssl'; // Enable TLS encryption
            $mail->Port = 465; // TCP port to connect to

            // Recipients
            $mail->setFrom('demo.orangeacadamy@gmail.com', 'Book A Demo Request');
            $mail->addAddress('s.lathika1312@gmail.com'); // Add recipient email

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Book A Demo Request from ' . $name;
            $mail->Body = "New Medical Coding Education Form Submission Received.<br><br>" .
                          "Name: " . $name . "<br>" .
                          "Email: " . $email . "<br>" .
                          "Phone: " . $phone . "<br>" .
                          "Course: " . $course . "<br>" .
                          "Message: " . nl2br($message) . "<br>";

            // Send email
            if ($mail->send()) {
                echo json_encode(['status' => 'success', 'message' => 'Email sent successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Email sending failed.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input. Please check your details.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
