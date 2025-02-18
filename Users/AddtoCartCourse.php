<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session to track cart items
session_start();

// Establish a database connection
$conn = new mysqli("localhost", "root", "", "navsclub");

// Check database connection
if ($conn->connect_error) {
    echo json_encode([
        "success" => false, 
        "error" => "Database connection failed"
    ]);
    exit();
}

// Check if courseId is passed via POST and sanitize the input
if(isset($_POST['courseId'])) { // Changed from CourseID to courseId to match the AJAX request
    $courseId = intval($_POST['courseId']); // Sanitize input

    // Check if courseId is valid
    if ($courseId <= 0) {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid course ID'
        ]);
        exit();
    }

    // Initialize cart in session if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the course already exists in the cart
    if (!in_array($courseId, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $courseId;
    }

    // Get the updated cart count
    $cartCount = count($_SESSION['cart']);

    // Return a success response with cart count
    echo json_encode([
        'success' => true, 
        'cartCount' => $cartCount,
        'message' => 'Course added to cart successfully'
    ]);
} else {
    // If courseId is not passed, return an error message
    echo json_encode([
        'success' => false,
        'error' => 'No course ID provided'
    ]);
}

// Close the database connection
$conn->close();
?>
