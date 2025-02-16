<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include database configuration (modify this file to store credentials)
include 'config.php';

// Create a connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection problem: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, password_hash, first_name, last_name, nickname, rank, usertype, role FROM personal_information WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $row['password_hash'])) {
            // Start session and store user data
            session_start();
            $_SESSION['id'] = $row['id'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['nickname'] = $row['nickname'];
            $_SESSION['rank'] = $row['rank'];
            $_SESSION['usertype'] = $row['usertype'];
            $_SESSION['role'] = $row['role'];

            // Redirect admin to Appointment Notifications
            if ($row["usertype"] == "admin") {
                header("Location: http://localhost/Repair-Shop-Locator-new/ADMIN/AppointmentNotifications.php");
                exit();
            } else {
                header("Location: http://localhost/Repair-Shop-Locator-new/Landing-Page/Home.php");
                exit();
            }
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "No user found with this email.";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
