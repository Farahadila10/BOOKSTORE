<?php
session_start();
include("bookstore.php"); // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$userID = $_SESSION['userID'];

// Fetch user details from the users table
$userQuery = "SELECT userName, userEmail, userPhoneNo, userAddress FROM user WHERE userID = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param('i', $userID);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();

// Check if the user is missing a phone number or address
if (empty($user['userPhoneNo']) || empty($user['userAddress'])) {
    header("Location: accInformation.php"); // Redirect to account information page if missing
    exit();
}

// Fetch cart items for the logged-in user
$cartQuery = "SELECT * FROM shoppingcart WHERE userID = ?";
$cartStmt = $conn->prepare($cartQuery);
$cartStmt->bind_param('i', $userID);
$cartStmt->execute();
$cartResult = $cartStmt->get_result();

// Check if the form was submitted to update the cart
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $cartID => $quantity) {
        if ($quantity > 0) {
            // Update the quantity and total price
            $updateQuery = "UPDATE shoppingcart SET bookQuantity = ?, totalPrice = bookPrice * ? WHERE cartID = ? AND userID = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param('iiii', $quantity, $quantity, $cartID, $userID);
            $updateStmt->execute();
        } else {
            // Remove the item if quantity is 0
            $removeQuery = "DELETE FROM shoppingcart WHERE cartID = ? AND userID = ?";
            $removeStmt = $conn->prepare($removeQuery);
            $removeStmt->bind_param('ii', $cartID, $userID);
            $removeStmt->execute();
        }
    }
    header("Location: cart.php"); // Redirect to refresh cart after update
    exit();
}

// Remove item from cart if "remove" is clicked
if (isset($_GET['remove'])) {
    $cartID = $_GET['remove'];
    $removeQuery = "DELETE FROM shoppingcart WHERE cartID = ? AND userID = ?";
    $removeStmt = $conn->prepare($removeQuery);
    $removeStmt->bind_param('ii', $cartID, $userID);
    $removeStmt->execute();
    header("Location: cart.php"); // Redirect to refresh cart after removal
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart - Pustaka Syafie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f7f4;
            color: #333;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .table thead th {
            background-color: #f2f2f2;
        }

        h1, h4 {
            color: #2c3e50;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-success {
            background-color: #2ecc71;
        }

        .btn-success:hover {
            background-color: #27ae60;
        }

        .alert-warning {
            background-color: #fcf8e3;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        a.alert-link {
            color: #856404;
            text-decoration: underline;
        }

        a.alert-link:hover {
            text-decoration: none;
        }

        .user-info {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .user-info p {
            margin: 0 0 10px;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <h1 class="text-center mb-4">Your Shopping Cart</h1>

    <div class="user-info">
        <h4>User Information</h4>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['userName']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['userEmail']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['userPhoneNo']); ?></p>
        <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($user['userAddress'])); ?></p>
    </div>

    <?php if ($cartResult->num_rows > 0): ?>
        <form method="POST">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Price (RM)</th>
                        <th>Quantity</th>
                        <th>Total (RM)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalAmount = 0;
                    while ($cartItem = $cartResult->fetch_assoc()):
                        $bookID = $cartItem['bookID'];
                        $quantity = $cartItem['bookQuantity'];
                        $totalPrice = $cartItem['totalPrice'];
                        
                        // Fetch book details
                        $bookQuery = "SELECT bookTitle, bookPrice FROM book WHERE bookID = ?";
                        $bookStmt = $conn->prepare($bookQuery);
                        $bookStmt->bind_param('i', $bookID);
                        $bookStmt->execute();
                        $bookResult = $bookStmt->get_result();
                        $book = $bookResult->fetch_assoc();
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['bookTitle']); ?></td>
                            <td>RM <?php echo number_format($book['bookPrice'], 2); ?></td>
                            <td>
                                <input type="number" name="quantity[<?php echo $cartItem['cartID']; ?>]" value="<?php echo $quantity; ?>" min="0" class="form-control" style="width: 80px;">
                            </td>
                            <td>RM <?php echo number_format($totalPrice, 2); ?></td>
                            <td>
                                <a href="cart.php?remove=<?php echo $cartItem['cartID']; ?>" class="btn btn-danger btn-sm">Remove</a>
                            </td>
                        </tr>
                        <?php
                        $totalAmount += $totalPrice;
                    endwhile;
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td>RM <?php echo number_format($totalAmount, 2); ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <div class="d-flex justify-content-between">
                <a href="shop.php" class="btn btn-secondary">Continue Shopping</a>
                <button type="submit" name="update_cart" class="btn btn-primary">Update Cart</button>
                <a href="payment.php" class="btn btn-success">Proceed to Payment</a>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            Your cart is empty. <a href="shop.php" class="alert-link">Go back to shopping</a>.
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
