<?php
include 'dbconfig.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $copies = $_POST['copies'];

    $sql = "INSERT INTO books (title, author, publication_year, available_copies) VALUES ('$title', '$author', $year, $copies)";
    if ($conn->query($sql) === TRUE) {
        header('Location: display_books.php');
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
</head>
<body>
    <h1>Add Book</h1>
    <form method="post">
        <label>Title:</label>
        <input type="text" name="title" required>
        <br>
        <label>Author:</label>
        <input type="text" name="author" required>
        <br>
        <label>Publication Year:</label>
        <input type="number" name="year" required>
        <br>
        <label>Available Copies:</label>
        <input type="number" name="copies" required>
        <br>
        <button type="submit">Add Book</button>
    </form>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
