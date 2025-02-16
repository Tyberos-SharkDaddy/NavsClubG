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

    <!-- MODAL -->
    <!-- <div class="modal" id="hoverModal">
        <div class="modal-content">
            <h3 id="modalTitle"></h3>
            <p><strong>About Course:</strong> <span id="modalAbout"></span></p>
            <p><strong>Audience:</strong> <span id="modalAudience"></span></p>
        </div>
    </div> -->

    <script>
       $(document).ready(function() {
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
                                    <button class="btn-AddtoCart" data-id="${course.CourseID}">Add to Cart</button>
                                </td>
                            </tr>
                        `).join("");

                        $("#courseTable").html(rows);
                    }
                });
            }

            fetchCourses();

            // Enroll function (redirects to shopping cart)
            $(document).on("click", ".btn-enroll", function() {
                let courseId = $(this).data("id");
                window.location.href = `cart.php?course_id=${courseId}`;
            });

            // // Hover modal functionality
            // let hideTimeout;

            // $(document).on("mouseenter", ".course-row", function() {
            //     clearTimeout(hideTimeout);

            //     let $this = $(this);
            //     $("#modalTitle").text($this.data("course"));
            //     $("#modalAbout").text($this.data("about"));
            //     $("#modalAudience").text($this.data("audience"));

            //     let rect = this.getBoundingClientRect();
            //     let modalWidth = $("#hoverModal").outerWidth();
            //     let leftPos = Math.max(10, rect.left + rect.width / 2 - modalWidth / 2);
            //     let topPos = rect.top - $("#hoverModal").outerHeight() - 10;

            //     // Ensure modal doesn't go off-screen
            //     leftPos = Math.min(window.innerWidth - modalWidth - 10, leftPos);
            //     topPos = Math.max(10, topPos);

            //     $("#hoverModal").css({
            //         left: `${leftPos}px`,
            //         top: `${topPos}px`,
            //         display: "block"
            //     });
            // });

            // $(document).on("mouseleave", ".course-row", function() {
            //     hideTimeout = setTimeout(() => {
            //         $("#hoverModal").fadeOut(200);
            //     }, 200);
            // });

            // $("#hoverModal").on("mouseenter", function() {
            //     clearTimeout(hideTimeout);
            // }).on("mouseleave", function() {
            //     hideTimeout = setTimeout(() => {
            //         $("#hoverModal").fadeOut(200);
            //     }, 200);
            // });
        });
    </script>

</body>
</html>