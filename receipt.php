<?php
session_start();
include 'bookstore.php';  // Include database connection

// Initialize variables
$totalAmount = 0;
$userID = $_SESSION['userID'];  // Assuming userID is stored in session

// Retrieve user and order details from the session
$name = $_SESSION['name'] ?? 'Not Provided';
$email = $_SESSION['email'] ?? 'Not Provided';
$address = $_SESSION['address'] ?? 'Not Provided';
$phone = $_SESSION['phone'] ?? 'Not Provided';
$paymentMethod = $_SESSION['payment_method'] ?? 'Not Provided';
$userName = $_SESSION['username'] ?? 'Not Provided';
$orderDate = date("Y-m-d H:i:s"); // Generate the order date

// Query to get cart details from the shoppingcart table for the current user
$cartQuery = "SELECT bookID, bookTitle, bookPrice, bookQuantity FROM shoppingcart WHERE userID = $userID";
$cartResult = $conn->query($cartQuery);

// Check if cart is empty
if (!$cartResult || $cartResult->num_rows == 0) {
    echo "Your cart is empty. <a href='shop.php'>Go back to shopping</a>";
    exit;
}

// Retrieve user information from the database
$userQuery = "SELECT userName, userEmail, userPhoneNo, userAddress FROM user WHERE userID = $userID";
$userResult = $conn->query($userQuery);

// Check if user exists in the database
if ($userResult && $userResult->num_rows > 0) {
    $user = $userResult->fetch_assoc();
} else {
    echo "User information not found.";
    exit;
}

// Insert order into the orders database
if (isset($_POST['backToHome'])) {
    // Loop through cart items to calculate total and prepare data
    while ($row = $cartResult->fetch_assoc()) {
        $bookID = $row['bookID'];
        $bookTitle = $row['bookTitle'];
        $bookPrice = $row['bookPrice'];
        $bookQuantity = $row['bookQuantity'];
        $totalPrice = $bookPrice * $bookQuantity;
        
        // Add to the total amount for the order
        $totalAmount += $totalPrice;
    }

    // Insert the order data into the orders table
    $insertOrderQuery = "INSERT INTO orders (userID, userName, userEmail, userPhoneNo, userAddress, 
                           bookQuantity, bookTitle, bookPrice, total_amount, paymentMethod, orderDate, status, bookID) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare the statement
    $stmt = $conn->prepare($insertOrderQuery);
    
    if ($stmt === false) {
        echo "Error preparing the query: " . $conn->error;
        exit;
    }

    // Bind parameters for the orders table
    $status = 'Pending';  // Initial status
    $stmt->bind_param("issssssdsdssi", $userID, $userName, $email, $phone, $address, 
                      $bookQuantity, $bookTitle, $bookPrice, $totalAmount, $paymentMethod, 
                      $orderDate, $status, $bookID);

    // Execute the statement
    if ($stmt->execute()) {
        $orderID = $stmt->insert_id;  // Get the last inserted order ID
        echo "Order has been placed successfully!";
        
        $_SESSION['orderID'] = $orderID; // Optionally save the orderID to session
        header("Location: home.php");  // Redirect to home page
        exit();
    } else {
        echo "Error executing query: " . $stmt->error;
    }
    
    // Close the statement
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Pustaka Syafie Online Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f7f4; /* Light Beige Background */
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1, h3 {
            color: #2c3e50;
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #f2f2f2;
            color: #555;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 20px;
            display: inline-block;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 20px;
            display: inline-block;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-header h3 {
            font-size: 22px;
            color: #007bff;
        }

        .receipt-header p {
            font-size: 14px;
            color: #555;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #555;
        }

        .receipt-footer p {
            margin: 0;
        }

        .user-info {
            margin-top: 20px;
            font-size: 16px;
        }

        .user-info p {
            margin: 5px 0;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .btn-primary, .btn-secondary {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1 class="text-center my-4">Receipt</h1>

        <div class="receipt-header">
            <h3>Pustaka Syafie Online Bookstore</h3>
            <p>Your trusted source for Islamic literature</p>
        </div>

        <h3 class="mb-3">Order Summary</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Quantity</th>
                    <th>Price (RM)</th>
                    <th>Total (RM)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Calculate total amount and display cart items
                while ($row = $cartResult->fetch_assoc()) {
                    $bookTitle = $row['bookTitle'];
                    $bookPrice = $row['bookPrice'];
                    $quantity = $row['bookQuantity'];

                    // Calculate the total price for this book
                    $totalPrice = $bookPrice * $quantity;
                    $totalAmount += $totalPrice;

                    echo "<tr>
                            <td>{$bookTitle}</td>
                            <td>{$quantity}</td>
                            <td>RM " . number_format($bookPrice, 2) . "</td>
                            <td>RM " . number_format($totalPrice, 2) . "</td>
                        </tr>";
                }
                ?>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                    <td><strong>RM <?php echo number_format($totalAmount, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <div class="user-info">
            <h4>User Information</h4>
            <p><strong>Order Date:</strong> <?php echo htmlspecialchars($orderDate); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['userName']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['userEmail']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['userPhoneNo']); ?></p>
            <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($user['userAddress'])); ?></p>
        </div>

        <div class="receipt-footer">
            <p>© 2024 Pustaka Syafie Online Bookstore. All Rights Reserved.</p>
        </div>

        <form method="POST">
            <div class="text-center mt-4">
                <button type="submit" name="backToHome" class="btn btn-primary">Back to Home</button>
            </div>
        </form>
    </div>

    <!-- Close the database connection -->
    <?php $conn->close(); ?>
</body>
</html>
