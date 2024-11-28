<?php
// Include the database connection
include('bookstore.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $adminName = $_POST['adminName'];
    $adminEmail = $_POST['adminEmail'];
    $adminPassword = $_POST['adminPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if password and confirm password match
    if ($adminPassword === $confirmPassword) {
        // Prepare SQL statement to insert new admin data without hashing the password
        $stmt = $conn->prepare("INSERT INTO admin (adminName, adminEmail, adminPassword) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $adminName, $adminEmail, $adminPassword);

        // Check if the insertion is successful
        if ($stmt->execute()) {
            // Redirect to admin login page after successful registration
            header("Location: adminLogin.php");
            exit();
        } else {
            echo "<div class='error'>Error: " . $stmt->error . "</div>";
        }

        // Close the statement and connection
        $stmt->close();
    } else {
        echo "<div class='error'>Passwords do not match. Please try again.</div>";
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('images/wall.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Transparent black overlay */
            z-index: -1; /* Behind all content */
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .register_box_area {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .register_form_inner {
            background: rgba(250, 247, 240, 0.9); /* Semi-transparent background */
            padding: 40px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* Slightly deeper shadow */
            border-radius: 15px;
            width: 100%;
            max-width: 500px; /* Increased width */
            position: relative;
        }

        .register_form_inner h3 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 28px;
            font-weight: bold;
            color: #493628;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #493628;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .button-register {
            padding: 15px;
            background: #705C53;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            width: 100%;
            font-size: 18px;
        }

        .button-register:hover {
            background: #0056b3;
        }

        .col-md-12 a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007BFF;
            text-decoration: none;
            font-size: 16px;
        }

        .col-md-12 a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        /* Close button styling */
        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #FF4C4C;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
        }

        .close-btn:hover {
            background: #ff1a1a;
        }
    </style>
</head>
<body>

    <div class="overlay"></div> <!-- Transparent overlay -->

    <section class="register_box_area">
        <div class="container">
            <div class="register_form_inner">
                <!-- Close Button -->
                <button class="close-btn" onclick="window.location.href='home.php'">&times;</button>
                <h3>PUSTAKA SYAFIE BOOKSTORE</h3>
                <h3>Admin Register</h3>
                <form action="adminRegister.php" method="post">
                    <div class="form-group">
                        <label for="adminName">Admin Name:</label>
                        <input type="text" class="form-control" id="adminName" name="adminName" required>
                    </div>
                    <div class="form-group">
                        <label for="adminEmail">Admin Email:</label>
                        <input type="text" class="form-control" id="adminEmail" name="adminEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="adminPassword">Password:</label>
                        <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password:</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="button-register">Register</button>
                        <a href="adminLogin.php">Already have an account? Login</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

</body>
</html>
