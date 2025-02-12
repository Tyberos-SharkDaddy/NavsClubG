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

// Check if 'id' exists in session
if (!isset($_SESSION['id'])) {
    die(json_encode(["error" => "User ID not found in session."]));
}

$user_id = $_SESSION['id']; // Fetch user ID

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
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Information</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            width: 100%;
            max-width: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h3 {
            text-align: center;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
        }
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h3>General Information</h3>
    <form id="updateForm">
        <input type="hidden" name="user_id" id="id" value="<?= $id; ?>">
        <input type="hidden" name="action" value="update">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" readonly>

        <label for="password_hash">Password Hash:</label>
        <input type="text" id="password_hash" name="password_hash" readonly>

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name">

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name">

        <label for="nickname">Nickname:</label>
        <input type="text" id="nickname" name="nickname">

        <label for="rank">Rank:</label>
        <input type="text" id="rank" name="rank">

        <button type="submit" class="btn">Update</button>
    </form>
</div>

<script>
$(document).ready(function(){
    let id = $("#id").val();

    // Fetch user data
    $.ajax({
        url: "general_information.php",
        type: "POST",
        data: { action: "fetch" },
        dataType: "json",
        success: function(response) {
            if (response.error) {
                alert(response.error);
            } else {
                $("#email").val(response.email);
                $("#password_hash").val(response.password_hash);
                $("#first_name").val(response.first_name);
                $("#last_name").val(response.last_name);
                $("#nickname").val(response.nickname);
                $("#rank").val(response.rank);
            }
        }
    });

    // Update user data
    $("#updateForm").submit(function(e){
        e.preventDefault();

        $.ajax({
            url: "general_information.php",
            type: "POST",
            data: $("#updateForm").serialize(),
            dataType: "json",
            success: function(response) {
                alert(response.success || response.error);
            }
        });
    });
});
</script>

</body>
</html>
