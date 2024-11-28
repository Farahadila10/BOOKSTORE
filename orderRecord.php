<?php
include 'bookstore.php'; // Database connection

// Retrieve all orders with user details, total amount, payment method, and order date
$orderQuery = "
    SELECT o.orderID, u.userName, u.userEmail, u.userAddress, o.total_amount, o.paymentMethod, o.orderDate
    FROM orders o
    JOIN user u ON o.userID = u.userID
    ORDER BY o.orderDate DESC;
";

$result = $conn->query($orderQuery);

// Handle the delete action if requested
if (isset($_GET['delete_order_id'])) {
    $orderIdToDelete = $_GET['delete_order_id'];

    // Query to delete the order from the 'orders' table
    $deleteOrderQuery = "DELETE FROM orders WHERE orderID = ?";
    $stmt = $conn->prepare($deleteOrderQuery);
    $stmt->bind_param("i", $orderIdToDelete);
    $stmt->execute();
    
    // Redirect to the same page to refresh the order list after deletion
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Order Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4; /* Light Gray Background */
        }

        .container {
            margin-top: 40px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #4CAF50;
            color: white;
            font-size: 1.5em;
            text-align: center;
            padding: 15px;
        }

        .table-responsive {
            margin-top: 30px;
        }

        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }

        .table thead {
            background-color: #f8f9fa;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
            color: #888;
        }

        .back-button {
            position: fixed;
            bottom: 20px;
            left: 20px;
        }

        .btn-secondary {
            background-color: #777;
            border-color: #777;
        }

        .btn-secondary:hover {
            background-color: #555;
            border-color: #555;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Order Records
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <?php
                    if ($result->num_rows > 0) {
                        echo "<table class='table table-bordered'>
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Total Amount (RM)</th>
                                        <th>Order Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>";
                        // Loop through orders and display them
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['orderID']}</td>
                                    <td>{$row['userName']}</td>
                                    <td>{$row['userEmail']}</td>
                                    <td>{$row['userAddress']}</td>
                                    <td>RM " . number_format($row['total_amount'], 2) . "</td>
                                    <td>{$row['orderDate']}</td>
                                    <td>
                                        <a href='?delete_order_id={$row['orderID']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this order?\");'>Delete</a>
                                    </td>
                                  </tr>";
                        }
                        echo "</tbody></table>";
                    } else {
                        echo "<p>No orders found.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>© 2024 Pustaka Syafie Bookstore | All Rights Reserved</p>
    </div>

    <div class="back-button">
        <button class="btn btn-secondary" onclick="window.history.back();">Back</button>
    </div>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
