<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config.php'; // Include your database connection

if (isset($_SESSION['login_error'])) {
    $loginError = $_SESSION['login_error'];
    unset($_SESSION['login_error']); // Clear the error after displaying it
} else {
    $loginError = '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Regex patterns for validation
    $emailPattern = '/^[a-z0-9._%+-]+@gmail\.com$/'; // Ensure email ends with @gmail.com
    $passwordPattern = '/^(?=.*[A-Z])(?=.*\d)(?=.*[@]).{6,}$/'; // Password requirements

    // Validate email
    if (!preg_match($emailPattern, $email)) {
        $_SESSION['login_error'] = "Invalid email format. Must be a Gmail address.";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Validate password
    if (!preg_match($passwordPattern, $password)) {
        $_SESSION['login_error'] = "Invalid password. Must be at least 6 characters long, contain an uppercase letter, a number, and @.";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Query to find the user
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Login successful, redirect based on role
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];

            if ($user['role'] === 'admin') {
                header('Location: admin_panel.php'); // Redirect admin to admin panel
            } else {
                header('Location: shop.php'); // Redirect regular user to their dashboard
            }
            exit;
        } else {
            // Password is incorrect
            $_SESSION['login_error'] = "Incorrect password.";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    } else {
        // Email not found
        $_SESSION['login_error'] = "Email not found.";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php if ($loginError): ?>
                <div class="alert alert-danger">
                    <?php echo $loginError; ?>
                </div>
            <?php endif; ?>
            <div class="modal-body">
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="toggleLoginPassword">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
