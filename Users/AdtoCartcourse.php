<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">My Course Cart</h2>
    
    <!-- Loading Indicator -->
    <div class="text-center loading" style="display: none;">
        <div class="spinner-border text-primary"></div>
        <p>Loading courses...</p>
    </div>

    <!-- Table -->
    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>Course Name</th>
                <th>About</th>
                <th>Duration</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="courseTable">
            <!-- Courses will be loaded here dynamically -->
        </tbody>
    </table>
</div>

<!-- Toast Notification -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="toastMessage" class="toast align-items-center text-white bg-success" role="alert" aria-live="polite" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
    function showToast(message, type = "success") {
        let toastEl = $("#toastMessage");
        toastEl.removeClass("bg-success bg-danger").addClass(`bg-${type}`);
        toastEl.find(".toast-body").text(message);
        new bootstrap.Toast(toastEl[0]).show();
    }

    function fetchCourses() {
        $(".loading").show();
        $.ajax({
            url: "fetchAddtoCart.php",
            method: "GET",
            dataType: "json",
            success: function(response) {
                $(".loading").hide();
                let rows = response.map(course => `
                    <tr data-course-id="${course.CourseID}">
                        <td>${course.CourseName}</td>
                        <td>${course.AboutCourse}</td>
                        <td>${course.Duration}</td>
                        <td>
                            <button class="btn btn-primary btn-sm action-btn" data-action="save_later">Save for Later</button>
                            <button class="btn btn-danger btn-sm action-btn" data-action="remove">Remove</button>
                            <button class="btn btn-warning btn-sm action-btn" data-action="move_wishlist">Move to Wishlist</button>
                            <button class="btn btn-success btn-sm checkout-btn">Checkout</button>
                        </td>
                    </tr>
                `).join("");
                $("#courseTable").html(rows);
            },
            error: function() {
                $(".loading").hide();
                showToast("Error fetching courses!", "danger");
            }
        });
    }

    fetchCourses(); // Load courses on page load

    $(document).on("click", ".checkout-btn", function() {
        let courseID = $(this).closest("tr").data("course-id");
        if (courseID) {
            window.location.href = `CheckingOut.php?courseID=${courseID}`;
        }
    });
});

</script>
</body>
</html>
