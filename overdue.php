<?php
session_start();

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'dbconfig.php'; // Database connection file

// Get current date
$current_date = date('Y-m-d');

// SQL query to get overdue borrowing records
$sql = "
    SELECT 
        bb.borrow_id, 
        u.name, 
        bb.title, 
        bb.author, 
        bb.book_type, 
        bb.borrow_date, 
        bb.return_date
    FROM bookborrowing bb
    LEFT JOIN users u ON bb.user_id = u.user_id
    WHERE bb.return_date < ? AND bb.return_date IS NOT NULL
";

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_date);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overdue Borrowings</title>
    <link rel="stylesheet" href="layout.css">
    <style>
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .no-records {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>
<body>

<?php
include 'header.php';
include 'menu.php';
?>

<div class="container">
    <h2>Overdue Borrowings</h2>
    <table>
        <thead>
            <tr>
                <th>Borrow ID</th>
                <th>Username</th>
                <th>Book Title</th>
                <th>Author</th>
                <th>Book Type</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['borrow_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['book_type']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['borrow_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['return_date']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='no-records'>No overdue borrowings found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
