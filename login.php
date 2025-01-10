<?php
session_start();
include 'dbconfig.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Query the database to validate credentials
    $query = "SELECT name, password FROM users WHERE name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($storedUsername, $storedHash);
    $stmt->fetch();

    if ($storedUsername && password_verify($password, $storedHash)) {
        $_SESSION['user'] = $storedUsername; // Store username in session
        header("Location: index.php");
        exit();
    }

    $error = "Invalid username or password.";
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .container h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color: #007bff;
        }

        .error-message {
            font-size: 12px;
            color: #ff4d4f;
            margin-top: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        .footer {
            margin-top: 15px;
            font-size: 14px;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="footer">
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </div>
    </div>
</body>
</html>
