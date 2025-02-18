<!DOCTYPE html>
<html l1ang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            margin-top: 20px;
        }
        table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
        }
        th {
            background: #007bff;
            color: white;
        }
        .btn-enroll {
            background: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-enroll:hover {
            background: #218838;
        }
        /* MODAL STYLES */
        .modal {
            display: none;
            position: fixed; /* Fixed position for proper placement */
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            z-index: 1000;
        }
        .modal-content {
            padding: 10px;
        }
    </style>
</head>
<body>

    <h1>Available Courses</h1>
    
    <div class="container">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Level</th>
                    <th>Duration</th>
                    <th>By</th>
                    <th>Enrolled</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="courseTable">
                <!-- Courses will be loaded here by AJAX -->
            </tbody>
        </table>
    </div>
    <script>
     $(document).ready(function() {
    // Function to fetch courses
    function fetchCourses() {
        $.ajax({
            url: "FetchEnrollcourse.php",
            method: "GET",
            dataType: "json",
            success: function(response) {
                let rows = response.map(course => `
                    <tr class="course-row" 
                        data-course="${course.CourseName}" 
                        data-about="${course.AboutCourse}" 
                        data-audience="${course.Audience}">
                        <td>${course.CourseName}</td>
                        <td>${course.CourseLevel}</td>
                        <td>${course.Duration}</td>
                        <td>${course.CourseBy}</td>
                        <td>${course.EnrolledCount}</td>
                        <td>
                            <button class="btn-AddtoCart" data-id="${course.CourseID}" data-name="${course.CourseName}">
                                Add to Cart
                            </button>
                        </td>
                    </tr>
                `).join("");

                $("#courseTable").html(rows);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching courses:", status, error);
                alert("Failed to load courses. Please try again later.");
            }
        });
    }

    // Fetch courses when document is ready
    fetchCourses();

    // Add to Cart Button Click Handler
    $(document).on("click", ".btn-AddtoCart", function() {
        let courseId = $(this).data("id");
        let courseName = $(this).data("name");

        // Log for debugging purposes
        console.log("Adding to cart:", courseId, courseName);

        // AJAX request to add the course to the cart
        $.ajax({
            url: "AddtoCartCourse.php",
            method: "POST",
            data: { courseId: courseId },
            dataType: "json", // Expecting JSON response
            success: function(response) {
                // Check the response for success
                if (response.success) {
                    alert(`${courseName} has been added to your cart!`);
                    $("#cartCount").text(response.cartCount); // Update the cart count
                    // Redirect to checkout page after a short delay
                    setTimeout(function() {
                        window.location.href = "CheckingoutCourses.php";
                    }, 1000); // 1-second delay
                } else {
                    alert("Error adding course to cart: " + (response.error || "Unknown error"));
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX errors
                console.error("AJAX Error:", status, error);
                alert("AJAX Error: " + error);
            }
        });
    });
});


    </script>

</body>
</html>