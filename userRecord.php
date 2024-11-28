<?php
// Start the session
session_start();

// Include database connection
include("bookstore.php");

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['userName'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Handle delete action
if (isset($_GET['delete'])) {
    $userID = $_GET['delete'];

    // Prepare and execute delete query
    $sqlDelete = "DELETE FROM user WHERE userID = ?";
    $stmt = $conn->prepare($sqlDelete);
    $stmt->bind_param("i", $userID);

    if ($stmt->execute()) {
        // Redirect back to the page after successful deletion
        header("Location: userRecord.php"); 
        exit();
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}

// Fetch all user records from the database
$sql = "SELECT userID, userName, userEmail, userPassword, userPhoneNo, userAddress 
        FROM user 
        ORDER BY userPhoneNo ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Records</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <style>
        /* Global styling */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            color: #333;
        }

        header {
            background-color: #3b9ae1;
            color: white;
            text-align: center;
            padding: 30px;
        }

        h1 {
            font-size: 2.5rem;
            margin: 0;
        }

        /* Main container styling */
        .container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 40px;
            margin: 0 auto;
            max-width: 1200px;
        }

        .user-card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .user-card h2 {
            margin: 0;
            font-size: 1.5rem;
            color: #3b9ae1;
        }

        .user-info {
            margin-top: 10px;
        }

        .user-info p {
            margin: 5px 0;
            font-size: 1rem;
            line-height: 1.5;
        }

        .delete-button {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            font-size: 14px;
            color: white;
            background-color: #e74c3c;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }

        .back-button {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 24px;
            font-size: 16px;
            color: white;
            background-color: #3b9ae1;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #2a7bbd;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .back-button {
                width: 100%;
                text-align: center;
            }

            .delete-button {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <header>
        <h1>User Records</h1>
    </header>

    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            // Output data of each user in card format
            while($row = $result->fetch_assoc()) {
                echo "<div class='user-card'>";
                echo "<h2>" . htmlspecialchars($row['userName']) . "</h2>";
                echo "<div class='user-info'>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($row['userEmail']) . "</p>";
                echo "<p><strong>Password:</strong> " . htmlspecialchars($row['userPassword']) . "</p>";
                echo "<p><strong>Phone No:</strong> " . htmlspecialchars($row['userPhoneNo']) . "</p>";
                echo "<p><strong>Address:</strong> " . htmlspecialchars($row['userAddress']) . "</p>";
                echo "</div>";

                // Delete button with userID passed as a GET parameter
                echo "<a href='?delete=" . $row['userID'] . "' class='delete-button' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No records found.</p>";
        }
        ?>
    </div>

    <!-- Back Button -->
    <a href="adminPage.php" class="back-button">Back</a>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
