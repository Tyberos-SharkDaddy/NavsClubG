<?php include 'download_file.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <title>Download & View Files</title>
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-3">Download & View Files</h2>
    <table class="table table-bordered">
    <thead class="table-dark">
        <th>ID</th>
        <th>Filename</th>
        <th>Size (KB)</th>
        <th>Downloads</th>
        <th>Actions</th>
    </thead>
    <tbody>
    <?php foreach ($files as $file): ?>
        <tr>
          <td><?php echo $file['id']; ?></td>
          <td><?php echo $file['name']; ?></td>
          <td><?php echo floor($file['size'] / 1000) . ' KB'; ?></td>
          <td><?php echo $file['downloads']; ?></td>
          <td>
            <a href="download_file.php?file_id=<?php echo $file['id']; ?>" class="btn btn-success btn-sm">Download</a>
            <button class="btn btn-primary btn-sm view-file" data-id="<?php echo $file['id']; ?>">View</button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
</div>

<!-- 🔹 Modal for Viewing Files -->
<div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="fileModalLabel">File Viewer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center" id="filePreview">
        <!-- Content will be loaded here dynamically -->
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $(".view-file").click(function() {
        let fileId = $(this).attr("data-id"); // Fetch file ID from button

        if (!fileId) {
            alert("Invalid file ID!");
            return;
        }

        $.ajax({
            url: "fetch_file.php",
            type: "POST",
            data: { file_id: fileId },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    let fileExt = response.file_name.split('.').pop().toLowerCase();
                    let previewHTML = "";

                    if (["jpg", "jpeg", "png", "gif"].includes(fileExt)) {
                        previewHTML = `<img src="data:${response.file_type};base64,${response.file_data}" class="img-fluid" alt="Image Preview">`;
                    } else if (fileExt === "pdf") {
                        previewHTML = `<iframe src="data:${response.file_type};base64,${response.file_data}" width="100%" height="500px"></iframe>`;
                    } else if (fileExt === "docx") {
                        previewHTML = `<iframe src="https://docs.google.com/gview?url=data:${response.file_type};base64,${response.file_data}&embedded=true" width="100%" height="500px"></iframe>`;
                    } else if (fileExt === "txt") {
                        previewHTML = `<pre>${atob(response.file_data)}</pre>`;
                    } else {
                        previewHTML = `<p class="text-danger">Unsupported file format</p>`;
                    }

                    $("#filePreview").html(previewHTML);
                    $("#fileModal").modal("show");
                } else {
                    alert(response.error);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                alert("Error loading file.");
            }
        });
    });
});

</script>

</body>
</html>
