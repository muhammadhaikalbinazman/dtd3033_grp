<?php
include 'dbconfig.php'; // Include database configuration

// Check if the book ID is provided
if (!isset($_GET['book_id'])) {
    die("Error: Book ID not provided.");
}

$book_id = intval($_GET['book_id']);

// Fetch the existing book details
$sql = "SELECT * FROM bookForm WHERE book_id = $book_id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    die("Error: Book not found.");
}
$book = $result->fetch_assoc();

// Handle form submission to update book details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = intval($_POST['year']);
    $copies = intval($_POST['copies']);

    // Validate input to prevent SQL injection
    $title = $conn->real_escape_string($title);
    $author = $conn->real_escape_string($author);

    $sql = "UPDATE bookForm SET title = '$title', author = '$author', publication_year = $year, available_copies = $copies WHERE book_id = $book_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: display_books.php");
        exit();
    } else {
        $error = "Error updating book: " . $conn->error;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="layout.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            outline: none;
        }

        .form-group input:focus {
            border-color: #007bff;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }

        .message {
            margin-top: 20px;
            font-size: 16px;
        }

        .message.error {
            color: red;
        }
    </style>
</head>
<body>

<?php 
include 'header.php';
include 'menu.php';
?>

<div class="container">
    <h1>Edit Book (ID: <?php echo $book_id; ?>)</h1>
    <form method="post">
        <div class="form-group">
            <label>Title:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
        </div>
        <div class="form-group">
            <label>Author:</label>
            <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
        </div>
        <div class="form-group">
            <label>Publication Year:</label>
            <input type="number" name="year" value="<?php echo $book['publication_year']; ?>" required>
        </div>
        <div class="form-group">
            <label>Available Copies:</label>
            <input type="number" name="copies" value="<?php echo $book['available_copies']; ?>" required>
        </div>
        <button type="submit">Update Book</button>
    </form>
    <?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>
</div>

<?php include 'footer.php'; ?>

</body>
</html>