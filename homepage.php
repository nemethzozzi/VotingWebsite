<?php
// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$database = "szavazatszamlalo";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start the session (if not already started)
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Voting Website</title>
</head>
<body>
    <?php
    // Check if the user is logged in or not
    if (isset($_SESSION['email'])) {
        // User is logged in, display a link to logout.php
        echo '<p>Hello ' . $_SESSION['username'] . '</p>';
        echo '<li><a href="logout.php">Logout</a></li>';

        echo '<a href="create_vote.php">Create a New Vote</a><br>';

    } else {
        // User is not logged in, display links to login.php and signup.php
        echo '<li><a href="login.php">Login</a></li>';
        echo '<li><a href="signup.php">Signup</a></li>';
    }

    
    ?>
</body>
</html>
