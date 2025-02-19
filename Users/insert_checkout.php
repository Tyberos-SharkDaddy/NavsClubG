<?php
function insertCheckout($conn, $courseId, $courseName, $courseBy, $price) {
    $query = "INSERT INTO checkout_course (CourseID, CourseName, CourseBy, Enrolled, CheckoutDate, Price) VALUES (?, ?, ?, 1, NOW(), ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issi", $courseId, $courseName, $courseBy, $price);
    $stmt->execute();
    return $stmt->insert_id;
}
?>
