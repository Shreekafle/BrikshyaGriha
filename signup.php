<?php
session_start();
include 'config.php'; // Ensure your config.php has proper DB connection setup

$_SESSION['errors'] = array();
$_SESSION['success_message'] = null; // Reset success message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $first_name = $_POST['first_name'] ?? null;
    $last_name = $_POST['last_name'] ?? null;
    $username = $_POST['username'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $confirm_password = $_POST['confirm_password'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $address = $_POST['address'] ?? null;

    // Input validation
    if (empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($password) || empty($phone) || empty($address)) {
        $_SESSION['errors'][] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errors'][] = "Invalid email format.";
    }

    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[\W_]/', $password)) {
        $_SESSION['errors'][] = "Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.";
    }

    if ($password !== $confirm_password) {
        $_SESSION['errors'][] = "Passwords do not match.";
    }

    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $_SESSION['errors'][] = "Invalid phone number format. It should be 10 digits.";
    }

    // Check if username or email already exists
    if (empty($_SESSION['errors'])) {
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['errors'][] = "Username or email already exists.";
        } else {
            // Insert new user into the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (first_name, last_name, username, email, password, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $first_name, $last_name, $username, $email, $hashed_password, $phone, $address);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'You have successfully registered. Now you can log in.';
                header('Location: index.php?success=true');
                exit;
            } else {
                $_SESSION['errors'][] = "Error: " . $stmt->error;
            }
        }

        $stmt->close();
    }

    $conn->close();
    header('Location: index.php');
    exit;
}
?>
