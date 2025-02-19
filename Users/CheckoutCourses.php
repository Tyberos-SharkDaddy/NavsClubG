<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Course</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .loading { display: none; }
        .btn-custom { margin: 3px; }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">My Course Cart</h2>
    <div class="text-center loading">
        <div class="spinner-border text-primary"></div>
        <p>Loading courses...</p>
    </div>
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
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
    <div id="toastMessage" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <!-- Toast message will be inserted here -->
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    function showToast(message, type = "success") {
        let toastEl = $("#toastMessage");

        if (toastEl.length === 0) {
            console.error("Toast element not found!");
            return;
        }

        toastEl.removeClass("bg-success bg-danger bg-warning").addClass(`bg-${type}`);
        toastEl.find(".toast-body").text(message);

        let toastInstance = bootstrap.Toast.getInstance(toastEl[0]) || new bootstrap.Toast(toastEl[0]);
        toastInstance.show();
    }

    function fetchCourses() {
        $(".loading").show();
        $.ajax({
            url: "fetchAddtoCart.php",
            method: "GET",
            dataType: "json",
            success: function (response) {
                $(".loading").hide();
                let rows = response.map((course) => `
                    <tr id="course-${course.CourseID}" data-course-id="${course.CourseID}">
                        <td class="course-name">${course.CourseName}</td>
                        <td>${course.AboutCourse}</td>
                        <td>${course.Duration}</td>
                        <td>
                            <button class="btn btn-success btn-sm btn-custom checkout-btn">Checkout</button>
                            <button class="btn btn-primary btn-sm btn-custom action-btn" data-action="save_later">Save for Later</button>
                            <button class="btn btn-danger btn-sm btn-custom action-btn" data-action="remove">Remove</button>
                            <button class="btn btn-warning btn-sm btn-custom action-btn" data-action="move_wishlist">Move to Wishlist</button>
                        </td>
                    </tr>
                `).join("");
                $("#courseTable").html(rows);
            },
            error: function () {
                $(".loading").hide();
                showToast("Failed to fetch courses.", "danger");
            }
        });
    }

    fetchCourses();

    $(document).on("click", ".checkout-btn", function () {
    showToast("Proceeding to billing...", "success");

    // Redirect to Billing.php after a short delay
    setTimeout(function () {
        window.location.href = "PaymentChecking.php";
    }, 2000); // 2-second delay
});

    $(document).on("click", ".action-btn", function () {
        let action = $(this).data("action");
        let courseID = $(this).closest("tr").data("course-id");

        if (!courseID || !action) {
            showToast("Invalid course or action!", "danger");
            return;
        }

        $.ajax({
            url: "CourseActions.php",
            type: "POST",
            dataType: "json",
            data: { action: action, courseID: courseID },
            success: function (response) {
                if (response.status === "success") {
                    showToast(response.message, "success");

                    if (action === "remove" || action === "move_wishlist") {
                        $(`#course-${courseID}`).fadeOut();
                    }
                } else {
                    showToast(response.message, "danger");
                }
            },
            error: function () {
                showToast("Something went wrong!", "danger");
            }
        });
    });
});

</script>

</body>
</html>
