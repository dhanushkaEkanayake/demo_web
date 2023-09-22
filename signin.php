<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();  // Start the session at the beginning

// Connect to the database
$mysqli = new mysqli('localhost', 'root', 'root', 'users');

// Check for connection error
if ($mysqli->connect_error) {
    die('Database connection failed: ' . $mysqli->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the email address and password from the form
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate the form data
    if (empty($email) || empty($password)) {
        die('Please enter your email address and password.');
    }

    // Check if the user account exists
    $sql = 'SELECT * FROM user_details WHERE email = ?';
    $stmt = $mysqli->prepare($sql);

    // Check for prepare error
    if (!$stmt) {
        die('SQL prepare failed: ' . $mysqli->error);
    }

    $sql = 'SELECT * FROM user_details WHERE email = ?';
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
         $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // User account exists, check the password
        //if ($password === $row['password']) {  // Direct comparison since passwords are in plain text
            // Password is correct, log the user in
            $_SESSION['user_id'] = $row['id'];
            header('Location: signon.html');
            exit();
        } else {
            // Password is incorrect
            die('Invalid password.');
        }
    } else {
        // User account does not exist
        die('No account found with the provided email address.');
    }
  
  // Closing brace for the POST check
?>