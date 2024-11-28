<?php
session_start();
include("bookstore.php"); // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user input to prevent SQL injection
    $userEmail = mysqli_real_escape_string($conn, $_POST['userEmail']);
    $userPassword = trim($_POST['userPassword']); // Trim any leading/trailing spaces

    // Fetch user from the database
    $sql = "SELECT * FROM user WHERE userEmail = '$userEmail' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Compare plain text passwords directly
        if ($userPassword === $row['userPassword']) {
            // Password is correct, set session variables
            $_SESSION['userID'] = $row['userID']; // Store user ID in session
            $_SESSION['userName'] = $row['userName']; // Store user's name in session
            
            // Redirect to home.php after successful login
            header("Location: home_after.php");
            exit;
        } else {
            // Invalid password, show alert
            echo "<script>alert('Invalid email or password'); window.location.href = 'login.php';</script>";
        }
    } else {
        // User not found, show alert
        echo "<script>alert('Invalid email or password'); window.location.href = 'login.php';</script>";
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
        /* Basic Reset */
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

        /* Overlay to darken background */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
            z-index: -1;
        }

        /* Main Container for login */
        .login_box_area {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
        }

        .container {
            width: 100%;
            max-width: 600px; /* Limiting max-width for form container */
        }

        /* Login Form Styling */
        .login_form_inner {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            position: relative;
        }

        /* Close Button */
        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #FF4C4C;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
        }

        .close-btn:hover {
            background-color: #FF1A1A;
        }

        h1 {
            text-align: center;
            color: #fff;
            font-size: 36px;
            margin-bottom: 10px;
        }

        h3 {
            text-align: center;
            color: #493628;
            font-size: 24px;
            margin-bottom: 30px;
        }

        /* Input Fields Styling */
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

        /* Submit Button */
        .button-login {
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

        .button-login:hover {
            background-color: #70543c;
        }

        /* Links */
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

        /* Footer Section Styling */
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

        /* Adjust footer for small screens */
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
            <a href="home_after.php">
                <i class="fas fa-book-reader logo-icon"></i>
                <div>
                    <h1>PUSTAKA SYAFIE</h1>
                    <p>BOOKSTORE</p>
                </div>
            </a>
        </div>
        <div class="search-bar">
            <form action="search.php" method="GET">
                <input type="text" name="query" placeholder="Search for books, authors, or categories..." required />
                <button type="submit">Search</button>
            </form>
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

    <!-- Login Form Section -->
    <section class="login_box_area">
        <div class="container">
            <div class="login_form_inner">
                <h3>User Login</h3>
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="userEmail">User Email:</label>
                        <input type="email" class="form-control" id="userEmail" name="userEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="userPassword">Password:</label>
                        <input type="password" class="form-control" id="userPassword" name="userPassword" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="button-login">Log In</button>
                        <a href="signup.php">Create an Account</a>
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
