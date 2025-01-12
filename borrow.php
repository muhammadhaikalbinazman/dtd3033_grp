<?php
include 'dbconfig.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json'); // Set content type to JSON
    
    $book_type = $_POST['book_type'];
    $borrow_date = $_POST['borrow_date'];
    $return_date = $_POST['return_date'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    
    // Validate and sanitize input
    $book_type = $conn->real_escape_string($book_type);
    $borrow_date = $conn->real_escape_string($borrow_date);
    $return_date = $conn->real_escape_string($return_date);
    $title = $conn->real_escape_string($title);
    $author = $conn->real_escape_string($author);
    $year = intval($year);
    
    // Generate a random borrow_id
    $borrow_id = rand(10000, 99999);
    
    // Insert into borrowing table
    $sql = "INSERT INTO bookBorrowing (borrow_id, book_type, borrow_date, return_date, title, author, year) 
            VALUES ($borrow_id, '$book_type', '$borrow_date', '$return_date', '$title', '$author', $year)";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode([
            'success' => true,
            'message' => 'Book borrowing request submitted successfully.',
            'borrow_id' => $borrow_id
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $conn->error
        ]);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow a Book</title>
    <link rel="stylesheet" href="layout.css">
    <style>
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
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            outline: none;
        }

        .date-group {
            display: flex;
            gap: 15px;
        }

        .date-group .form-group {
            flex: 1;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

<?php
include 'header.php';
include 'menu.php';
?>

<div class="container">
    <h2>Borrow a Book</h2>

    <form id="borrowForm" method="POST">
        <div class="form-group">
            <label for="book_type">Book Type</label>
            <select id="book_type" name="book_type" required>
                <option value="">Select a book type</option>
                <option value="fiction">Fiction</option>
                <option value="non-fiction">Non-Fiction</option>
                <option value="textbook">Textbook</option>
                <option value="reference">Reference</option>
                <option value="magazine">Magazine</option>
            </select>
        </div>

        <div class="date-group">
            <div class="form-group">
                <label for="borrow_date">Borrow Date</label>
                <input type="date" id="borrow_date" name="borrow_date" required>
            </div>
            <div class="form-group">
                <label for="return_date">Return Date</label>
                <input type="date" id="return_date" name="return_date" required>
            </div>
        </div>

        <div class="form-group">
            <label for="title">Book Title</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" id="author" name="author" required>
        </div>

        <div class="form-group">
            <label for="year">Publication Year</label>
            <input type="number" id="year" name="year" required>
        </div>

        <button type="submit">Submit Borrowing Request</button>
    </form>

    <div id="message" class="message"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum date for borrow date as today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('borrow_date').min = today;
        
        // Set default borrow date as today
        document.getElementById('borrow_date').value = today;
        
        // Update return date min value when borrow date changes
        document.getElementById('borrow_date').addEventListener('change', function() {
            const borrowDate = new Date(this.value);
            const minReturnDate = new Date(borrowDate);
            minReturnDate.setDate(borrowDate.getDate() + 1);
            
            document.getElementById('return_date').min = minReturnDate.toISOString().split('T')[0];
            
            // Set default return date to 14 days after borrow date
            const defaultReturnDate = new Date(borrowDate);
            defaultReturnDate.setDate(borrowDate.getDate() + 14);
            document.getElementById('return_date').value = defaultReturnDate.toISOString().split('T')[0];
        });
        
        // Trigger change event to set initial return date
        document.getElementById('borrow_date').dispatchEvent(new Event('change'));
    });

    document.getElementById('borrowForm').addEventListener('submit', function(event) {
        event.preventDefault();

        // Clear previous error message
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = '';
        messageDiv.className = 'message';

        const formData = new FormData(this);
        const borrowDate = new Date(formData.get('borrow_date'));
        const returnDate = new Date(formData.get('return_date'));
        
        // Validate dates
        if (returnDate <= borrowDate) {
            messageDiv.textContent = 'Return date must be after borrow date';
            messageDiv.className = 'message error';
            return;
        }

        // Calculate borrowing duration
        const duration = Math.ceil((returnDate - borrowDate) / (1000 * 60 * 60 * 24));
        if (duration > 30) {
            messageDiv.textContent = 'Maximum borrowing period is 30 days';
            messageDiv.className = 'message error';
            return;
        }

        // Convert FormData to URLSearchParams
        const params = new URLSearchParams();
        for (const pair of formData) {
            params.append(pair[0], pair[1]);
        }

        // Send data to server
        fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json'
            },
            body: params
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.textContent = data.message;
                messageDiv.className = 'message success';
                this.reset();
                
                // Reset default dates
                document.getElementById('borrow_date').value = new Date().toISOString().split('T')[0];
                document.getElementById('borrow_date').dispatchEvent(new Event('change'));
            } else {
                messageDiv.textContent = data.message;
                messageDiv.className = 'message error';
            }
        })
        .catch(error => {
            messageDiv.textContent = 'Error: ' + error;
            messageDiv.className = 'message error';
        });
    });
</script>

<?php include 'footer.php'; ?>
</body>
</html>