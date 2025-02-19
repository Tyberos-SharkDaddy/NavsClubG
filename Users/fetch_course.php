<?php
header('Content-Type: application/json');

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Connection
$conn = new mysqli("localhost", "root", "", "navsclub");

// Check for database connection error
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit();
}

// Fetch all course details
$query = "SELECT CourseID, CourseName, CourseBy, Price FROM courses";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
    echo json_encode(["success" => true, "courses" => $courses]);
} else {
    http_response_code(404);
    echo json_encode(["success" => false, "message" => "No courses found"]);
}

// Close connection
$conn->close();
?>