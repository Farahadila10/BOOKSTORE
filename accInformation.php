<?php
session_start(); // Start the session

// Include database connection
include("bookstore.php");

// Check if user is logged in, otherwise redirect to login page
if (!isset($_SESSION['userName'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch the logged-in user's name from the session
$userName = $_SESSION['userName'];

// Fetch current user information from the database
$stmt = $conn->prepare("SELECT * FROM user WHERE userName = ?");
$stmt->bind_param("s", $userName); // "s" stands for string
$stmt->execute();
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc();
$userId = $user['userID']; // Assuming `user_id` is the primary key for the user table

// Fetch order history for the logged-in user, including cart items
$orderStmt = $conn->prepare("SELECT o.orderID, o.orderDate, o.total_amount, o.status, 
                            sc.bookTitle, sc.bookPrice, sc.bookQuantity, sc.totalPrice 
                            FROM orders o
                            JOIN shoppingcart sc ON o.userID = sc.userID
                            WHERE o.userID = ? ORDER BY o.orderDate DESC");
$orderStmt->bind_param("i", $userId);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();

// Handle order deletion
if (isset($_GET['deleteOrder'])) {
    $orderID = $_GET['deleteOrder'];
    
    // Delete the order from the orders table
    $deleteStmt = $conn->prepare("DELETE FROM orders WHERE orderID = ? AND userID = ?");
    $deleteStmt->bind_param("ii", $orderID, $userId);
    
    if ($deleteStmt->execute()) {
        echo "<script>alert('Order deleted successfully.');</script>";
        header("Location: update_info.php"); // Redirect to the same page to refresh the order history
        exit();
    } else {
        echo "<script>alert('Error deleting order.');</script>";
    }
}

// Update user information in the database
if (isset($_POST['updateProfile'])) {
    $newUserName = $_POST['userName'];
    $newEmail = $_POST['email'];
    $newPhoneNo = $_POST['phone_no'];
    $newAddress = $_POST['address'];

    // Update the user information
    $updateQuery = "UPDATE user SET userName = ?, userEmail = ?, userPhoneNo = ?, userAddress = ? WHERE userName = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssss", $newUserName, $newEmail, $newPhoneNo, $newAddress, $userName);

    if ($stmt->execute()) {
        $_SESSION['userName'] = $newUserName; // Update the session with the new username
        echo "<script>alert('Profile updated successfully.');</script>";
    } else {
        echo "<script>alert('Error updating profile.');</script>";
    }
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy all session data
    header("Location: login.php"); // Redirect to login page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Info</title>
    <style>
        /* Reset and box-sizing */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-image: url('images/wall.jpg');
            background-size: cover;
            background-position: center;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
            position: relative;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: -1;
        }

        .container {
            width: 100%;
            max-width: 900px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: all 0.3s ease;
        }

        .container h1 {
            font-size: 32px;
            margin-bottom: 30px;
            color: #333;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
            width: 100%;
        }

        .form-group label {
            font-size: 16px;
            color: #555;
            display: block;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 12px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .button-group {
            margin-bottom: 20px;
        }

        .button {
            background-color: #4CAF50;
            color: white;
            padding: 14px 32px;
            font-size: 18px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .button:hover {
            background-color: #45a049;
        }

        .logout-button {
            background-color: #f44336;
        }

        .logout-button:hover {
            background-color: #e53935;
        }

        .home-button {
            background-color: #2196F3;
        }

        .home-button:hover {
            background-color: #1976D2;
        }

        .order-history {
            margin-top: 30px;
            width: 100%;
            overflow-x: auto;
        }

        .order-history table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .order-history th,
        .order-history td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .order-history th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .order-history tr:hover {
            background-color: #f1f1f1;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .delete-btn:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <h1>PUSTAKA SYAFIE</h1>
        <h2>Welcome, <?php echo htmlspecialchars($userName); ?></h2>

        <h1>Update Your Profile</h1>
        <form method="POST">
            <div class="form-group">
                <label for="userName">Username</label>
                <input type="text" id="userName" name="userName" value="<?php echo htmlspecialchars($user['userName']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['userEmail']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_no">Phone Number</label>
                <input type="text" id="phone_no" name="phone_no" value="<?php echo htmlspecialchars($user['userPhoneNo']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['userAddress']); ?>" required>
            </div>

            <div class="button-group">
                <button type="submit" class="button" name="updateProfile">Update Profile</button>
            </div>
        </form>

        <h1>Order History</h1>
        <div class="order-history">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Book Title</th>
                        <th>Book Price (RM)</th>
                        <th>Book Quantity</th>
                        <th>Total Price (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $orderResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['orderID']); ?></td>
                            <td><?php echo htmlspecialchars($order['orderDate']); ?></td>
                            <td><?php echo htmlspecialchars($order['bookTitle']); ?></td>
                            <td><?php echo number_format($order['bookPrice'], 2); ?></td>
                            <td><?php echo htmlspecialchars($order['bookQuantity']); ?></td>
                            <td><?php echo number_format($order['totalPrice'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="button-group">
            <a href="?logout=true" class="button logout-button">Logout</a>
            <a href="home_after.php" class="button home-button">Go to Home</a>
        </div>
    </div>
</body>
</html>
