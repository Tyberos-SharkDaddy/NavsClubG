<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
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
    <h3>Login</h3>
    <form id="signupForm">
        <!-- Email -->
        <label for="email">Email Address</label>
        <input type="email" id="email" placeholder="Enter your email" required>

        <!-- Confirm Email -->
        <label for="Password">Password</label>
        <input type="email" id="Password" placeholder="Enter Password" required>
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
