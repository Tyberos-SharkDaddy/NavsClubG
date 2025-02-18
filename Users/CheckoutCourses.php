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

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">Checkout Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="checkoutForm">
                    <div class="mb-3">
                        <label class="form-label">Course Name</label>
                        <input type="text" id="checkoutCourseName" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select id="payment_method" class="form-control">
                            <option value="Credit Card">Credit Card</option>
                            <option value="PayPal">PayPal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Card Number</label>
                        <input type="text" id="card_number" class="form-control" placeholder="XXXX-XXXX-XXXX-XXXX">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Expiry Date</label>
                            <input type="text" id="expiry_date" class="form-control" placeholder="MM/YY">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CVV</label>
                            <input type="text" id="cvv" class="form-control" placeholder="123">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="confirmCheckout" class="btn btn-success">Confirm Payment</button>
            </div>
        </div>
    </div>
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
        let row = $(this).closest("tr");
        let courseId = row.data("course-id");
        let courseName = row.find(".course-name").text().trim();

        $("#checkoutCourseName").val(courseName);
        $("#checkoutModal").modal("show");

        $("#confirmCheckout").off("click").on("click", function () {
            let payment_method = $("#payment_method").val();
            let card_number = $("#card_number").val();
            let expiry_date = $("#expiry_date").val();
            let cvv = $("#cvv").val();

            if (!card_number || !expiry_date || !cvv) {
                showToast("Please fill in all payment details!", "danger");
                return;
            }

            $.ajax({
                url: "checkout.php",
                method: "POST",
                data: {
                    courseId: courseId,
                    courseName: courseName,
                    payment_method: payment_method,
                    card_number: card_number,
                    expiry_date: expiry_date,
                    cvv: cvv
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        showToast(response.message, "success");
                        $("#checkoutModal").modal("hide");
                    } else {
                        showToast(response.message, "danger");
                    }
                },
                error: function (xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                    showToast("Error processing payment. Check console for details.", "danger");
                }
            });
        });
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
