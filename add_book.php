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
    <title>Book Input Form</title>
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

        h2 {
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
    </style>
</head>
<body>

<?php
include 'header.php';
include 'menu.php';
?>
<div class="container">
    <h2>Enter Book Details</h2>

    <form id="bookForm">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" id="author" name="author" required>
        </div>
        <div class="form-group">
            <label for="year">Year</label>
            <input type="number" id="publication_year" name="year" required>
        </div>
        <div class="form-group">
            <label for="copies">Number of Copies</label>
            <input type="number" id="available_copies" name="copies" required>
        </div>
        <button type="submit">Add Book</button>
    </form>

    <h3>Book List</h3>
    <table id="bookTable">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Year</th>
                <th>Copies</th>
            </tr>
        </thead>
        <tbody>
            <!-- Book list will be displayed here -->
        </tbody>
    </table>
</div>

<script>
    document.getElementById('bookForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from refreshing the page

        // Get values from the form
        const title = document.getElementById('title').value;
        const author = document.getElementById('author').value;
        const year = document.getElementById('year').value;
        const copies = document.getElementById('copies').value;

        // Create a new row in the table
        const table = document.getElementById('bookTable').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();

        // Insert new cells with the book details
        newRow.insertCell(0).textContent = title;
        newRow.insertCell(1).textContent = author;
        newRow.insertCell(2).textContent = year;
        newRow.insertCell(3).textContent = copies;

        // Clear the form inputs
        document.getElementById('bookForm').reset();
    });
</script>

</body>
</html>

