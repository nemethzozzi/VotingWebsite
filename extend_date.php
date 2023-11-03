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

// Initialize the success and error messages
$successMessage = '';
$errorMessage = '';

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

            // Update the "Zarul" date for the selected vote in the database
            $updateQuery = "UPDATE szavazas SET Zarul = ? WHERE `Szavazas kod` = ?";
            if ($stmt = $conn->prepare($updateQuery)) {
                $stmt->bind_param("si", $newZarulDate, $voteID);
                if ($stmt->execute()) {
                    $successMessage = "Date extended successfully.";
                } else {
                    $errorMessage = "Error extending date: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    } else {
        $errorMessage = "You do not have permission to extend the date.";
    }
} else {
    $errorMessage = "Please log in to extend the date.";
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
    if (!empty($successMessage)) {
        echo '<p style="color: green;">' . $successMessage . '</p>';
        echo '<script>
        setTimeout(function(){
            window.location.href = "homepage.php";
        }, 2000);
        </script>';
    }

    if (!empty($errorMessage)) {
        echo '<p style="color: red;">' . $errorMessage . '</p>';
    }
    ?>

    <?php
    if ($canExtendDate) {
        echo '<h2>Extend Date</h2>';
        echo '<form method="post" action="extend_date.php?vote_id=' . $_GET['vote_id'] . '">';
        echo 'New Ends Date: <input type="date" name="new_zarul_date" required>';
        echo '<input type="submit" value="Extend Date">';
        echo '</form>';
    }
    ?>

    <p><a href="homepage.php">Go back to homepage</a></p>
</body>
</html>
