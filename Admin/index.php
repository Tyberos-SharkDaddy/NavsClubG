<?php
// Connect to the database
$conn = mysqli_connect('localhost', 'root', '', 'navsclub');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all files from the database
$sql = "SELECT id, name, size, type FROM files";
$result = mysqli_query($conn, $sql);

$files = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File & Folder Upload</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4">Upload Files or Folders</h3>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- File Upload Form -->
                <form id="fileUploadForm" enctype="multipart/form-data" class="border p-4 shadow rounded bg-light mb-4">
                    <h5 class="text-center">Upload Files</h5>
                    <div class="mb-3">
                        <input type="file" id="fileInput" name="files[]" multiple class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success w-100">Upload Files</button>
                </form>

                <!-- Folder Upload Form -->
                
                
                <div id="uploadStatus"></div>
            </div>
        </div>

        <!-- File List -->
        <h3 class="text-center mt-5">Available Files & Folders</h3>
        <table class="table table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Filename</th>
                    <th>Size (KB)</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="fileTableBody">
                <?php foreach ($files as $file): ?>
                <tr>
                    <td><?php echo $file['id']; ?></td>
                    <td><?php echo $file['name']; ?></td>
                    <td><?php echo number_format($file['size'] / 1024, 2); ?></td>
                    <td><?php echo ucfirst($file['type']); ?></td>
                    <td>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $file['id']; ?>">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
   $(document).ready(function() {
    function handleUpload(form, inputSelector) {
        $(form).on("submit", function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let files = $(inputSelector)[0].files;
            let valid = true;
            let allowedExtensions = ["pdf", "xls", "xlsx", "jpg", "jpeg", "png", "gif", "zip"];
            let maxFileSize = 2 * 1024 * 1024; // 2MB for files, allow ZIP as well

            if (files.length === 0) {
                $("#uploadStatus").html('<div class="alert alert-danger">No file selected!</div>');
                return;
            }

            for (let i = 0; i < files.length; i++) {
                let fileType = files[i].name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(fileType) || files[i].size > maxFileSize) {
                    valid = false;
                    break;
                }
            }

            if (!valid) {
                $("#uploadStatus").html('<div class="alert alert-danger">Invalid file! Only PDF, Excel, Image, and ZIP files under 2MB are allowed.</div>');
                return;
            }

            $.ajax({
                url: "upload_file.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#uploadStatus").html('<div class="alert alert-info">Uploading...</div>');
                },
                success: function(response) {
                    try {
                        let res = JSON.parse(response);
                        if (res.success) {
                            $("#uploadStatus").html('<div class="alert alert-success">' + res.message + '</div>');
                            $(form)[0].reset();
                            loadFileList(); // Refresh file list
                        } else {
                            $("#uploadStatus").html('<div class="alert alert-danger">' + res.error + '</div>');
                        }
                    } catch (err) {
                        $("#uploadStatus").html('<div class="alert alert-danger">Unexpected server response.</div>');
                    }
                },
                error: function(xhr) {
                    $("#uploadStatus").html('<div class="alert alert-danger">Error: ' + xhr.responseText + '</div>');
                }
            });
        });
    }

    handleUpload("#fileUploadForm", "#fileInput");

    function loadFileList() {
        $.ajax({
            url: 'fetchFiles.php',
            type: 'GET',
            success: function(data) {
                $('#fileTableBody').html(data);
                attachDeleteEvents();
            }
        });
    }

    function attachDeleteEvents() {
        $(".delete-btn").off("click").on("click", function() {
            const fileId = $(this).attr("data-id");

            if (confirm("Are you sure you want to delete this file?")) {
                $.ajax({
                    url: "deleteFile.php",
                    type: "POST",
                    data: { delete_id: fileId },
                    success: function(response) {
                        if (response.trim() === "success") {
                            alert("File deleted successfully!");
                            loadFileList();
                        } else {
                            alert("Error deleting file!");
                        }
                    },
                    error: function() {
                        alert("Error processing request.");
                    }
                });
            }
        });
    }

    attachDeleteEvents();
});



    </script>
</body>
</html>