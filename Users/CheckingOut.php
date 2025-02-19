<?php
header('Content-Type: application/json');
session_start();
require_once 'db.php';

// Debugging code to print POST data
error_log(print_r($_POST, true));

// Check if POST data is received
if (empty($_POST)) {
    echo json_encode(["success" => false, "message" => "No POST data received"]);
    exit();
}

$courseId = isset($_POST['courseId']) ? intval($_POST['courseId']) : 0;
$fullName = isset($_POST['full_name']) ? $_POST['full_name'] : '';
$address = isset($_POST['address']) ? $_POST['address'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$phoneNumber = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
$paymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';

// Validate required fields
if ($courseId == 0 || empty($fullName) || empty($email) || empty($phoneNumber) || empty($paymentMethod)) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit();
}

// Fetch course details
require_once 'fetch_course.php';
$course = getCourseDetails($conn, $courseId);

if (!$course) {
    echo json_encode(["success" => false, "message" => "Course not found"]);
    exit();
}

$courseName = $course['CourseName'];
$courseBy = $course['CourseBy'];
$price = $course['Price'];

// Insert checkout record
require_once 'insert_checkout.php';
$checkoutID = insertCheckout($conn, $courseId, $courseName, $courseBy, $price);

// Insert payment record
require_once 'process_payment.php';
insertPayment($conn, $checkoutID, $courseId, $courseName, $fullName, $address, $email, $phoneNumber, $paymentMethod, $price);

// Return success response
echo json_encode(["success" => true, "message" => "Transaction complete! Redirecting to homepage..."]);
?>