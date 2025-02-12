<?php
// Include the database connection
include('db.php');

if (!$conn) {
    die(json_encode(['error' => 'Database connection failed.']));
}

// Check if the 'file_id' parameter is passed via GET for download
if (isset($_GET['file_id'])) {
    $id = intval($_GET['file_id']); // Ensure it's an integer

    // Fetch file details using prepared statement
    $stmt = $conn->prepare ("SELECT name, downloads FROM files WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $file = $result->fetch_assoc();
        $filepath = 'uploads/' . $file['name'];

        // Check if the file exists on the server
        if (file_exists($filepath)) {
            // Set headers for file download
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));

            // Prevent output buffering issues
            ob_clean();
            flush();

            // Read file and send it to the browser
            $fileHandle = fopen($filepath, 'rb');
            fpassthru($fileHandle);
            fclose($fileHandle);

            // Update the download count using prepared statement
            $newCount = $file['downloads'] + 1;
            $updateStmt = $conn->prepare("UPDATE files SET downloads = ? WHERE id = ?");
            $updateStmt->bind_param("ii", $newCount, $id);
            $updateStmt->execute();
            $updateStmt->close();

            exit;
        } else {
            http_response_code(404);
            die('File not found!');
        }
    } else {
        http_response_code(400);
        die('Error fetching file from database.');
    }
} else {
    // Fetch the files from the database, excluding the 'downloads' column
    $sql = "SELECT id, name, size FROM files"; // Only select 'id', 'name', and 'size'
    $result = $conn->query($sql);

    header('Content-Type: application/json');

    if ($result->num_rows > 0) {
        $files = [];

        while ($row = $result->fetch_assoc()) {
            $files[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'size' => $row['size']
            ];
        }

        echo json_encode($files);
    } else {
        echo json_encode(['error' => 'No files found.']);
    }
}

// Close the database connection
$conn->close();
?>
