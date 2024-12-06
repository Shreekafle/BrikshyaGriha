<?php
// Enable error reporting during development (optional, remove in production)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servername = "localhost"; // Database host
$username = "root";        // Database username
$password = "";            // Database password
$dbname = "brikshya_griha"; // Database name

try {
    // Create a new MySQLi connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8mb4"); // Optional: Set character encoding to avoid issues with special characters
} catch (Exception $e) {
    // Catch and display errors (during development only)
    die("Database connection error: " . $e->getMessage());
}

// Prevent direct access to this file
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    http_response_code(403);
    die("Access denied.");
}
?>
