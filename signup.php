<?php
session_start();
include("bookstore.php"); // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userEmail = mysqli_real_escape_string($conn, $_POST['userEmail']);
    $userPassword = mysqli_real_escape_string($conn, $_POST['userPassword']);
    $userName = mysqli_real_escape_string($conn, $_POST['userName']);
    $userConfirmPassword = mysqli_real_escape_string($conn, $_POST['userConfirmPassword']);

    // Check if passwords match
    if ($userPassword !== $userConfirmPassword) {
        echo "<script>alert('Passwords do not match. Please try again.'); window.location.href = 'signup.php';</script>";
        exit;
    }

    // Insert user into database with plain text password
    $sql = "INSERT INTO user (userEmail, userPassword, userName) VALUES ('$userEmail', '$userPassword', '$userName')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Registration successful! You can now log in.'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href = 'signup.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUSTAKA SYAFIE BOOKSTORE</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: url('background-image.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #493628;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7); /* White overlay */
            z-index: -1;
        }

        .signup_box_area {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
        }

        .container {
            width: 100%;
            max-width: 600px;
        }

        .signup_form_inner {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            position: relative;
        }

        h3 {
            text-align: center;
            color: #493628;
            font-size: 28px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            color: #493628;
            display: block;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 18px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 10px;
            transition: border 0.3s ease;
        }

        .form-control:focus {
            border-color: #493628;
            outline: none;
        }

        .button-register {
            width: 100%;
            padding: 15px;
            background-color: #493628;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button-register:hover {
            background-color: #70543c;
        }

        .col-md-12 a {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
            color: #007BFF;
            text-decoration: none;
        }

        .col-md-12 a:hover {
            text-decoration: underline;
        }

        footer {
            background-color: #333;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        footer ul {
            list-style-type: none;
            margin-bottom: 15px;
        }

        footer ul li {
            display: inline-block;
            margin-right: 20px;
        }

        footer a {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        footer a:hover {
            text-decoration: underline;
        }

        footer .social-media {
            margin-bottom: 15px;
        }

        footer .social-media a {
            margin: 0 10px;
            color: white;
            font-size: 20px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo">
            <a href="home.php">
                <i class="fas fa-book-reader logo-icon"></i>
                <div>
                    <h1>PUSTAKA SYAFIE</h1>
                    <p>BOOKSTORE</p>
                </div>
            </a>
        </div>
        <div class="header-icons">
            <a href="login.php" class="icon">
                <i class="fas fa-user"></i>
                <span>Login</span>
            </a>
            <a href="cart.php" class="icon">
                <i class="fas fa-shopping-cart"></i>
                <span>Cart</span>
            </a>
            <a href="wishlist.php" class="icon">
                <i class="fas fa-heart"></i>
                <span>Wishlist</span>
            </a>
            <a href="accInformation.php" class="icon">
                <i class="fas fa-cogs"></i>
                <span>My Account</span>
            </a>
            <a href="contact.php" class="icon">
                <i class="fas fa-envelope"></i>
                <span>Contact</span>
            </a>
        </div>
    </header>

    <section class="signup_box_area">
        <div class="container">
            <div class="signup_form_inner">
                <h3>Create Account</h3>
                <form action="signup.php" method="post">
                    <div class="form-group">
                        <label for="userName">Name:</label>
                        <input type="text" class="form-control" id="userName" name="userName" required>
                    </div>
                    <div class="form-group">
                        <label for="userEmail">Email:</label>
                        <input type="email" class="form-control" id="userEmail" name="userEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="userPassword">Password:</label>
                        <input type="password" class="form-control" id="userPassword" name="userPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="userConfirmPassword">Confirm Password:</label>
                        <input type="password" class="form-control" id="userConfirmPassword" name="userConfirmPassword" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="button-register">Sign Up</button>
                        <a href="login.php">Already have an account? Log in</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <div class="footer-content">
            <ul>
                <li><a href="faq.html">FAQs</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="terms.html">Terms & Condition</a></li>
                <li><a href="userManual.html">User Manual</a></li>
            </ul>
            <div class="social-media">
                <a href="#"> | </a> 
            </div>
            <div class="col-md-6">
                <p>Copyright © 2024 PUSTAKA SYAFIE BOOK CO. (MALAYSIA) SDN. BHD. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
