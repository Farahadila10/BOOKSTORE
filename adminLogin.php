<?php
session_start();
include("bookstore.php"); // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminEmail = mysqli_real_escape_string($conn, $_POST['adminEmail']);
    $adminPassword = mysqli_real_escape_string($conn, $_POST['adminPassword']);

    // Fetch user from database
    $sql = "SELECT * FROM admin WHERE adminEmail = '$adminEmail' AND adminPassword = '$adminPassword'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['adminID'] = $row['adminID']; // Store user ID in session
        $_SESSION['adminName'] = $row['adminName']; // Store user's name in session
        
        // Redirect to adminPage.php
        header("Location: adminPage.php");
        exit;
    } else {
        // Invalid login, redirect back to login.php
        echo "<script>alert('Invalid email or password'); window.location.href = 'adminLogin.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('images/wall.jpg') no-repeat center center fixed;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .login_box_area {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login_form_inner {
            background: #FAF7F0;
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            position: relative; /* To position the close button relative to this container */
        }

        .login_form_inner h3 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .button-login {
            padding: 10px;
            background: #705C53;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            width: 100%;
            font-size: 16px;
        }

        .button-login:hover {
            background: #0056b3;
        }

        .col-md-12 a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #007BFF;
            text-decoration: none;
        }

        .col-md-12 a:hover {
            text-decoration: underline;
        }

        .success {
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        /* Close button styling */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #FF4C4C;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 50%;
            font-size: 16px;
            cursor: pointer;
        }

        .close-btn:hover {
            background: #ff1a1a;
        }
    </style>
</head>
<body>

    <section class="login_box_area">
        <div class="container">
            <div class="login_form_inner">
                <!-- Close Button -->
                <button class="close-btn" onclick="window.location.href='home.php'">&times;</button>
                <h3>PUSTAKA SYAFIE BOOKSTORE</h3>
                <h3>Admin Login</h3>
                <form action="adminLogin.php" method="post">
                    <div class="form-group">
                        <label for="adminEmail">Admin Email:</label>
                        <input type="text" class="form-control" id="adminEmail" name="adminEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="adminPassword">Password:</label>
                        <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="button-login">Log In</button>
                        <a href="adminRegister.php">Create an Account</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

</body>
</html>
