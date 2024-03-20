<?php

// Include database connection (replace with your connection details)
require_once('db_connect.php');

// Initialize variables and error flag
$username = "";
$email = "";
$password = "";
$signup_successful = false;
$error_message = "";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // Collect form data
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  // Basic validation (replace with more robust validation)
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "Please fill out all required fields.";
  } else if (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Please enter a valid email address.";
  } else {

    // Hash password for security (using bcrypt)
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Prepare SQL statement (consider using prepared statements for better security)
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
      $signup_successful = true;
    } else {
      $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
  }

  // Close the connection
  $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UNMASKED: Signup Response</title>
</head>
<body>
  <?php if ($signup_successful) : ?>
    <h1>Signup Successful!</h1>
    <p>You can now log in to your UNMASKED account.</p>
    <a href="login.html">Login Page</a>
  <?php else : ?>
    <h1>Signup Failed</h1>
    <?php if (!empty($error_message)) : ?>
      <p><?php echo $error_message; ?></p>
    <?php else : ?>
      <p>An error occurred during signup. Please try again later.</p>
    <?php endif; ?>
  <?php endif; ?>
</body>
</html>
