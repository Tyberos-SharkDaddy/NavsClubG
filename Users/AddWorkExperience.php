<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Experience Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h3>Add Work Experience</h3>
    <form id="workExperienceForm">
        <div id="workExperienceContainer">
            <div class="row mb-2 work-experience">
                <div class="col-md-2">
                    <input type="text" name="rank[]" class="form-control" placeholder="Rank" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="company[]" class="form-control" placeholder="Company" required>
                </div>
                <div class="col-md-2">
                    <input type="date" name="work_date[]" class="form-control" required>
                </div>
                <div class="col-md-1">
                    <input type="text" name="work_day[]" class="form-control" placeholder="Day" required>
                </div>

                <div class="col-md-1">
                    <input type="number" name="work_year[]" class="form-control" placeholder="Year" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-field">X</button>
                </div>
            </div>
        </div>
        <button type="button" id="addWorkExperience" class="btn btn-primary">+ Add More</button>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
    <div id="responseMessage" class="mt-3"></div>
</div>

<script>
$(document).ready(function () {
    // Add new input row
    $("#addWorkExperience").click(function () {
        $("#workExperienceContainer").append(`
            <div class="row mb-2 work-experience">
                <div class="col-md-2">
                    <input type="text" name="rank[]" class="form-control" placeholder="Rank" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="company[]" class="form-control" placeholder="Company" required>
                </div>
                <div class="col-md-2">
                    <input type="date" name="work_date[]" class="form-control" required>
                </div>
                <div class="col-md-1">
                    <input type="text" name="work_day[]" class="form-control" placeholder="Day" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="work_month[]" class="form-control" placeholder="Month" required>
                </div>
                <div class="col-md-1">
                    <input type="number" name="work_year[]" class="form-control" placeholder="Year" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-field">X</button>
                </div>
            </div>
        `);
    });

    // Remove an input row
    $(document).on("click", ".remove-field", function () {
        $(this).closest(".work-experience").remove();
    });

    // Submit form via AJAX
    $("#workExperienceForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: "save_work_experience.php",
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                $("#responseMessage").html(response);
                $("#workExperienceForm")[0].reset(); // Reset form after submission
            },
            error: function () {
                $("#responseMessage").html("<div class='alert alert-danger'>Error saving data.</div>");
            }
        });
    });
});
</script>

</body>
</html>