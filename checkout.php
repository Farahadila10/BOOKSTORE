

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Pustaka Syafie Online Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4e1d2; /* Light Brown Pastel Background */
        }

        .container {
            max-width: 900px;
            margin-top: 30px;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: #343a40;
            color: white;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            width: 100%;
            margin-top: 20px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
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
    </style>
</head>
<body>

    <div class="container">
        <h1 class="my-5 text-center">Checkout</h1>

        <h3>Your Cart</h3>
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
                // Loop through the shopping cart
                foreach ($shoppingcart as $item) {
                    $totalPrice = $item['bookPrice'] * $item['bookQuantity'];
                    echo "<tr>
                            <td>{$item['bookTitle']}</td>
                            <td>{$item['bookQuantity']}</td>
                            <td>{$item['bookPrice']}</td>
                            <td>{$totalPrice}</td>
                        </tr>";
                }
                ?>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td><strong>RM <?php echo number_format($totalAmount, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <h3>Shipping Information</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Shipping Address</label>
                <textarea name="address" id="address" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" name="phone" id="phone" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="paymentMethod" class="form-label">Payment Method</label>
                <select name="paymentMethod" id="paymentMethod" class="form-control" required>
                    <option value="qr">QR Payment</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Place Order</button>
        </form>

        <a href="javascript:history.back()" class="btn btn-secondary">Back to Previous Page</a>
    </div>

</body>
</html>
