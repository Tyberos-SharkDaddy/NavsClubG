<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Form</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            padding: 20px;
            width: 100%;
            max-width: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h3 {
            text-align: center;
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input:focus, select:focus {
            border-color: #007bff;
            outline: none;
        }

        .row {
            display: flex;
            gap: 10px;
        }

        .row input {
            flex: 1;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .checkbox-container input {
            width: auto;
            margin-right: 10px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
        }

        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h3>Sign Up</h3>
    <form id="signupForm">
        <!-- Email -->
        <label for="email">Email Address</label>
        <input type="email" id="email" placeholder="Enter your email" required>

        <!-- Confirm Email -->
        <label for="confirm_email">Confirm Email</label>
        <input type="email" id="confirm_email" placeholder="Confirm your email" required>

        <!-- First & Last Name -->
        <div class="row">
            <div>
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" placeholder="First Name" required>
            </div>
            <div>
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" placeholder="Last Name" required>
            </div>
        </div>

        <!-- Nickname -->
        <label for="nickname">Nickname</label>
        <input type="text" id="nickname" placeholder="Enter your nickname">

        <!-- Current Rank -->
        <label for="rank">What is your current rank?</label>
        <select id="rank" required>
            <option value="" disabled selected>Select your rank</option>
            <option value="Beginner">Beginner</option>
            <option value="Intermediate">Intermediate</option>
            <option value="Advanced">Advanced</option>
            <option value="Expert">Expert</option>
        </select>

        <!-- Status -->
        <div class="checkbox-container">
            <input type="checkbox" id="status" required>
            <label for="status">✅ I confirm that my information is correct</label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn">Sign Up</button>
    </form>
</div>

<script>
    document.getElementById("signupForm").addEventListener("submit", function(event) {
        const email = document.getElementById("email").value;
        const confirmEmail = document.getElementById("confirm_email").value;

        if (email !== confirmEmail) {
            alert("Emails do not match!");
            event.preventDefault();
        }
    });
</script>

</body>
</html>
