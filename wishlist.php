<?php
session_start();
include("bookstore.php"); // Include the database connection file

// Remove book from wishlist if remove action is triggered
if (isset($_GET['remove']) && isset($_SESSION['wishlist'])) {
    $bookIDToRemove = $_GET['remove'];
    // Remove the book from the wishlist array
    $_SESSION['wishlist'] = array_filter($_SESSION['wishlist'], function($book) use ($bookIDToRemove) {
        return $book['bookID'] != $bookIDToRemove;
    });
    $_SESSION['wishlist_message'] = "Book removed from your wishlist!";
    header("Location: wishlist.php"); // Redirect back to wishlist
    exit();
}

// Check if a book has been added to the wishlist
if (isset($_POST['bookID'])) {
    $bookID = $_POST['bookID'];
    // Initialize wishlist if not already set
    if (!isset($_SESSION['wishlist'])) {
        $_SESSION['wishlist'] = [];
    }

    // Add the book to the wishlist
    $_SESSION['wishlist'][] = [
        'bookID' => $bookID,
        'bookTitle' => $_POST['bookTitle'],
        'bookPrice' => $_POST['bookPrice'],
        'bookImage' => $_POST['bookImage']  // Added bookImage field
    ];
    $_SESSION['wishlist_message'] = "Book added to your wishlist!";
    header("Location: wishlist.php"); // Redirect to the wishlist page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist - Pustaka Syafie Online Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4; /* Light Gray */
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #343a40; /* Dark Gray */
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 0 0 8px 8px;
        }

        .header h1 {
            font-size: 2.5rem;
            margin: 0;
        }

        .container {
            padding: 20px;
        }

        .wishlist-item {
            margin-bottom: 20px;
        }

        .wishlist-item-card {
            display: flex;
            align-items: stretch;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            background-color: white;
        }

        .wishlist-item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .wishlist-item-card img {
            max-width: 150px;
            object-fit: cover;
        }

        .wishlist-item-details {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .wishlist-item-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .wishlist-item-price {
            color: #28a745;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .wishlist-item-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn {
            border-radius: 8px;
            font-size: 1rem;
            padding: 8px 16px;
        }

        .btn-remove {
            background-color: #dc3545;
            color: white;
        }

        .btn-remove:hover {
            background-color: #c82333;
        }

        .btn-add-to-cart {
            background-color: #007bff;
            color: white;
        }

        .btn-add-to-cart:hover {
            background-color: #0056b3;
        }

        .alert {
            text-align: center;
        }

        .back-to-home {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
        }

        .back-to-home a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            font-size: 1rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .back-to-home a:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 768px) {
            .wishlist-item-card {
                flex-direction: column;
                align-items: center;
            }

            .wishlist-item-card img {
                width: 100%;
                max-height: 200px;
            }

            .wishlist-item-details {
                text-align: center;
            }

            .wishlist-item-actions {
                justify-content: center;
            }

            .back-to-home a {
                font-size: 0.9rem;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Your Wishlist</h1>
    </header>

    <div class="container">
        <?php if (isset($_SESSION['wishlist_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['wishlist_message']; ?>
            </div>
            <?php unset($_SESSION['wishlist_message']); ?>
        <?php endif; ?>

        <div class="row">
            <?php if (isset($_SESSION['wishlist']) && !empty($_SESSION['wishlist'])): ?>
                <?php foreach ($_SESSION['wishlist'] as $book): ?>
                    <div class="col-md-6 col-lg-4 wishlist-item">
                        <div class="card wishlist-item-card">
                            <div class="wishlist-item-details">
                                <div>
                                    <h5 class="wishlist-item-title"><?php echo htmlspecialchars($book['bookTitle']); ?></h5>
                                    <p class="wishlist-item-price">RM <?php echo number_format($book['bookPrice'], 2); ?></p>
                                </div>
                                <div class="wishlist-item-actions">
                                    <a href="wishlist.php?remove=<?php echo $book['bookID']; ?>" class="btn btn-remove">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">
                    Your wishlist is empty. Start exploring books and add them to your wishlist!
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="back-to-home">
        <a href="home_after.php">Back to Home</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
