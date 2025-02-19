<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Course</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Checkout Course</h2>

        <!-- Course Box (Fetched from Database) -->
        <div class="card p-3 mb-3" id="course_box" style="display: none;">
            <h5 id="course_name"></h5>
            <p>Instructor: <span id="course_by"></span></p>
            <p>Price: $<span id="course_price"></span></p>
            <input type="hidden" id="course_id" value="1"> <!-- Hidden Course ID, set a default value for testing -->
        </div>

        <form id="checkoutForm">
            <!-- User's Information -->
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" id="full_name" class="form-control" placeholder="Your Full Name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" id="address" class="form-control" placeholder="Your Address" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" id="email" class="form-control" placeholder="example@example.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" id="phone_number" class="form-control" placeholder="Your Phone Number" required>
            </div>

            <!-- Payment Method -->
            <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select id="payment_method" class="form-control" required>
                    <option value="Credit Card">Credit Card</option>
                    <option value="PayPal">PayPal</option>
                </select>
            </div>

            <!-- Credit Card Details (Only if Credit Card is Selected) -->
            <div id="credit_card_section">
                <div class="mb-3">
                    <label class="form-label">Card Number</label>
                    <input type="text" id="card_number" class="form-control" placeholder="XXXX-XXXX-XXXX-XXXX" required>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Expiry Date</label>
                        <input type="text" id="expiry_date" class="form-control" placeholder="MM/YY" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">CVV</label>
                        <input type="text" id="cvv" class="form-control" placeholder="123" required>
                    </div>
                </div>
            </div>

            <button type="submit" id="confirmCheckout" class="btn btn-success mt-3">Confirm Payment</button>
        </form>
    </div>

    <script>
    $(document).ready(function () {
        function fetchCourseDetails() {
            let courseId = $("#course_id").val().trim(); // Get course ID from input field

            if (!courseId) {
                console.error("Course ID is missing.");
                return;
            }

            $.ajax({
                url: "fetch_course.php",
                method: "GET",
                data: { courseId: courseId },
                dataType: "json",
                success: function (response) {
                    if (response.success && response.courses.length > 0) {
                        let course = response.courses[0];
                        $("#course_name").text(course.CourseName);
                        $("#course_by").text(course.CourseBy);
                        $("#course_price").text(course.Price);
                        $("#course_box").show();
                    } else {
                        console.error("Error fetching course:", response.message);
                    }
                },
                error: function (xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                }
            });
        }

        // Hide/show credit card fields based on payment method
        $("#payment_method").on("change", function () {
            if ($(this).val() === "PayPal") {
                $("#credit_card_section").hide();
                $("#card_number, #expiry_date, #cvv").val(""); // Clear credit card fields
            } else {
                $("#credit_card_section").show();
            }
        });

        // Handle form submission
        $("#checkoutForm").on("submit", function (event) {
            event.preventDefault();

            // Collect form data
            let formData = {
                course_id: parseInt($("#course_id").val(), 10) || 0, // Ensure it's a number
                course_name: $("#course_name").text(),
                price: parseFloat($("#course_price").text()) || 0, // Ensure it's a valid number
                full_name: $("#full_name").val().trim(),
                address: $("#address").val().trim(),
                email: $("#email").val().trim(),
                phone_number: $("#phone_number").val().trim(),
                payment_method: $("#payment_method").val(),
                card_number: $("#card_number").val().trim(),
                expiry_date: $("#expiry_date").val().trim(),
                cvv: $("#cvv").val().trim()
            };

            // Validate required fields
            if (!formData.course_id || !formData.full_name || !formData.email || !formData.phone_number || !formData.payment_method) {
                alert("Please fill in all required fields.");
                return;
            }

            // Validate credit card fields if "Credit Card" is selected
            if (formData.payment_method === "Credit Card") {
                if (!formData.card_number || !formData.expiry_date || !formData.cvv) {
                    alert("Please enter your credit card details.");
                    return;
                }
            }

            // AJAX request to process checkout
            $.ajax({
                url: "CheckingOut.php",
                method: "POST",
                data: formData,
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        alert("Payment successful! Redirecting...");
                        window.location.href = "index.php"; // Redirect to homepage
                    } else {
                        alert("Payment failed: " + response.message);
                    }
                },
                error: function (xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                    alert("Error processing payment.");
                }
            });
        });

        // Fetch course details on page load
        fetchCourseDetails();
    });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>