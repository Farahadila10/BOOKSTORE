<?php
session_start();
include('bookstore.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind parameters to prevent SQL Injection
    $stmt = $conn->prepare("INSERT INTO contact (contactName, contactEmail, contactPhone, comments) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $comment);

    // Assign variables from POST request, sanitized
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $comment = htmlspecialchars($_POST['comment']);

    // Execute the prepared statement and set feedback message
    if ($stmt->execute()) {
        $_SESSION['message'] = "Your message has been submitted successfully!";
    } else {
        $_SESSION['message'] = "Failed to submit your message. Please try again later.";
    }

    // Close connections
    $stmt->close();
    $conn->close();

    // Redirect to avoid form resubmission
    header("Location: contact.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Pustaka Syafie Bookstore</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        h1, h2, p {
            text-align: center;
            margin: 20px 0;
        }

        .contact-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .contact-container label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        .contact-container input, 
        .contact-container textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .contact-container button {
            background-color: #705C53;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .contact-container button:hover {
            background-color: #5a4a43;
        }

        footer {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }

        footer a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }

        footer a:hover {
            text-decoration: underline;
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
        <input type="text" placeholder="Search for books, authors, or categories..." />
        <button>Search</button>
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

    <!-- Main Content -->
    <h1>Contact Us</h1>
    <h2>Working Hours :</h2>
    <p>Monday to Saturday 9:00am to 6:00pm. (Lunch break: 1:00pm to 2:00pm) Closed on Sunday & Public Holidays</p>
    <h2>Call Us :</h2>
    <p>Monday to Friday: +603-8961 0048 & +603-8961 0131 & +603-8964 1618</p>
    <p>Saturday: +6012-700 7511</p>
    <div class="contact-container">
        <!-- Feedback Message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Contact Form -->
        <form action="contact.php" method="post">
            <label for="name">Name <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" placeholder="Your Full Name" required>

            <label for="email">Email <span class="text-danger">*</span></label>
            <input type="email" id="email" name="email" placeholder="Your Email Address" required>

            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" placeholder="Your Phone Number">

            <label for="comment">Comment <span class="text-danger">*</span></label>
            <textarea id="comment" name="comment" rows="4" placeholder="Your Message" required></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>

    <!-- Footer Section -->
    <footer>
        <ul>
            <li><a href="faq.html">FAQs</a></li>
            <li><a href="about.html">About Us</a></li>
            <li><a href="terms.html">Terms & Conditions</a></li>
            <li><a href="userManual.html">User Manual</a></li>
        </ul>
        <p>&copy; 2024 Pustaka Syafie Bookstore. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
