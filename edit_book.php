<?php
include 'db_connection.php';

// Step 1: Get the book ID from the query parameter
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    // Step 2: Fetch the current details of the book
    $sql = "SELECT * FROM books WHERE book_id = $book_id";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $book = $result->fetch_assoc(); // Get the book data
    } else {
        die("Book not found.");
    }
} else {
    die("Invalid request.");
}

// Step 3: Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $copies = $_POST['copies'];

    // Step 4: Update the book details in the database
    $sql = "UPDATE books SET title = '$title', author = '$author', publication_year = $year, available_copies = $copies WHERE book_id = $book_id";

    if ($conn->query($sql) === TRUE) {
        header('Location: display_books.php'); // Redirect to the books list page
        exit();
    } else {
        $error = "Error updating book: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
</head>
<body>
    <h1>Edit Book</h1>
    <form method="post">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
        <br>
        <label>Author:</label>
        <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
        <br>
        <label>Publication Year:</label>
        <input type="number" name="year" value="<?php echo $book['publication_year']; ?>" required>
        <br>
        <label>Available Copies:</label>
        <input type="number" name="copies" value="<?php echo $book['available_copies']; ?>" required>
        <br>
        <button type="submit">Update Book</button>
    </form>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
