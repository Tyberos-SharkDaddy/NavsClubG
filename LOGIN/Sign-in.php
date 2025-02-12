<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$username = "root";
$password = "";
$dbname = "navsclub";

// Create a connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if ($conn == false) {
    die("Connection problem: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, email, password_hash, first_name, last_name, nickname, rank, usertype, role FROM personal_information WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    // Check if any row is returned
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Debugging output (remove this in production)
        echo "Debug: Email from DB: " . htmlspecialchars($row['email']) . "<br>";
        echo "Debug: Password Hash from DB: " . htmlspecialchars($row['password_hash']) . "<br>";
        echo "Debug: User type from DB: " . htmlspecialchars($row['usertype']) . "<br>";
        echo "Debug: Role from DB: " . htmlspecialchars($row['role']) . "<br>";

        // Verify password (hashed for security)
        if (password_verify($password, $row['password_hash'])) {
            // Start session and store user data
            session_start();
            $_SESSION['id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['nickname'] = $row['nickname'];
            $_SESSION['rank'] = $row['rank'];
            $_SESSION['usertype'] = $row['usertype'];
            $_SESSION['role'] = $row['role'];

            // Redirect based on user type
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
