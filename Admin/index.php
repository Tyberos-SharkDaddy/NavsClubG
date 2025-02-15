<?php include 'fileuploadLogic.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload & Download</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
  
    <div class="container mt-5">
        <h3 class="text-center mb-4">Upload File</h3>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="index.php" method="post" enctype="multipart/form-data" class="border p-4 shadow rounded bg-light">
                    <div class="mb-3">
                        <input type="file" name="myfile" class="form-control">
                    </div>
                    <button type="submit" name="save" class="btn btn-primary w-100">Upload</button>
                </form>
            </div>
        </div>

        <!-- File List -->
        <h3 class="text-center mt-5">Available Files</h3>
        <table class="table table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Filename</th>
                    <th>Size (KB)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file): ?>
                <tr>
                    <td><?php echo $file['id']; ?></td>
                    <td><?php echo $file['name']; ?></td>
                    <td><?php echo number_format($file['size'] / 1024, 2); ?></td>
                    <td>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $file['id']; ?>">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function() {
            const fileId = this.getAttribute("data-id");

            if (confirm("Are you sure you want to delete this file?")) {
                fetch("deleteFile.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "delete_id=" + fileId
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "success") {
                        alert("File deleted successfully!");
                        this.closest("tr").remove(); // Remove the file row from table
                    } else {
                        alert("Error deleting file!");
                    }
                })
                .catch(error => console.error("Error:", error));
            }
        });
    });
});
</script>


</body>
</html>
