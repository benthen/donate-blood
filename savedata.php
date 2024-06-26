<?php
header("X-Frame-Options: DENY");
include ("anti-csrf.php");

session_start();

// Disable error reporting in production
error_reporting(0);

// Function to log errors
function log_error($error_message)
{
    // Ensure the log directory exists
    if (!file_exists('logs')) {
        mkdir('logs', 0777, true);
    }

    // Log the error with a unique identifier
    $error_id = uniqid('error_', true);
    $log_message = date('Y-m-d H:i:s') . " [{$error_id}] - {$error_message}\n";
    file_put_contents('logs/error_log.txt', $log_message, FILE_APPEND);

    return $error_id;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
        die('CSRF token validation failed');
    }

    $name = $_POST['fullname'];
    $number = $_POST['mobileno'];
    $email = $_POST['emailid'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $blood_group = $_POST['blood'];
    $address = $_POST['address'];

    $conn = mysqli_connect("localhost", "root", "", "blood_donation") or die("Connection error");
    if (!$conn) {
        $error_id = log_error("Connection error: " . mysqli_connect_error());
        $_SESSION['error_message'] = "An error occurred. Please try again later. Error ID: {$error_id}";
        header("Location: error.php");
        exit();
    }
    $sql = $conn->prepare("INSERT INTO donor_details(donor_name, donor_number, donor_mail, donor_age, donor_gender, donor_blood, donor_address) 
            values(?, ?, ?, ?, ?, ?, ?)");
    $sql->bind_param('sssssss', $name, $number, $email, $age, $gender, $blood_group, $address);
    $sql->execute() or die("query unsuccessful");
    // $result = mysqli_query($conn, $sql) or die("query unsuccessful.");

    header("Location: http://localhost/Blood-Bank-And-Donation-Management-System-master/Blood-Bank-And-Donation-Management-System-master/home.php");

    mysqli_close($conn);
} else {
    header("HTTP/1.1 405 Method Not Allowed");
    exit;
}
?>