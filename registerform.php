<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Registration form container */
        .registration-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: 20px;
        }

        .registration-container h2 {
            margin: 0 0 25px;
            font-size: 28px;
            color: #333333;
            text-align: center;
            font-weight: 600;
        }

        /* Form group styles */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #444;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        /* Button styles */
        .form-group button {
            width: 100%;
            padding: 14px;
            background-color: #4CAF50;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-group button:hover {
            background-color: #45a049;
        }

        /* Footer text */
        .footer-text {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #666666;
        }

        .footer-text a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: 500;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        /* Notification popup styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            transform: translateX(150%);
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification-success {
            background-color: #4CAF50;
        }

        .notification-error {
            background-color: #f44336;
        }

        .notification-icon {
            margin-right: 10px;
            font-size: 18px;
        }

        /* Input validation styles */
        .form-group input:invalid {
            border-color: #f44336;
        }

        .form-group .validation-message {
            color: #f44336;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .form-group input:invalid + .validation-message {
            display: block;
        }

        /* Password strength indicator */
        .password-strength {
            height: 4px;
            background-color: #e1e1e1;
            margin-top: 8px;
            border-radius: 2px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        .strength-weak { background-color: #f44336; width: 33.33%; }
        .strength-medium { background-color: #ffa726; width: 66.66%; }
        .strength-strong { background-color: #4CAF50; width: 100%; }
    </style>
</head>
<body>
    <div class="registration-container">
        <h2>Register</h2>
        <form id="registrationForm" action="register.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
                <div class="validation-message">Username is required</div>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <div class="validation-message">Please enter a valid email address</div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <div class="password-strength">
                    <div class="password-strength-bar"></div>
                </div>
                <div class="validation-message">Password must be at least 8 characters long</div>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                <div class="validation-message">Passwords do not match</div>
            </div>
            <div class="form-group">
                <button type="submit">Register</button>
            </div>
        </form>
        <p class="footer-text">Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <div id="notification" class="notification">
        <span class="notification-icon"></span>
        <span class="notification-message"></span>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const strengthBar = document.querySelector('.password-strength-bar');

            // Password strength checker
            function checkPasswordStrength(password) {
                let strength = 0;
                if (password.length >= 8) strength++;
                if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
                if (password.match(/\d/)) strength++;

                strengthBar.className = 'password-strength-bar';
                if (strength === 1) strengthBar.classList.add('strength-weak');
                else if (strength === 2) strengthBar.classList.add('strength-medium');
                else if (strength === 3) strengthBar.classList.add('strength-strong');
            }

            // Show notification function
            function showNotification(message, type) {
                const notification = document.getElementById('notification');
                const notificationMessage = notification.querySelector('.notification-message');
                const icon = notification.querySelector('.notification-icon');

                notification.className = 'notification';
                notification.classList.add(`notification-${type}`);
                
                icon.innerHTML = type === 'success' ? '✓' : '✕';
                notificationMessage.textContent = message;

                notification.classList.add('show');
                
                setTimeout(() => {
                    notification.classList.remove('show');
                }, 3000);
            }

            // Form submission handler
            form.addEventListener('submit', async function(e) {
    e.preventDefault();

    try {
        const formData = new FormData(form);
        const response = await fetch('register.php', {
            method: 'POST',
            body: formData
        });

        // Parse the JSON response
        const result = await response.json(); // Change from response.text()
        
        if (result.success) {
            showNotification(result.message, 'success');
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 2000);
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    }
});

            // Password strength checker
            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
            });

            // Confirm password validator
            confirmPasswordInput.addEventListener('input', function() {
                if (this.value !== passwordInput.value) {
                    this.setCustomValidity('Passwords do not match');
                } else {
                    this.setCustomValidity('');
                }
            });
        });
    </script>
</body>
</html>