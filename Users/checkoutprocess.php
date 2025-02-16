<?php
$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$database = "navsclub"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

// Handle POST request (Insert Order)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $course_name = $_POST["course_name"];
    $amount = $_POST["amount"];
    $payment_method = $_POST["payment_method"];

    // Optional fields (only if payment method is 'Card')
    $card_number = isset($_POST["card_number"]) ? $_POST["card_number"] : null;
    $expiry_date = isset($_POST["expiry_date"]) ? $_POST["expiry_date"] : null;
    $cvv = isset($_POST["cvv"]) ? $_POST["cvv"] : null;

    // Prepare SQL statement to insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, course_name, amount, payment_method, card_number, expiry_date, cvv) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdssss", $user_id, $course_name, $amount, $payment_method, $card_number, $expiry_date, $cvv);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Order placed successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Order failed: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit();
}

// Handle GET request (Fetch Courses Instead of Orders)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql = "SELECT CourseID, CourseName FROM courses ORDER BY CourseID DESC"; // Fetch only CourseID & CourseName
    $result = $conn->query($sql);

    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }

    echo json_encode($courses); // Return JSON response

    $conn->close();
    exit();
}
?>
