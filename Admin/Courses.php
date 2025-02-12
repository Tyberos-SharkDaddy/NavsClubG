<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "navsclub"; // Make sure this is the correct database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle actions
$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

// List all courses
if ($action == 'list') {
    $sql = "SELECT * FROM courses"; // Adjusted table name and fields
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['CourseID']}</td>
                    <td>{$row['CourseName']}</td>
                    <td>{$row['CourseLevel']}</td>
                    <td>{$row['AboutCourse']}</td>
                    <td>{$row['Audience']}</td>
                    <td>{$row['Duration']}</td>                    
                    <td>{$row['CourseBy']}</td>
                    <td>
                        <button class='view-btn' data-id='{$row['CourseID']}'>View</button>
                        <button class='edit-btn' data-id='{$row['CourseID']}'>Edit</button>
                        <button class='delete-btn' data-id='{$row['CourseID']}'>Delete</button>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No records found</td></tr>";
    }
}

// Add a new course
if ($action == 'add') {
    // Validate inputs
    if(isset($_POST['courseName'], $_POST['aboutCourse'], $_POST['audience'], $_POST['courseLevel'], $_POST['duration'], $_POST['courseBy'])){
        $courseName = $_POST['courseName'];
        $aboutCourse = $_POST['aboutCourse'];
        $audience = $_POST['audience'];
        $courseLevel = $_POST['courseLevel'];
        $duration = $_POST['duration'];
        $courseBy = $_POST['courseBy'];

        // Prepared statement to insert new course
        $stmt = $conn->prepare("INSERT INTO courses (courseName, aboutCourse, audience, courseLevel, duration, courseBy) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $courseName, $aboutCourse, $audience, $courseLevel, $duration, $courseBy);

        if ($stmt->execute()) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "All fields are required!";
    }
}

// Edit an existing course
if ($action == 'edit') {
    // Validate inputs
    if(isset($_POST['courseID'], $_POST['courseName'], $_POST['aboutCourse'], $_POST['audience'], $_POST['courseLevel'], $_POST['duration'], $_POST['courseBy'])){
        $courseID = $_POST['courseID'];
        $courseName = $_POST['courseName'];
        $aboutCourse = $_POST['aboutCourse'];
        $audience = $_POST['audience'];
        $courseLevel = $_POST['courseLevel'];
        $duration = $_POST['duration'];
        $courseBy = $_POST['courseBy'];

        // Prepared statement to update course
        $stmt = $conn->prepare("UPDATE courses SET courseName=?, aboutCourse=?, audience=?, courseLevel=?, duration=?, courseBy=? WHERE courseID=?");
        $stmt->bind_param("ssssssi", $courseName, $aboutCourse, $audience, $courseLevel, $duration, $courseBy, $courseID);

        if ($stmt->execute()) {
            echo "Record updated successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "All fields are required!";
    }
}

// Delete a course
if ($action == 'delete') {
    // Validate courseID
    if(isset($_POST['courseID'])){
        $courseID = $_POST['courseID'];

        // Prepared statement to delete course
        $stmt = $conn->prepare("DELETE FROM courses WHERE courseID=?");
        $stmt->bind_param("i", $courseID);

        if ($stmt->execute()) {
            echo "Record deleted successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Course ID is required!";
    }
}

// View a specific course
if ($action == 'view') {
    if(isset($_GET['id'])){
        $courseID = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM courses WHERE CourseID=?");
        $stmt->bind_param("i", $courseID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode($result->fetch_assoc());
        } else {
            echo json_encode(['error' => 'No record found']);
        }
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Course ID is required']);
    }
}

$conn->close();
?>
