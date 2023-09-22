<?php

session_start();

if (isset($_SESSION['user_id'])) {
  // The user is logged in, so display the index page
  echo '<h1>Welcome to the index page!</h1>';
} else {
  // Redirect the user to the login page
  header('Location: login.php');
  exit();
}

?>