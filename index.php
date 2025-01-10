<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 1rem 0;
            text-align: center;
        }
        nav {
            background-color: #333;
            overflow: hidden;
        }
        nav a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 0.75rem 1rem;
            text-decoration: none;
        }
        nav a:hover {
            background-color: #575757;
        }
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
    <header>
        <h1>Book Management System</h1>
        <p>Your one-stop solution to manage books efficiently</p>
    </header>
    <nav>
        <a href="index.php">Home</a>
        <a href="add_book.php">Add Book</a>
        <a href="view_books.php">View Books</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a href="login.php">Login</a>
    </nav>
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
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Book Management System. All rights reserved.</p>
    </footer>
</body>
</html>
