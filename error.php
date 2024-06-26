<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
</head>
<body>
    <h1>Something went wrong</h1>
    <p><?php echo isset($_SESSION['error_message']) ? htmlspecialchars($_SESSION['error_message']) : 'An unexpected error occurred. Please try again later.'; ?></p>
    <a href="home.php">Go back to home</a>
</body>
</html>

<?php
// Clear the error message from the session
unset($_SESSION['error_message']);
?>