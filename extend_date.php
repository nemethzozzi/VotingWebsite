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

// Check if the user is logged in and has the permission to extend the Zarul date
if (isset($_SESSION['email'])) {
    // Check if the user can extend the "Zarul" date (implement your permission logic here)
    $canExtendDate = true; // Implement your permission logic here

    if ($canExtendDate) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get the new "Zarul" date from the form
            $newZarulDate = $_POST['new_zarul_date'];

            // Get the vote ID from the URL
            $voteID = $_GET['vote_id'];

            // Check if the new "Zarul" date is greater than the current "Ends" date
            $checkEndDateQuery = "SELECT Zarul FROM szavazas WHERE `Szavazas kod` = ?";
            if ($stmt = $conn->prepare($checkEndDateQuery)) {
                $stmt->bind_param("i", $voteID);
                $stmt->execute();
                $stmt->bind_result($currentEndDate);
                $stmt->fetch();
                $stmt->close();

                if (strtotime($newZarulDate) > strtotime($currentEndDate)) {
                    // Update the "Zarul" date for the selected vote in the database
                    $updateQuery = "UPDATE szavazas SET Zarul = ? WHERE `Szavazas kod` = ?";
                    if ($stmt = $conn->prepare($updateQuery)) {
                        $stmt->bind_param("si", $newZarulDate, $voteID);
                        if ($stmt->execute()) {
                            $successMessage =  "Date extended successfully.";
                        } else {
                            echo "Error extending date: " . $stmt->error;
                        }
                        $stmt->close();
                    }
                } else {
                    echo "The new ends date must be higher than the current Ends date.";
                }
            }
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
    <title>Extend Ends Date</title>
</head>
<body>
    <?php
    if (isset($successMessage)) {
        echo '<p style="color: green;">' . $successMessage . '</p>';
        echo '<script>
        setTimeout(function(){
            window.location.href = "homepage.php";
        }, 2000);
        </script>';
    }
    
    if ($canExtendDate) {
        echo '<form method="post" action="extend_date.php?vote_id=' . $_GET['vote_id'] . '">';
        echo '<h2>Extend Date</h2>';
        echo 'New Ends Date: <input type="date" name="new_zarul_date" required>';
        echo '<input type="submit" value="Extend Date">';
        echo '</form>';
    }
    ?>
    <p><a href="homepage.php">Go back to homepage</a></p>
</body>
</html>
