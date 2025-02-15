<?php
header('Content-Type: application/json');

$conn = mysqli_connect('localhost', 'root', '', 'navsclub');

if (!$conn) {
    echo json_encode(["success" => false, "error" => "Database connection failed"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['file_id'])) {
    $file_id = intval($_POST['file_id']);

    if ($file_id <= 0) {
        echo json_encode(["success" => false, "error" => "Invalid file ID"]);
        exit;
    }

    $stmt = mysqli_prepare($conn, "SELECT name, type, data FROM files WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $file_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $name, $type, $data);
        mysqli_stmt_fetch($stmt);
        
        // Convert file data to base64 for inline display
        $base64Data = base64_encode($data);

        echo json_encode([
            "success" => true,
            "file_name" => $name,
            "file_type" => $type,
            "file_data" => $base64Data
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "File not found"]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}

mysqli_close($conn);
?>
