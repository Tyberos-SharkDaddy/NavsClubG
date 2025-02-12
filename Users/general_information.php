<?php
session_start();
$host = "localhost";
$username = "root";
$password = "";
$dbname = "navsclub";
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die(json_encode(["error" => "Database connection failed!"]));
}

// Check if request is AJAX
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    if ($action === "fetch") {
        // Check if session ID exists
        if (!isset($_SESSION["id"])) {
            die(json_encode(["error" => "User ID not found in session."]));
        }

        $user_id = $_SESSION["id"];

        // Fetch user data
        $sql = "SELECT id, email, password_hash, first_name, last_name, nickname, rank FROM personal_information WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            echo json_encode($user);
        } else {
            echo json_encode(["error" => "User not found"]);
        }

        $stmt->close();
        exit();
    }

    if ($action === "update") {
        // Validate input
        if (!isset($_POST["first_name"], $_POST["last_name"], $_POST["nickname"], $_POST["rank"])) {
            die(json_encode(["error" => "All fields are required"]));
        }

        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $nickname = $_POST["nickname"];
        $rank = $_POST["rank"];
        $user_id = $_SESSION["id"];

        // Update user data
        $sql = "UPDATE personal_information SET first_name=?, last_name=?, nickname=?, rank=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $first_name, $last_name, $nickname, $rank, $user_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => "User information updated successfully!"]);
        } else {
            echo json_encode(["error" => "Failed to update user data"]);
        }

        $stmt->close();
        exit();
    }
}

$conn->close();
?>
