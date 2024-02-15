<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "phpmailer\src\Exception.php";
require 'phpmailer\src\PHPMailer.php';
require 'phpmailer\src\SMTP.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbName = "barbershop";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);

// Check connection
if (mysqli_connect_errno()) {
    echo json_encode(['status' => 'failed', 'message' => 'Database connection failed']);
} else {
    $currentDate = date('Y-m-d');

    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Check if there is an existing record with the same date and time
    $existingRecordQuery = "SELECT * FROM appointments WHERE date = '$date' AND time = '$time'";
    $result = mysqli_query($conn, $existingRecordQuery);

    if (mysqli_num_rows($result) > 0) {
        // Record with the same date and time already exists
        echo json_encode(['status' => 'failed', 'message' => 'Appointment already exists for the selected date and time. Please choose a different date or time.']);
    } elseif ($date < $currentDate) {
        // Date is in the past
        echo json_encode(['status' => 'failed', 'message' => 'Enter a Valid Date']);
    } else {
        // No conflicting record, proceed with the insertion
        $stmt = $conn->prepare("INSERT INTO appointments (name, email, number, date, time) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $number, $date, $time);
        $stmt->execute();

        // Send email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;  // Enable verbose debug output
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'francirizai2002@gmail.com'; // Replace with your Gmail email address
            $mail->Password = 'zjhjmqhzmebxayxr';  // Replace with your Gmail password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('francirizai2002@gmail.com','Malvin.gr'); // Replace with your Gmail email address and your name
            $mail->addAddress("francirizai2002@gmail.com");  // Replace with the recipient's email address

            // Content
            $mail->isHTML(true);
            $mail->Subject = "ΡΑΝΤΕΒΟΥ $name ";
            $mail->Body =  "
            <p>Στοιχεία</p>
            <ul>
                <li><strong>Όνομα:</strong> $name</li>
                <li><strong>Email:</strong> $email</li>
                <li><strong>Τηλέφωνο:</strong> $number</li>
                <li><strong>Ημερομηνία:</strong> $date</li>
                <li><strong>Ώρα:</strong> $time</li>
            </ul>
        ";

            $mail->send();
            echo json_encode(['status' => 'success', 'message' => 'Insert success']);
        } catch (exception $e) {
            echo json_encode(['status' => 'failed', 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
        }

        $stmt->close();
    }

    $conn->close();
}
?>
