<?php
// Start session
session_start();

// Include database configuration
include 'dbconfig.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to send JSON response
function sendResponse($success, $message) {
    $response = [
        'success' => $success,
        'message' => $message
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get and sanitize form input values
        $username = trim($conn->real_escape_string($_POST['username']));
        $email = trim($conn->real_escape_string($_POST['email']));
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate empty fields
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            sendResponse(false, "All fields are required.");
        }

        // Validate username length
        if (strlen($username) < 3 || strlen($username) > 50) {
            sendResponse(false, "Username must be between 3 and 50 characters.");
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            sendResponse(false, "Invalid email format.");
        }

        // Validate password length and complexity
        if (strlen($password) < 8) {
            sendResponse(false, "Password must be at least 8 characters long.");
        }

        // Check if password contains at least one number, one uppercase and one lowercase letter
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/", $password)) {
            sendResponse(false, "Password must contain at least one uppercase letter, one lowercase letter, and one number.");
        }

        // Check if passwords match
        if ($password !== $confirm_password) {
            sendResponse(false, "Passwords do not match.");
        }

        // HERE IS WHERE WE ADD THE SPECIFIC ERROR HANDLING FOR USERNAME AND EMAIL
        
        // Check if username exists
        $check_username = "SELECT user_id FROM users WHERE name = ?";
        $stmt = $conn->prepare($check_username);
        if (!$stmt) {
            throw new Exception("Database prepare error: " . $conn->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if($stmt->get_result()->num_rows > 0) {
            $stmt->close();
            sendResponse(false, "Username '" . htmlspecialchars($username) . "' is already taken. Please choose another.");
        }
        $stmt->close();

        // Check if email exists
        $check_email = "SELECT user_id FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_email);
        if (!$stmt) {
            throw new Exception("Database prepare error: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if($stmt->get_result()->num_rows > 0) {
            $stmt->close();
            sendResponse(false, "Email address '" . htmlspecialchars($email) . "' is already registered.");
        }
        $stmt->close();

        // If we get here, we can proceed with user creation
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $insert_query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')";
        $insert_stmt = $conn->prepare($insert_query);
        
        if (!$insert_stmt) {
            throw new Exception("Database prepare error: " . $conn->error);
        }

        $insert_stmt->bind_param("sss", $username, $email, $hashed_password);
        
        if ($insert_stmt->execute()) {
            $insert_stmt->close();
            
            // Set session variables for the new user
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'user';
            
            sendResponse(true, "Registration successful!");
        } else {
            throw new Exception("Error inserting user: " . $insert_stmt->error);
        }

    } catch (Exception $e) {
        // Log the error (in a production environment)
        error_log("Registration error: " . $e->getMessage());
        sendResponse(false, "An error occurred during registration: " . $e->getMessage());
    } finally {
        // Close the database connection
        if ($conn) {
            $conn->close();
        }
    }
} else {
    // If not a POST request, redirect to the registration form
    header("Location: registerform.php");
    exit;
}
?>