<?php
include 'dbconfig.php';

$sql = "SELECT borrows.borrow_id, users.name AS user_name, books.title AS book_title, borrows.borrow_date, borrows.return_date
        FROM borrows
        JOIN users ON borrows.user_id = users.user_id
        JOIN books ON borrows.book_id = books.book_id";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Report</title>
</head>
<body>
    <h1>Borrow Report</h1>
    <table border="1">
        <tr>
            <th>Borrow ID</th>
            <th>User</th>
            <th>Book</th>
            <th>Borrow Date</th>
            <th>Return Date</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['borrow_id']; ?></td>
            <td><?php echo $row['user_name']; ?></td>
            <td><?php echo $row['book_title']; ?></td>
            <td><?php echo $row['borrow_date']; ?></td>
            <td><?php echo $row['return_date'] ? $row['return_date'] : 'Not Returned'; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
