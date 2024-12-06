<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['login_error'] = "Invalid email format.";
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
            $_SESSION['login_error'] = "Incorrect password.";
        }
    } else {
        $_SESSION['login_error'] = "Email not found.";
    }

    $stmt->close();
    $conn->close();

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
