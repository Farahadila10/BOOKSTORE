<?php
session_start();
// Include database connection script
include 'bookstore.php';

// Initialize variables
$totalAmount = 0;
$userID = $_SESSION['userID'];  // Assuming the user ID is stored in the session

// Query to get cart details from the shoppingcart table for the current user
$cartQuery = "SELECT bookID, bookTitle, bookPrice, bookQuantity 
              FROM shoppingcart 
              WHERE userID = $userID";
$cartResult = $conn->query($cartQuery);

if (!$cartResult || $cartResult->num_rows == 0) {
    echo "Your cart is empty. <a href='shop.php'>Go back to shopping</a>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Pustaka Syafie Online Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f7f4; /* Light Beige Background */
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1, h3 {
            color: #2c3e50; /* Dark Gray for headings */
        }

        .table th {
            background-color: #f2f2f2;
            color: #333;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            font-size: 16px;
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 12px;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 15px 0;
            text-align: center;
            margin-top: 40px;
        }

        .footer p {
            margin: 0;
        }

        .qr-code {
            margin-top: 30px;
            text-align: center;
        }

        .qr-code img {
            max-width: 150px;
            height: auto;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .btn-primary, .btn-secondary {
                font-size: 14px;
            }

            .qr-code img {
                max-width: 120px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1 class="my-5 text-center">Payment</h1>

        <h3>Your Order Summary</h3>
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
                    <td colspan="3" class="text-end"><strong>Total Book Amount:</strong></td>
                    <td><strong>RM <?php echo number_format($totalAmount, 2); ?> </strong></td>
                </tr>
            </tbody>
        </table>

        <h3>Choose Payment Method</h3>
        <form method="POST" action="receipt.php">
            <div class="mb-3">
                <label for="payment_method" class="form-label">Select Payment Method</label>
                <select name="payment_method" id="payment_method" class="form-control" required>
                    <option value="qr">QR Code</option>
                </select>
            </div>

            <!-- QR Code Section -->
            <div class="qr-code">
                <h3>Scan the QR Code to Complete Payment</h3>
                <?php
                // Generate QR code URL (you can replace this with your actual payment URL or other data)
                $qrData = "TotalAmount=" . $totalAmount . " RM"; // Updated total with delivery
                $qrCodeUrl = "images/qrCode.jpg" . urlencode($qrData) . "&size=150x150";
                ?>
                <!-- QR Code Image Replacement -->
                <img src="images/qrCode.jpg" alt="QR Code" class="img-fluid mt-3">
                <button type="submit" class="btn btn-primary mt-4">Proceed with Payment</button>
            </div>
        </form>

        <!-- Back Button -->
        <button type="button" class="btn btn-secondary mt-3" onclick="history.back()">Back</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
