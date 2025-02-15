<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "navsclub"; // Ensure this database exists

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
    $sql = "SELECT * FROM courses";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
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
        echo "<tr><td colspan='8'>No records found</td></tr>";
    }
}

// Add a new course
if ($action == 'add') {
    if(isset($_POST['courseName'], $_POST['aboutCourse'], $_POST['audience'], $_POST['courseLevel'], $_POST['duration'], $_POST['courseBy'])){
        $courseName = $_POST['courseName'];
        $aboutCourse = $_POST['aboutCourse'];
        $audience = $_POST['audience'];
        $courseLevel = $_POST['courseLevel'];
        $duration = $_POST['duration'];
        $courseBy = $_POST['courseBy'];

        $stmt = $conn->prepare("INSERT INTO courses (CourseName, AboutCourse, Audience, CourseLevel, Duration, CourseBy) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $courseName, $aboutCourse, $audience, $courseLevel, $duration, $courseBy);

        if ($stmt->execute()) {
            echo "success";
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
    if(isset($_POST['courseID'], $_POST['courseName'], $_POST['aboutCourse'], $_POST['audience'], $_POST['courseLevel'], $_POST['duration'], $_POST['courseBy'])){
        $courseID = $_POST['courseID'];
        $courseName = $_POST['courseName'];
        $aboutCourse = $_POST['aboutCourse'];
        $audience = $_POST['audience'];
        $courseLevel = $_POST['courseLevel'];
        $duration = $_POST['duration'];
        $courseBy = $_POST['courseBy'];

        $stmt = $conn->prepare("UPDATE courses SET CourseName=?, AboutCourse=?, Audience=?, CourseLevel=?, Duration=?, CourseBy=? WHERE CourseID=?");
        $stmt->bind_param("ssssssi", $courseName, $aboutCourse, $audience, $courseLevel, $duration, $courseBy, $courseID);

        if ($stmt->execute()) {
            echo "success";
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
    if(isset($_POST['courseID'])){
        $courseID = $_POST['courseID'];

        $stmt = $conn->prepare("DELETE FROM courses WHERE CourseID=?");
        $stmt->bind_param("i", $courseID);

        if ($stmt->execute()) {
            echo "success";
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
