<?php
// Include database configuration
include 'dbconfig.php';

// Initialize variables
$book_id = '';
$book = null;
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = trim($_POST['book_id']);

    // Validate book_id
    if (empty($book_id)) {
        $error = 'Please enter a book ID.';
    } else {
        // Prepare and execute the query
        $query = "SELECT * FROM bookform WHERE book_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the book exists
        if ($result->num_rows > 0) {
            $book = $result->fetch_assoc();
        } else {
            $error = 'No book found with the given ID.';
        }

        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books</title>
    <link rel="stylesheet" href="layout.css">
</head>
<body>

<?php 
include 'header.php';
include 'menu.php';
 ?>
    <div class="container">
        <h1>Search Books by ID</h1>
        <form action="search_books.php" method="POST">
            <label for="book_id">Book ID:</label>
            <input type="number" id="book_id" name="book_id" value="<?php echo htmlspecialchars($book_id); ?>" required>
            <button type="submit">Search</button>
        </form>

        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if ($book): ?>
            <h2>Book Details</h2>
            <p><strong>ID:</strong> <?php echo htmlspecialchars($book['book_id']); ?></p>
            <p><strong>Title:</strong> <?php echo htmlspecialchars($book['title']); ?></p>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
            <p><strong>Publication Year:</strong> <?php echo htmlspecialchars($book['publication_year']); ?></p>
        <?php endif; ?>
    </div>
<?php include 'footer.php'; ?>
</body>
</html>