<?php
// Database connection setup (same as in your existing code)
$servername = "localhost";
$username = "root";
$password = "";
$database = "szavazatszamlalo";
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start the session (if not already started)
session_start();

// Check if the user is logged in and has the permission to extend the Zarul date
if (isset($_SESSION['email'])) {
    // Check if the user can extend the "Zarul" date (implement your permission logic here)
    
    $canExtendDate = true; // Implement your permission logic here

    if ($canExtendDate) { // Replace with your permission logic
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get the new "Zarul" date from the form
            $newZarulDate = $_POST['new_zarul_date'];

            // Get the vote ID from the URL
            $voteID = $_GET['vote_id'];

            // Update the "Zarul" date for the selected vote in the database
            $updateQuery = "UPDATE szavazas SET Zarul = ? WHERE `Szavazas kod` = ?";
            if ($stmt = $conn->prepare($updateQuery)) {
                $stmt->bind_param("si", $newZarulDate, $voteID);
                if ($stmt->execute()) {
                    echo "Date extended successfully.";
                } else {
                    echo "Error extending date: " . $stmt->error;
                }
                $stmt->close();
            }

            // Redirect to homepage.php after displaying the message for 3 seconds
            echo '<script>
                setTimeout(function(){
                    window.location.href = "homepage.php";
                }, 3000);
            </script>';
        }
    } else {
        echo "You do not have permission to extend the date.";
    }
} else {
    echo "Please log in to extend the date.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Extend Zarul Date</title>
</head>
<body>
    <?php
    if ($canExtendDate) {
        echo '<h2>Extend Date</h2>';
        echo '<form method="post" action="extend_date.php?vote_id=' . $_GET['vote_id'] . '">';
        echo 'New Zarul Date: <input type="date" name="new_zarul_date" required>';
        echo '<input type="submit" value="Extend Date">';
        echo '</form>';
    }
    ?>
    <p><a href="homepage.php">Go back to homepage</a></p>
</body>
</html>
