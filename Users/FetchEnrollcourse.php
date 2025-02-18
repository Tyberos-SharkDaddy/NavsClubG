<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "navsclub");

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

$sql = "SELECT CourseID, CourseName, CourseLevel, AboutCourse, Audience, Duration, CourseBy, EnrolledCount, Price FROM courses";
$result = $conn->query($sql);

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

echo json_encode($courses);
$conn->close();
?>
