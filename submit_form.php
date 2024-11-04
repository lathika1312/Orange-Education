<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight request for CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit();
}

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $course = htmlspecialchars(trim($_POST['course'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/^[0-9]{10}$/', $phone) && !empty($course)) {
        $mail = new PHPMailer();

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'demo.orangeacadamy@gmail.com';
            $mail->Password = 'vkbagdpfrshlcaf';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('demo.orangeacadamy@gmail.com', 'Book A Demo Request');
            $mail->addAddress('vasanthmaster100@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = 'Book A Demo Request from ' . $name;
            $mail->Body = "New Medical Coding Education Form Submission Received.<br><br>" .
                          "Name: " . $name . "<br>" .
                          "Email: " . $email . "<br>" .
                          "Phone: " . $phone . "<br>" .
                          "Course: " . $course . "<br>" .
                          "Message: " . nl2br($message) . "<br>";

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
