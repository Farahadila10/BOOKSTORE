<?php
// Include database connection file
include("bookstore.php");

// Initialize variables to avoid undefined variable warnings
$searchQuery = "";
$result = false;

// Check if a search query is provided
if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];

    // Sanitize the input to prevent SQL injection
    $searchQuery = htmlspecialchars($searchQuery);

    // Search the database
    $stmt = $conn->prepare("SELECT * FROM book WHERE 
        bookTitle LIKE ? OR 
        bookAuthor LIKE ? OR 
        bookGenre LIKE ?"); // Removed bookDescription

    // Add wildcards to the search query for a partial match
    $likeQuery = "%" . $searchQuery . "%";

    // Bind parameters (all are strings)
    $stmt->bind_param("sss", $likeQuery, $likeQuery, $likeQuery);

    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUSTAKA SYAFIE BOOKSTORE</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .back-button {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo">
            <a href="home.php">
                <i class="fas fa-book-reader logo-icon"></i>
                <div>
                    <h1>PUSTAKA SYAFIE</h1>
                    <p>BOOKSTORE</p>
                </div>
            </a>
        </div>
        <div class="search-bar">
            <form action="search.php" method="GET">
                <input type="text" name="query" placeholder="Search for books, authors, or categories..." required />
                <button type="submit">Search</button>
            </form>
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

    <div class="search-results">
        <h1>Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h1>
        <?php if ($result && $result->num_rows > 0): ?>
            <div class="product-list">
                <?php while ($book = $result->fetch_assoc()): ?>
                    <div class="product-item">
                        <!-- Book Image -->
                        <div class="product-image">
                            <img class="card-img-top" src="<?php echo $book['bookImage']; ?>" alt="<?php echo htmlspecialchars($book['bookTitle']); ?>">
                        </div>
                        <!-- Book Title, Author, Genre, and Price -->
                        <div class="product-details">
                            <h3><?php echo htmlspecialchars($book['bookTitle']); ?></h3>
                            <p>Author: <?php echo htmlspecialchars($book['bookAuthor']); ?></p>
                            <p>Genre: <?php echo htmlspecialchars($book['bookGenre']); ?></p>
                            <p>Price: RM<?php echo htmlspecialchars($book['bookPrice']); ?></p>

                            <!-- Wishlist Icon -->
                            <form action="wishlist.php" method="POST" style="display: inline-block; margin-left: 10px;">
                                <input type="hidden" name="bookID" value="<?php echo $book['bookID']; ?>">
                                <input type="hidden" name="bookTitle" value="<?php echo htmlspecialchars($book['bookTitle']); ?>">
                                <input type="hidden" name="bookPrice" value="<?php echo htmlspecialchars($book['bookPrice']); ?>">
                                <button type="submit" class="wishlist-btn">
                                    <i class="fas fa-heart"></i> Wishlist
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No results found for "<?php echo htmlspecialchars($searchQuery); ?>".</p>
        <?php endif; ?>
    </div>

    <!-- Back Button -->
    <a href="javascript:history.back()" class="back-button">Back</a>

    <!-- Footer Section -->
    <footer>
        <div class="footer-content">
            <ul>
                <li><a href="faq.html">FAQs</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="terms.html">Terms & Condition</a></li>
                <li><a href="userManual.html">User Manual</a></li>
            </ul>
            <div class="social-media">
                <a href="#"></a> | <a href="#"></a> | <a href="#"></a>
            </div>
            <div class="col-md-6">
                <p>Copyright © 2024 PUSTAKA SYAFIE BOOK CO. (MALAYSIA) SDN. BHD. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
