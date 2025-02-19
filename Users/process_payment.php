<?php
function insertPayment($conn, $checkoutID, $courseId, $courseName, $fullName, $address, $email, $phoneNumber, $paymentMethod, $price) {
    $query = "INSERT INTO payment (checkoutID, CourseID, CourseName, FullName, Address, Email, PhoneNumber, PaymentMethod, Price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iissssssd", $checkoutID, $courseId, $courseName, $fullName, $address, $email, $phoneNumber, $paymentMethod, $price);
    $stmt->execute();
}
?>
