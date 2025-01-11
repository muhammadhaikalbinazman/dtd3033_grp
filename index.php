<?php
session_start();
include 'dbconfig.php'; // Include database connection

function checkauth($conn) {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit();
    }

    $username = $_SESSION['user'];
    $query = "SELECT name, password FROM users WHERE name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($storedUsername, $storedHash);
    $stmt->fetch();
    $stmt->close();

    return $storedUsername;
}

$storedUsername = checkauth($conn);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management System</title>
    <link rel="stylesheet" href="layout.css">
    <style>
        main {
            padding: 2rem;
            text-align: center;
        }
        .features {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px; /* Adjust the gap between features as needed */
}

.feature {
    flex: 1;
    min-width: 200px; /* Adjust the minimum width as needed */
    max-width: 300px; /* Adjust the maximum width as needed */
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease;
}

.feature-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.feature:hover {
    background-color: #e9e9e9;
}
    </style>
</head>
<body>
    <main>
        <h1>Welcome, <?php echo htmlspecialchars($storedUsername); ?>!</h1>

    </main>


<?php 
  include 'header.php';
  include 'menu.php';
?>
    <main>
    <h2>Welcome to the Book Management System</h2>
    <p>Manage your library, track book details, and much more.</p>
    <div class="features">
    <div class="feature">
        <a href="add_book.php" class="feature-link">
            <h3>Add New Books</h3>
            <p>Organize your library by adding new books with detailed information.</p>
        </a>
    </div>
    <div class="feature">
        <a href="list_books.php" class="feature-link">
            <h3>View All Books</h3>
            <p>Keep track of all the books in your library in a simple interface.</p>
        </a>
    </div>
    <div class="feature">
        <a href="search_books.php" class="feature-link">
            <h3>Search Books</h3>
            <p>Quickly find books using keywords or categories.</p>
        </a>
    </div>
</div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
