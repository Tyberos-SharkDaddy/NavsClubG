<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'navsclub');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$response = ['success' => false, 'error' => 'No file uploaded!'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['folder'])) {
    $file = $_FILES['folder'];

    $filename = $file['name'];
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $tempFile = $file['tmp_name'];
    $size = $file['size'];
    $mime_type = mime_content_type($tempFile);

    // Allow only ZIP files
    if ($extension !== 'zip' || $mime_type !== 'application/zip') {
        $response = ['success' => false, 'error' => 'Only ZIP files are allowed!'];
    } elseif ($size > 5 * 1024 * 1024) { // Limit to 5MB
        $response = ['success' => false, 'error' => 'ZIP file is too large! Max size: 5MB.'];
    } else {
        $zipData = file_get_contents($tempFile); // Read ZIP file as BLOB

        $sql = "INSERT INTO files (name, size, type, data) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sisb", $filename, $size, $mime_type, $null);
        mysqli_stmt_send_long_data($stmt, 3, $zipData);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            mysqli_stmt_close($stmt);
            $response = ['success' => true, 'message' => "ZIP file '$filename' uploaded successfully."];
        } else {
            $response = ['success' => false, 'error' => 'Database error: ' . mysqli_error($conn)];
        }
    }
}

mysqli_close($conn);
echo json_encode($response);
?>