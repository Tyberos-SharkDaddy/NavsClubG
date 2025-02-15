<?php
// Connect to the database
$conn = mysqli_connect('localhost', 'root', '', 'navsclub');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Allowed image extensions and MIME types
$allowed_extensions = ['jpg', 'jpeg', 'png'];
$allowed_mime_types = ['image/jpeg', 'image/png'];

// Set max file size (2MB)
$max_file_size = 2 * 1024 * 1024; // 2MB

$response = ['success' => false, 'error' => 'No file uploaded!'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['files'])) {
    $uploadedFiles = $_FILES['files'];
    
    // Handle multiple file uploads
    $successCount = 0;
    $errors = [];

    for ($i = 0; $i < count($uploadedFiles['name']); $i++) {
        $file = [
            'name' => $uploadedFiles['name'][$i],
            'tmp_name' => $uploadedFiles['tmp_name'][$i],
            'size' => $uploadedFiles['size'][$i],
            'type' => $uploadedFiles['type'][$i]
        ];

        $result = handleImageUpload($file, $conn, $allowed_extensions, $allowed_mime_types, $max_file_size);
        if ($result['success']) {
            $successCount++;
        } else {
            $errors[] = $result['error'];
        }
    }

    if ($successCount > 0) {
        $response = ['success' => true, 'message' => "$successCount image(s) uploaded successfully."];
    } else {
        $response = ['success' => false, 'error' => implode(", ", $errors)];
    }
}

mysqli_close($conn);
echo json_encode($response);

/**
 * Function to handle a single image upload.
 */
function handleImageUpload($file, $conn, $allowed_extensions, $allowed_mime_types, $max_file_size) {
    $filename = $file['name'];
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $tempFile = $file['tmp_name'];
    $size = $file['size'];
    $mime_type = mime_content_type($tempFile);

    if (!in_array($extension, $allowed_extensions) || !in_array($mime_type, $allowed_mime_types)) {
        return ['success' => false, 'error' => "Invalid file type! Only JPG, JPEG, and PNG images are allowed."];
    }

    if ($size > $max_file_size) {
        return ['success' => false, 'error' => 'Image too large! Maximum allowed size is 2MB.'];
    }

    return storeImageInDatabase($conn, $filename, $size, $mime_type, $tempFile);
}

/**
 * Function to store an image in the database.
 */
function storeImageInDatabase($conn, $filename, $size, $mime_type, $tempFile) {
    $imageData = file_get_contents($tempFile); // Get image data as BLOB

    $sql = "INSERT INTO files (name, size, type, data) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sisb", $filename, $size, $mime_type, $imageData);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        mysqli_stmt_close($stmt);
        return ['success' => true, 'message' => "Image '$filename' uploaded successfully."];
    } else {
        return ['success' => false, 'error' => 'Database error: ' . mysqli_error($conn)];
    }
}
?>
