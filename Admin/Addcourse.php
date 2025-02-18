<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <style>
        /* Add your styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        button, input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        button:hover, input[type="submit"]:hover {
            background-color: #0056b3;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; 
            background-color: rgba(0, 0, 0, 0.4); 
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 70%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border: 2px solid #ddd;
        }
        th, td {
            border: 2px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .back-btn {
            display: inline-block;
            margin: 20px 0;
            text-decoration: none;
            color: #007bff;
            font-size: 18px;
            font-weight: bold;
            border: 1px solid #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        .back-btn:hover {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Add Course</h1>
    <a href="DashboardInventory.php" class="back-btn">&larr; Back</a>
    <button id="addBtn">Add Course</button>
    <table>
        <thead>
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>        <!-- Name of the course -->
                <th>About Course</th>       <!-- Brief description of the course -->
                <th>Audience</th>           <!-- Target audience -->
                <th>Course Level</th>       <!-- Level of the course (e.g., Beginner, Advanced) -->
                <th>Duration</th>           <!-- Duration of the course (e.g., 2 hours, 4 weeks) -->
                <th>Course By</th>          <!-- Instructor or organization offering the course -->
                <th>Actions</th>            <!-- Edit/Delete actions -->
            </tr>
        </thead>
        <tbody id="inventoryTable">
            <!-- Dynamic rows will be loaded here -->
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeAdd">&times;</span>
        <h2>Add New Course</h2>
        <form id="addForm" enctype="multipart/form-data">
            <!-- Course Name -->
            <label for="courseName">Course Name:</label>
            <input type="text" id="courseName" name="courseName" required><br><br>
            
            <!-- About the Course -->
            <label for="aboutCourse">About the Course:</label>
            <textarea id="aboutCourse" name="aboutCourse" rows="4" required></textarea><br><br>
            
            <!-- Audience -->
            <label for="audience">Audience:</label>
            <select id="audience" name="audience" required>
                <option value="General Public">General Public</option>
                <option value="Students">Students</option>
                <option value="Professionals">Professionals</option>
                <option value="Specialized Group">Specialized Group</option>
            </select><br><br>
            
            <!-- Course Level -->
            <label for="courseLevel">Course Level:</label>
            <select id="courseLevel" name="courseLevel" required>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Advanced">Advanced</option>
            </select><br><br>
            
            <!-- Duration -->
            <label for="duration">Duration:</label>
            <input type="text" id="duration" name="duration" required placeholder="e.g., 2 weeks, 3 hours"><br><br>
            
            <!-- Instructor (Course By) -->
            <label for="courseBy">Course By:</label>
            <input type="text" id="courseBy" name="courseBy" required><br><br>

            <!-- Price -->
            <label for="price">Price:</label>
            <input type="text" id="price" name="price" required placeholder="e.g., $100"><br><br>

            <!-- Course Image Upload -->
            <!-- <label for="courseImage">Course Image:</label>
            <input type="file" id="courseImage" name="courseImage" accept="image/*" required><br><br>
             -->
            <input type="submit" value="Add Course">
        </form>
    </div>
</div>


<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeEdit">&times;</span>
        <h2>Edit Course</h2>
        <form id="editForm" enctype="multipart/form-data">
            <input type="hidden" id="editCourseID" name="courseID">
            
            <!-- Course Name -->
            <label for="editCourseName">Course Name:</label>
            <input type="text" id="editCourseName" name="courseName" required><br><br>
            
            <!-- About the Course -->
            <label for="editAboutCourse">About the Course:</label>
            <textarea id="editAboutCourse" name="aboutCourse" rows="4" required></textarea><br><br>
            
            <!-- Audience -->
            <label for="editAudience">Audience:</label>
            <select id="editAudience" name="audience" required>
                <option value="General Public">General Public</option>
                <option value="Students">Students</option>
                <option value="Professionals">Professionals</option>
                <option value="Specialized Group">Specialized Group</option>
            </select><br><br>
            
            <!-- Course Level -->
            <label for="editCourseLevel">Course Level:</label>
            <select id="editCourseLevel" name="courseLevel" required>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Advanced">Advanced</option>
            </select><br><br>
            
            <!-- Duration -->
            <label for="editDuration">Duration:</label>
            <input type="text" id="editDuration" name="duration" required placeholder="e.g., 2 weeks, 3 hours"><br><br>
            
            <!-- Instructor (Course By) -->
            <label for="editCourseBy">Course By:</label>
            <input type="text" id="editCourseBy" name="courseBy" required><br><br>

            <!-- Price -->
            <label for="editPrice">Price:</label>
            <input type="text" id="editPrice" name="price" required placeholder="e.g., $100"><br><br>

            <input type="submit" value="Update Course">
        </form>
    </div>
</div>


 <!-- Delete Modal -->
<div id="deleteModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeDelete">&times;</span>
                <h2>Delete Part</h2>
                <p>Are you sure you want to delete this part?</p>
                <form id="deleteForm">
                    <input type="hidden" id="deletePartID" name="partID">
                    <input type="submit" value="Delete">
                </form>
            </div>
        </div>

        <!-- View Modal -->
        <div id="viewModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeView">&times;</span>
                <h2>View Part</h2>
                <div id="viewDetails">
                    <!-- Part details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Open Add Modal
            document.getElementById('addBtn').onclick = function() {
                document.getElementById('addModal').style.display = 'block';
            }

            // Close Modals
            document.getElementById('closeAdd').onclick = function() {
                document.getElementById('addModal').style.display = 'none';
            }
            document.getElementById('closeEdit').onclick = function() {
                document.getElementById('editModal').style.display = 'none';
            }
            document.getElementById('closeDelete').onclick = function() {
                document.getElementById('deleteModal').style.display = 'none';
            }
            document.getElementById('closeView').onclick = function() {
                document.getElementById('viewModal').style.display = 'none';
            }

           // Fetch courses and populate table
function loadInventory() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'Courses.php?action=list', true);
    xhr.onload = function() {
        if (this.status == 200) {
            document.getElementById('inventoryTable').innerHTML = this.responseText;
        }
    }
    xhr.send();
}
loadInventory();

// Add Course
document.getElementById('addForm').onsubmit = function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'Courses.php?action=add', true);
    xhr.onload = function() {
        if (this.status == 200) {
            loadInventory();
            document.getElementById('addModal').style.display = 'none';
        }
    }
    xhr.send(formData);
}

// Edit Course
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('edit-btn')) {
        const CourseID = e.target.getAttribute('data-id');
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `Courses.php?action=view&id=${CourseID}`, true);
        xhr.onload = function() {
            if (this.status == 200) {
                const data = JSON.parse(this.responseText);
                if (data.error) {
                    alert(data.error); // Display error if course is not found
                } else {
                    document.getElementById('editCourseID').value = data.CourseID;
                    document.getElementById('editCourseName').value = data.CourseName;
                    document.getElementById('editAboutCourse').value = data.AboutCourse;
                    document.getElementById('editAudience').value = data.Audience;
                    document.getElementById('editCourseLevel').value = data.CourseLevel;
                    document.getElementById('editDuration').value = data.Duration;
                    document.getElementById('editCourseBy').value = data.CourseBy;
                    document.getElementById('editPrice').value = data.Price;  // Set Price field
                    document.getElementById('editModal').style.display = 'block';
                }
            }
        };
        xhr.send();
    }
});


// Handle Edit Form Submission
document.getElementById('editForm').onsubmit = function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'Courses.php?action=edit', true);
    xhr.onload = function() {
        if (this.status == 200) {
            if (this.responseText.trim() === "success") {
                alert("Course successfully updated!"); // Show success message
                document.getElementById('editModal').style.display = 'none';
                loadInventory(); // Refresh the course list
            } else {
                alert("Error: " + this.responseText); // Show error message
            }
        }
    };
    xhr.send(formData);
};

// Delete Course
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('delete-btn')) {
        const courseID = e.target.getAttribute('data-id');

        if (confirm("Are you sure you want to delete this course?")) {
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('courseID', courseID);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'Courses.php', true);
            xhr.onload = function() {
                if (this.status == 200) {
                    if (this.responseText.trim() === "success") {
                        alert("Course deleted successfully!");
                        loadInventory(); // Refresh the list
                    } else {
                        alert("Error: " + this.responseText);
                    }
                }
            };
            xhr.send(formData);
        }
    }
});


// View Course
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('view-btn')) {
        const courseID = e.target.getAttribute('data-id');
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `Courses.php?action=view&id=${courseID}`, true);
        xhr.onload = function() {
            if (this.status == 200) {
                try {
                    const data = JSON.parse(this.responseText);
                    // Display course details in the view modal
                    document.getElementById('viewDetails').innerHTML = `
                        <p><strong>Course ID:</strong> ${data.CourseID || 'N/A'}</p>
                        <p><strong>Course Name:</strong> ${data.CourseName || 'N/A'}</p>
                        <p><strong>About the Course:</strong> ${data.AboutCourse || 'N/A'}</p>
                        <p><strong>Audience:</strong> ${data.Audience || 'N/A'}</p>
                        <p><strong>Course Level:</strong> ${data.CourseLevel || 'N/A'}</p>
                        <p><strong>Duration:</strong> ${data.Duration || 'N/A'}</p>
                        <p><strong>Course By:</strong> ${data.CourseBy || 'N/A'}</p>
                        <p><strong>Price:</strong> ${data.Price || 'N/A'}</p> <!-- Added Price -->
                    `;
                    document.getElementById('viewModal').style.display = 'block';
                } catch (error) {
                    console.error("Error parsing JSON data:", error);
                }
            } else {
                console.error('Error loading course data:', this.statusText);
            }
        }
        xhr.onerror = function() {
            console.error('Request failed');
        };
        xhr.send();
    }
});

        });
    </script>
</body>
</html>