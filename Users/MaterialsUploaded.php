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
            <button class="btn btn-primary btn-sm view-file" data-file="<?php echo $file['name']; ?>">View</button>
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
        let fileName = $(this).data("file");
        let fileExt = fileName.split('.').pop().toLowerCase();
        let filePath = "uploads/" + fileName;
        let previewHTML = "";

        if (["jpg", "jpeg", "png", "gif"].includes(fileExt)) {
            previewHTML = `<img src="${filePath}" class="img-fluid" alt="Image Preview">`;
        } else if (fileExt === "pdf") {
            previewHTML = `<iframe src="${filePath}" width="100%" height="500px"></iframe>`;
        } else if (fileExt === "txt") {
            $.get(filePath, function(data) {
                previewHTML = `<pre>${data}</pre>`;
                $("#filePreview").html(previewHTML);
            });
            return;
        } else {
            previewHTML = `<p class="text-danger">Unsupported file format</p>`;
        }

        $("#filePreview").html(previewHTML);
        $("#fileModal").modal("show");
    });
});
</script>

</body>
</html>
