<?php
session_start();
include("bookstore.php"); // Include the database connection file

// Fetch category from query parameters
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Modify query based on category
$booksQuery = "SELECT bookID, bookTitle, bookAuthor, category, bookGenre, specialOffer, bookPrice, bookImage, bookQuantity FROM book";
if (!empty($category)) {
    $booksQuery .= " WHERE category = '" . $conn->real_escape_string($category) . "'";
} elseif ($category == 'Crime') {
    $booksQuery .= " WHERE bookGenre = 'Crime'"; // Filter for Crime genre
}
$booksResult = $conn->query($booksQuery);

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$userID = $_SESSION['userID'];

// Add book to cart if the form is submitted
if (isset($_POST['add_to_cart'])) {
    $bookID = $_POST['bookID'];
    $quantity = $_POST['quantity'];

    if ($quantity > 0) {
        // Fetch the book details
        $bookQuery = "SELECT bookTitle, bookPrice FROM book WHERE bookID = ?";
        $bookStmt = $conn->prepare($bookQuery);
        $bookStmt->bind_param('i', $bookID);
        $bookStmt->execute();
        $bookResult = $bookStmt->get_result();
        $book = $bookResult->fetch_assoc();

        $bookTitle = $book['bookTitle'];
        $bookPrice = $book['bookPrice'];
        $totalPrice = $bookPrice * $quantity;

        // Check if the book is already in the shopping cart
        $checkQuery = "SELECT * FROM shoppingcart WHERE userID = ? AND bookID = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param('ii', $userID, $bookID);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // Update the quantity and total price if the book already exists in the shopping cart
            $updateQuery = "UPDATE shoppingcart SET bookQuantity = bookQuantity + ?, totalPrice = totalPrice + ? WHERE userID = ? AND bookID = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param('iiii', $quantity, $totalPrice, $userID, $bookID);
            $updateStmt->execute();
        } else {
            // Insert new item into the shopping cart
            $insertQuery = "INSERT INTO shoppingcart (bookTitle, bookPrice, bookID, bookQuantity, totalPrice, userID) VALUES (?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param('ssiiis', $bookTitle, $bookPrice, $bookID, $quantity, $totalPrice, $userID);
            $insertStmt->execute();
        }

        // Redirect to cart.php after adding the item to the cart
        echo "<script>alert('Item added to cart successfully'); window.location.href = 'cart.php';</script>";
        exit();
    }
}

// Fetch unique genres for filtering
$genresQuery = "SELECT DISTINCT bookGenre FROM book";
$genresResult = $conn->query($genresQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pustaka Syafie Online Bookstore - Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .header {
            padding: 20px;
            text-align: center;
            background-color: #343a40;
            color: white;
        }
        .header h1 {
            font-size: 2.5rem;
            margin: 0;
        }
        .category-filter {
            margin: 20px auto;
            max-width: 800px;
            text-align: center;
        }
        .category-filter .btn {
            margin: 0 5px;
        }
        .product-list .card img {
            max-height: 200px;
            object-fit: contain;
        }
        .product-list .card {
            margin-bottom: 20px;
        }
        .wishlist-btn {
            background-color: #f8d7da;
            border: none;
            color: #721c24;
            padding: 8px 16px;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 5px;
        }
        .wishlist-btn i {
            margin-right: 5px;
        }
        .back-button {
            position: fixed;
            bottom: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .button-group {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: flex-start;
        }
        .button-group button {
            flex-shrink: 0;
        }
        
        /* Genre Bubble Style */
        .genre-bubble {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 30px;
            background-color: #007bff;
            color: white;
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .genre-bubble:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Optionally: Different colors for different genres */
        .genre-bubble[data-genre="Crime"] {
            background-color: #FFD700; /* Gold */
        }

        .genre-bubble[data-genre="All Books"] {
            background-color: #6c757d; /* Grey */
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Shop - <?php echo !empty($category) ? ucfirst($category) : 'All Books'; ?></h1>
    </header>

    <div class="container">
        <div class="row product-list">
            <?php if ($booksResult->num_rows > 0): ?>
                <?php while ($book = $booksResult->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <!-- Book Image -->
                            <div class="product-image">
                                <img class="card-img-top" src="<?php echo $book['bookImage']; ?>" alt="<?php echo htmlspecialchars($book['bookTitle']); ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($book['bookTitle']); ?></h5>
                                <p class="card-text">
                                    <strong>Author:</strong> <?php echo htmlspecialchars($book['bookAuthor']); ?><br>
                                    
                                    <!-- Genre Bubble -->
                                    <strong>Genre:</strong>
                                    <span class="genre-bubble" data-genre="<?php echo htmlspecialchars($book['bookGenre']); ?>">
                                        <?php echo htmlspecialchars($book['bookGenre']); ?>
                                    </span><br>
                                    
                                    <strong>Price:</strong> RM <?php echo number_format($book['bookPrice'], 2); ?><br>
                                    <strong>Available Quantity:</strong> <?php echo $book['bookQuantity']; ?>
                                </p>

                                <div class="button-group">
                                    <form method="POST" action="shop.php">
                                        <input type="hidden" name="bookID" value="<?php echo $book['bookID']; ?>">
                                        <input type="number" name="quantity" value="0" min="0" max="<?php echo $book['bookQuantity']; ?>" required>
                                        <button type="submit" name="add_to_cart" class="btn btn-success">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No books found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Home Button -->
    <button class="btn btn-secondary back-button" onclick="window.location.href='home_after.php';">Back</button>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
