<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$database = "navsclub";

// Database connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database Connection Failed"]);
    exit;
}

// Check request method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    exit;
}

// Validate input
$action = $_POST['action'] ?? '';
$courseID = $_POST['courseID'] ?? '';

if (empty($courseID) || !is_numeric($courseID)) {
    echo json_encode(["status" => "error", "message" => "Invalid Course ID"]);
    exit;
}

// Define valid actions and corresponding statuses
$validActions = [
    "save_later" => "Saved for Later",
    "remove" => "Hidden",  // Instead of deleting, it will be hidden
    "move_wishlist" => "Wishlist"
];

if (!array_key_exists($action, $validActions)) {
    echo json_encode(["status" => "error", "message" => "Invalid action"]);
    exit;
}

// Move to wishlist (Insert into wishlist table & update course status)
if ($action === "move_wishlist") {
    // Get course details
    $query = "SELECT CourseID, CourseName, AboutCourse, Duration FROM courses WHERE CourseID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $courseID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Course not found"]);
        exit;
    }

    $course = $result->fetch_assoc();
    $stmt->close();

    // Insert into wishlist (Always insert, even if already exists)
    $insertQuery = "INSERT INTO wishlist (CourseID, CourseName, AboutCourse, Duration) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("isss", $course['CourseID'], $course['CourseName'], $course['AboutCourse'], $course['Duration']);

    if (!$stmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Failed to add to Wishlist"]);
        exit;
    }
    $stmt->close();
}

// Update course status (Hidden for remove, Wishlist for move_wishlist)
$query = "UPDATE courses SET status=? WHERE CourseID=?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
    exit;
}

// Bind parameters and execute
$status = $validActions[$action];
$stmt->bind_param("si", $status, $courseID);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => ucfirst(str_replace("_", " ", $action)) . " successful"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database execution error: " . $stmt->error]);
}

// Close connections
$stmt->close();
$conn->close();
?>
