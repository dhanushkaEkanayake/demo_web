<?php
// Starting the session
session_start();

// Connect to the database
$mysqli = new mysqli('localhost', 'root', 'root', 'users');

// Check for connection error
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the name, email address, and password from the form
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate the form data
    if (empty($name) || empty($email) || empty($password)) {
        echo 'Please enter your name, email address, and password.';
        exit();
    }

    // Check if the user account already exists
    $sql = 'SELECT * FROM user_details WHERE email = ?';
    $stmt = $mysqli->prepare($sql);

    // Check for prepare error
    if (!$stmt) {
        die('SQL prepare failed: ' . $mysqli->error);
    }

    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // User account already exists
        echo 'Email address already in use.';
        exit();
    } else {
        // Create a new user account
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $sql = 'INSERT INTO user_details (name, email, password) VALUES (?, ?, ?)';
        $stmt = $mysqli->prepare($sql);

        // Check for prepare error
        if (!$stmt) {
            die('SQL prepare failed: ' . $mysqli->error);
        }

        $stmt->bind_param('sss', $name, $email, $passwordHash);
        
        if ($stmt->execute()) {
          $_SESSION['user_id'] = $mysqli->insert_id;
          header('Location: signin.html'); // Redirecting to signin.php
          exit();
      } else {
          echo "Error: " . $stmt->error;
          exit();
      }
        }
    }
  


?>
