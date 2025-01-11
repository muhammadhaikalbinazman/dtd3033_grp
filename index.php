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
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }
        .feature {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 1rem;
            padding: 1rem;
            width: 250px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <main>
        <h1>Welcome, <?php echo htmlspecialchars($storedUsername); ?>!</h1>
        <div class="features">
            <!-- Feature content here -->
        </div>
    </main>
</body>
</html>
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
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }
        .feature {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 1rem;
            padding: 1rem;
            width: 250px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1rem 0;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<?php 
  include 'header.php';
  include 'menu.php';
?>
    <main>
    <h2>Welcome to the Book Management System</h2>
    <p>Manage your library, track book details, and much more.</p>
        <div class="features">
            <div class="feature">
                <h3>Add New Books</h3>
                <p>Organize your library by adding new books with detailed information.</p>
            </div>
            <div class="feature">
                <h3>View All Books</h3>
                <p>Keep track of all the books in your library in a simple interface.</p>
            </div>
            <div class="feature">
                <h3>Search Books</h3>
                <p>Quickly find books using keywords or categories.</p>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
