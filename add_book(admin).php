<?php
session_start();

// Include database configuration
include 'dbconfig.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] == 'admin') {
    // Redirect to login page or show an error message
    header('Location: add_book(admin).php');
    exit;
}

// Initialize variables
$title = $author = $summary = $publish_year = '';
$error = $success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $summary = trim($_POST['summary']);
    $publish_year = trim($_POST['publish_year']);

    // Validate form inputs
    if (empty($title) || empty($author) || empty($summary) || empty($publish_year)) {
        $error = 'All fields are required.';
    } else {
        // Prepare and execute the insert query
        $query = "INSERT INTO booklist (title, author, summary, publish_year) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $title, $author, $summary, $publish_year);

        if ($stmt->execute()) {
            $success = 'Book added successfully!';
            // Clear form inputs
            $title = $author = $summary = $publish_year = '';
        } else {
            $error = 'Error adding book: ' . $stmt->error;
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
    <title>Add Book</title>
    <link rel="stylesheet" href="layout.css">
</head>
<body>
    <div class="container">
        <h1>Add New Book</h1>
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <form action="add_book(admin).php" method="POST">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
            <br>
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($author); ?>" required>
            <br>
            <label for="summary">Summary:</label>
            <textarea id="summary" name="summary" required><?php echo htmlspecialchars($summary); ?></textarea>
            <br>
            <label for="publish_year">Publish Year:</label>
            <input type="number" id="publish_year" name="publish_year" value="<?php echo htmlspecialchars($publish_year); ?>" required>
            <br>
            <button type="submit">Add Book</button>
        </form>
    </div>
</body>
</html>