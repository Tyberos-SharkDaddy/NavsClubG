<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'db.php'; // Ensure this file exists and is correct

    $courseId = $_POST['courseId'] ?? '';
    $courseName = $_POST['courseName'] ?? '';
    $paymentMethod = $_POST['payment_method'] ?? '';
    $cardNumber = $_POST['card_number'] ?? '';
    $expiryDate = $_POST['expiry_date'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

    if (empty($courseId) || empty($paymentMethod) || empty($cardNumber) || empty($expiryDate) || empty($cvv)) {
        echo json_encode(["success" => false, "message" => "All fields are required!"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO checkout (CourseID, CourseName, payment_method, card_number, expiry_date, cvv, checkout_date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
        exit;
    }
    
    $stmt->bind_param("ssssss", $courseId, $courseName, $paymentMethod, $cardNumber, $expiryDate, $cvv);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Payment successful!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Payment failed: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
