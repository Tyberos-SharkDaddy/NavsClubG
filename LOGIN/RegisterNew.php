<?php
// Database connection details
$host = "localhost"; // Change if necessary
$user = "root";      // Change to your database username
$pass = "";          // Change to your database password
$dbname = "navsclub"; // Change to your database name

// Connect to database
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $nickname = trim($_POST["nickname"]);
    $rank = trim($_POST["rank"]);
    $confirmed = isset($_POST["confirmed"]) ? 1 : 0;

    // Validate passwords match
    if ($password !== $confirm_password) {
        die("Error: Passwords do not match.");
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_email = $conn->prepare("SELECT id FROM personal_information WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        die("Error: Email is already registered.");
    }
    $check_email->close(); // Closing only after checking email

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO personal_information (email, password_hash, first_name, last_name, nickname, rank, confirmed) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $email, $password_hash, $first_name, $last_name, $nickname, $rank, $confirmed);

    if ($stmt->execute()) {
        echo "Success: Account created!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close resources
    $stmt->close();
    $conn->close();
}
?>
