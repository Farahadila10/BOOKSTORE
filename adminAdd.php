<?php
session_start();
include 'bookstore.php'; // Include the database connection script

// Handle form submission for adding a new book
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['edit_book']) && !isset($_POST['delete_book'])) {
    $bookTitle = $_POST['bookTitle'];
    $bookAuthor = !empty($_POST['bookAuthor']) ? $_POST['bookAuthor'] : "Unknown Author"; // Default if empty
    $bookGenre = $_POST['bookGenre'];
    $bookPrice = $_POST['bookPrice'];
    $bookQuantity = $_POST['bookQuantity']; // Getting the quantity

    // Initialize upload success flag and image path
    $uploadSuccess = false;
    $uploadFilePath = '';

    if (isset($_FILES['bookImage']) && $_FILES['bookImage']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['bookImage']['tmp_name'];
        $uploadFilePath = 'images/' . uniqid() . '_' . basename($_FILES['bookImage']['name']); // Unique filename

        if (move_uploaded_file($imageTmpPath, $uploadFilePath)) {
            $uploadSuccess = true;
        } else {
            $_SESSION['message'] = "Failed to upload image.";
        }
    }

    // Insert book into the database
    if ($uploadSuccess) {
        $sql = "INSERT INTO book (bookTitle, bookAuthor, bookGenre, bookPrice, bookQuantity, bookImage) 
                VALUES ('$bookTitle', '$bookAuthor', '$bookGenre', '$bookPrice', '$bookQuantity', '$uploadFilePath')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "Book added successfully!";
        } else {
            $_SESSION['message'] = "Error adding book: " . $conn->error;
        }
    }

    // Redirect to avoid form resubmission
    header('Location: adminAdd.php');
    exit();
}

// Handle book editing
if (isset($_POST['edit_book'])) {
    $bookID = $_POST['bookID'];
    $bookTitle = $_POST['bookTitle'];
    $bookAuthor = $_POST['bookAuthor'];
    $bookGenre = $_POST['bookGenre'];
    $bookPrice = $_POST['bookPrice'];
    $bookQuantity = $_POST['bookQuantity']; // Getting the quantity

    $uploadSuccess = false;
    $uploadFilePath = $_POST['existingBookImage']; // Keep existing image if no new one is uploaded

    if (isset($_FILES['bookImage']) && $_FILES['bookImage']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['bookImage']['tmp_name'];
        $uploadFilePath = 'images/' . uniqid() . '_' . basename($_FILES['bookImage']['name']); // Unique filename

        if (!move_uploaded_file($imageTmpPath, $uploadFilePath)) {
            $_SESSION['message'] = "Failed to upload image.";
        }
    }

    // Update book details in the database
    $sql = "UPDATE book SET bookTitle = '$bookTitle', bookAuthor = '$bookAuthor', bookGenre = '$bookGenre', 
            bookPrice = '$bookPrice', bookQuantity = '$bookQuantity', bookImage = '$uploadFilePath' WHERE bookID = '$bookID'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Book updated successfully!";
    } else {
        $_SESSION['message'] = "Error updating book: " . $conn->error;
    }

    // Redirect to avoid form resubmission
    header('Location: adminAdd.php');
    exit();
}

// Handle book deletion
if (isset($_POST['delete_book'])) {
    $bookID_to_delete = $_POST['bookID_to_delete'];

    // Delete the book from the database
    $sql = "DELETE FROM book WHERE bookID = '$bookID_to_delete'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Book deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting book: " . $conn->error;
    }

    // Redirect to avoid form resubmission
    header('Location: adminAdd.php');
    exit();
}

// Fetch all books from the database, grouped by genre
$sql = "SELECT * FROM book ORDER BY bookGenre";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .book-image {
            max-width: 100%;
            max-height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="my-5">Available Books</h1>

    <button class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#addBookModal">Add New Book</button>

    <?php
    if (isset($_SESSION['message'])) {
        echo "<div class='alert alert-info'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
    }

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    ?>
    <div class="row mb-4">
        <div class="col-md-3">
            <img src="<?php echo $row['bookImage']; ?>" class="book-image" alt="<?php echo $row['bookTitle']; ?>">
        </div>
        <div class="col-md-7">
            <h4><?php echo $row['bookTitle']; ?></h4>
            <p><strong>Author:</strong> <?php echo $row['bookAuthor']; ?></p>
            <p><strong>Category:</strong> <?php echo $row['bookGenre']; ?></p>
            <p><strong>Price:</strong> RM <?php echo number_format($row['bookPrice'], 2); ?></p>
            <p><strong>Quantity:</strong> <?php echo $row['bookQuantity']; ?></p>
        </div>
        <div class="col-md-2">
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editBookModal<?php echo $row['bookID']; ?>">Edit</button>
            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this book?');">
                <input type="hidden" name="bookID_to_delete" value="<?php echo $row['bookID']; ?>">
                <button type="submit" name="delete_book" class="btn btn-danger mt-2">Delete</button>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editBookModal<?php echo $row['bookID']; ?>" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBookModalLabel">Edit Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="bookID" value="<?php echo $row['bookID']; ?>">
                        <input type="hidden" name="existingBookImage" value="<?php echo $row['bookImage']; ?>">
                        <div class="mb-3">
                            <label for="bookTitle" class="form-label">Book Title</label>
                            <input type="text" class="form-control" id="bookTitle" name="bookTitle" value="<?php echo $row['bookTitle']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="bookAuthor" class="form-label">Author</label>
                            <input type="text" class="form-control" id="bookAuthor" name="bookAuthor" value="<?php echo $row['bookAuthor']; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="bookGenre" class="form-label">Category</label>
                            <select class="form-control" id="bookGenre" name="bookGenre" required>
                                <option value="Islamic" <?php echo ($row['bookGenre'] == 'Islamic') ? 'selected' : ''; ?>>Islamic</option>
                                <option value="Non-Fiction" <?php echo ($row['bookGenre'] == 'Non-Fiction') ? 'selected' : ''; ?>>Non-Fiction</option>
                                <option value="Crime" <?php echo ($row['bookGenre'] == 'Crime') ? 'selected' : ''; ?>>Crime</option>
                                <option value="Romance" <?php echo ($row['bookGenre'] == 'Romance') ? 'selected' : ''; ?>>Romance</option>
                                <option value="Revision" <?php echo ($row['bookGenre'] == 'Revision') ? 'selected' : ''; ?>>Revision</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="bookPrice" class="form-label">Price (RM)</label>
                            <input type="number" class="form-control" id="bookPrice" name="bookPrice" value="<?php echo $row['bookPrice']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="bookQuantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="bookQuantity" name="bookQuantity" value="<?php echo $row['bookQuantity']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="bookImage" class="form-label">Image</label>
                            <input type="file" class="form-control" id="bookImage" name="bookImage">
                        </div>
                        <button type="submit" name="edit_book" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php } } ?>
</div>
<!-- Add Book Modal -->
<div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBookModalLabel">Add New Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="bookTitle" class="form-label">Book Title</label>
                        <input type="text" class="form-control" id="bookTitle" name="bookTitle" required>
                    </div>
                    <div class="mb-3">
                        <label for="bookAuthor" class="form-label">Author</label>
                        <input type="text" class="form-control" id="bookAuthor" name="bookAuthor">
                    </div>
                    <div class="mb-3">
                        <label for="bookGenre" class="form-label">Category</label>
                        <select class="form-control" id="bookGenre" name="bookGenre" required>
                            <option value="Islamic">Islamic</option>
                            <option value="Romance">Romance</option>
                            <option value="Non-Fiction">Non-Fiction</option>
                            <option value="Revision">Revision</option>
                            <option value="Crime">Crime</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bookPrice" class="form-label">Price (RM)</label>
                        <input type="number" step="0.01" class="form-control" id="bookPrice" name="bookPrice" required>
                    </div>
                    <div class="mb-3">
                        <label for="bookQuantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="bookQuantity" name="bookQuantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="bookImage" class="form-label">Book Image</label>
                        <input type="file" class="form-control" id="bookImage" name="bookImage" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Book</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Back Button -->
<a href="adminPage.php" class="btn btn-secondary back-button">Back</a>
</body>
</html>