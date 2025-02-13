<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "navsclub";

// Create a database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure POST data exists and contains required fields
if (!empty($_POST['rank']) && !empty($_POST['company'])) {
    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO work_experience (rank, company, work_date, work_day, work_month, work_year) VALUES (?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("<div class='alert alert-danger'>SQL Error: " . $conn->error . "</div>");
    }

    // Loop through the submitted data
    foreach ($_POST['rank'] as $index => $rank) {
        $company = $_POST['company'][$index] ?? "";
        $work_date = $_POST['work_date'][$index] ?? "";
        $work_day = $_POST['work_day'][$index] ?? "";
        $work_month = $_POST['work_month'][$index] ?? "";
        $work_year = $_POST['work_year'][$index] ?? 0;

        // Bind parameters and execute the query
        $stmt->bind_param("sssssi", $rank, $company, $work_date, $work_day, $work_month, $work_year);
        $stmt->execute();
    }

    $stmt->close();
    echo "<div class='alert alert-success'>Work experience saved successfully!</div>";
} else {
    echo "<div class='alert alert-danger'>No data received. Please fill out all required fields.</div>";
}

// Close the connection
$conn->close();
?>