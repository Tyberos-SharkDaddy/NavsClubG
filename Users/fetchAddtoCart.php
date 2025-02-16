<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "navsclub");

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Set JSON header
header("Content-Type: application/json");

// Fetch only CourseID, CourseName, AboutCourse, and Duration
$sql = "SELECT CourseID, CourseName, AboutCourse, Duration FROM courses";
$result = $conn->query($sql);

if (!$result) {
    die(json_encode(["error" => "Query failed: " . $conn->error]));
}

$courses = [];

while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

// Return JSON response
echo json_encode($courses);

$conn->close();
?>