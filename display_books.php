<?php
include 'dbconfig.php'; // Include database configuration

// Handle delete request
if (isset($_GET['delete'])) {
    $book_id = intval($_GET['delete']);
    $sql = "DELETE FROM bookForm WHERE book_id = $book_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: display_books.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Fetch all books from the database
$sql = "SELECT * FROM bookForm";
$result = $conn->query($sql);
if (!$result) {
    die("Error fetching records: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
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
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        table td {
            background-color: #fff;
        }

        .add-button, .edit-button, .delete-button {
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            padding: 5px 10px;
            text-decoration: none;
        }

        .add-button:hover {
            background-color: #218838;
        }

        .edit-button {
            background-color: #007bff;
        }

        .edit-button:hover {
            background-color: #0056b3;
        }

        .delete-button {
            background-color: #dc3545;
        }

        .delete-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
<?php 
include 'header.php';
include 'menu.php'; 
?>

<div class="container">
    <h1>Books</h1>

    <a href="add_book.php" class="add-button">Add Book</a>

    <table>
        <thead>
            <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Year</th>
                <th>Available Copies</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['book_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                    <td><?php echo htmlspecialchars($row['publication_year']); ?></td>
                    <td><?php echo htmlspecialchars($row['available_copies']); ?></td>
                    <td>
                        <a href="edit_book.php?book_id=<?php echo $row['book_id']; ?>" class="edit-button">Edit</a>
                        <a href="display_books.php?delete=<?php echo $row['book_id']; ?>" class="delete-button">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
</body>
</html>