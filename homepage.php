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
        
        // Display a list of available votes
        $query = "SELECT * FROM szavazas";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            echo '<h2>Available Votes:</h2>';
            while ($row = $result->fetch_assoc()) {
                echo '<h3>' . $row['Megnevezes'] . '</h3>';
                echo '<p>' . $row['Leiras'] . '</p>';
                echo '<p>Jeloltek: ' . $row['Jeloltek'] . '</p>';
                echo '<p>Indul: ' . $row['Indul'] . '</p>';
                echo '<p>Zarul: ' . $row['Zarul'] . '</p>';
                
                // Display voting results if the vote is closed
                $currentDate = date('Y-m-d');
                if ($currentDate > $row['Zarul']) {
                    echo '<h3>Voting Results:</h3>';
                    // Fetch and display voting results here
                }

                // Add new participant form
                echo '<h3>Add New Participant:</h3>';
                echo '<form action="add_participant.php" method="post">';
                echo '<input type="hidden" name="vote_id" value="' . $row['Szavazas kod'] . '">';
                echo '<input type="submit" value="Add Participant">';
                echo '</form>';
            }
        } else {
            echo 'No available votes.';
        }
    } else {
        // User is not logged in, display links to login.php and signup.php
        echo '<li><a href="login.php">Login</a></li>';
        echo '<li><a href="signup.php">Signup</a></li>';
        
        // Display a list of available votes for non-logged users
        $query = "SELECT * FROM szavazas";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            echo '<h2>Available Votes:</h2>';
            while ($row = $result->fetch_assoc()) {
                echo '<h3>' . $row['Megnevezes'] . '</h3>';
                echo '<p>' . $row['Leiras'] . '</p>';
                echo '<p>Jeloltek: ' . $row['Jeloltek'] . '</p>';
                echo '<p>Indul: ' . $row['Indul'] . '</p>';
                echo '<p>Zarul: ' . $row['Zarul'] . '</p>';
                
                // Display voting results if the vote is closed
                $currentDate = date('Y-m-d');
                if ($currentDate > $row['Zarul']) {
                    echo '<h3>Voting Results:</h3>';
                    // Fetch and display voting results here
                }
            }
        } else {
            echo 'No available votes.';
        }
    }
    ?>
</body>
</html>
