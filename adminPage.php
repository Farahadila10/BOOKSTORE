<?php
session_start(); // Start the session

// Include database connection
include("bookstore.php");

// Check if admin is logged in, otherwise redirect to login page
if (!isset($_SESSION['adminName'])) {
    header("Location: adminlogin.php"); // Redirect to login if not logged in
    exit();
}

// Fetch the admin name from the session
$adminName = $_SESSION['adminName'];
?>

<?php
// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy all session data
    header("Location: adminlogin.php"); // Redirect to login page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore Admin Page</title>
    <style>
        /* General styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 800px;
            background-color: #FAF7F0;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .container h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
        }

        .container h1 {
            font-size: 24px;
            margin-bottom: 40px;
            color: #333;
        }

        .button-group {
            margin-bottom: 30px;
        }

        .button {
            display: inline-block;
            background-color: #705C53;
            color: white;
            padding: 14px 30px;
            margin: 10px;
            text-decoration: none;
            font-size: 18px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #45a049;
        }

        .logout-button {
            display: inline-block;
            background-color: #f44336;
            color: white;
            padding: 14px 30px;
            text-decoration: none;
            font-size: 18px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
            margin-top: 30px;
        }

        .logout-button:hover {
            background-color: #e53935;
        }

        .home-button {
            display: inline-block;
            background-color: #2196F3;
            color: white;
            padding: 14px 30px;
            text-decoration: none;
            font-size: 18px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        .home-button:hover {
            background-color: #1976D2;
        }

        /* Logo container */
        .logo {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            margin-bottom: 20px;
        }

        .logo a {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .logo-icon {
            font-size: 40px; /* Adjust logo icon size */
            color: #705C53;
            margin-right: 15px;
        }

        .logo-text h1 {
            font-size: 2.5rem;
            color: #705C53;
            margin: 0;
        }

        .logo-text p {
            font-size: 1.2rem;
            color: #333;
            margin-top: 5px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .button-group {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .button,
            .logout-button,
            .home-button {
                width: 100%;
                margin: 10px 0;
            }

            .logo-text h1 {
                font-size: 2rem;
            }

            .logo-text p {
                font-size: 1rem;
            }

            .logo-icon {
                font-size: 30px;
            }
        }
    </style>

    <script>
        // Confirm logout before proceeding
        function confirmLogout() {
            var confirmation = confirm("Are you sure you want to log out?");
            if (confirmation) {
                window.location.href = "?logout=true"; // Redirect to logout URL if confirmed
            }
        }
    </script>
</head>

<body>

    <div class="container">
        <header>
            <div class="logo">
                <a href="home.php" class="logo-link">
                    <div class="logo-icon">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div class="logo-text">
                        <h1>PUSTAKA SYAFIE</h1>
                        <p>BOOKSTORE</p>
                    </div>
                </a>
            </div>
        </header>

        <!-- Display the admin's name -->
        <h2>Welcome, <?php echo htmlspecialchars($adminName); ?></h2>

        <h1>Bookstore Admin Dashboard</h1>

        <!-- Button group -->
        <div class="button-group">
            <a href="adminAdd.php" class="button">Add New Book</a>
            <a href="adminRecord.php" class="button">Feedback Record</a>
            <a href="orderRecord.php" class="button">Order Record</a>
            <a href="userRecord.php" class="button">User Record</a>

        </div>

        <!-- Logout button with confirmation -->
        <a href="javascript:void(0);" onclick="confirmLogout()" class="logout-button">Logout</a>

    </div>

</body>

</html>
