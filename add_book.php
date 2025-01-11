<?php
include 'dbconfig.php'; // Include database configuration

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $copies = $_POST['copies'];

    // Validate input to prevent SQL injection
    $title = $conn->real_escape_string($title);
    $author = $conn->real_escape_string($author);
    $year = intval($year);
    $copies = intval($copies);

    // Insert into the correct table (`bookForm`)
    $sql = "INSERT INTO bookForm (title, author, publication_year, available_copies) VALUES ('$title', '$author', $year, $copies)";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Book added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }
    exit();
}
$conn->close();
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

        .message {
            margin-top: 20px;
            font-size: 16px;
        }

        .message.success {
            color: green;
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
    <h2>Enter Book Details</h2>

    <form id="bookForm" method="POST">
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
        <input type="number" id="year" name="year" required>
    </div>
    <div class="form-group">
        <label for="copies">Number of Copies</label>
        <input type="number" id="copies" name="copies" required>
    </div>
    <button type="submit">Add Book</button>
</form>

<div id="message" class="message"></div>

</div>

<script>
    document.getElementById('bookForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form from refreshing the page

    // Get values from the form
    const title = document.getElementById('title').value;
    const author = document.getElementById('author').value;
    const year = document.getElementById('year').value;
    const copies = document.getElementById('copies').value;

    // Send data to the server using AJAX
    fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `title=${encodeURIComponent(title)}&author=${encodeURIComponent(author)}&year=${year}&copies=${copies}`
    })
    .then(response => response.json())
    .then(data => {
        const messageDiv = document.getElementById('message');
        if (data.success) {
            // Display success message
            messageDiv.textContent = data.message;
            messageDiv.className = 'message success';

            // Clear the form inputs
            document.getElementById('bookForm').reset();
        } else {
            // Display error message
            messageDiv.textContent = data.message;
            messageDiv.className = 'message error';
        }
    })
    .catch(error => {
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = 'Error: ' + error;
        messageDiv.className = 'message error';
    });
});
</script>

</body>
</html>