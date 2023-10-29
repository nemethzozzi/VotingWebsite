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

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php'); // Redirect to the login page if not logged in
    exit;
}

// Check if the user has permission to update participant data (implement your permission logic here)
$canUpdateParticipant = true; // Implement your permission logic here

if ($canUpdateParticipant) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the updated participant data and vote ID from the form
        $newParticipantName = $_POST['new_participant_name'];
        $newBirthdate = $_POST['new_birthdate'];
        $newOccupation = $_POST['new_occupation'];
        $newProgram = $_POST['new_program'];
        $voteID = $_POST['vote_id'];

        // Update the participant's data in the database
        $updateQuery = "UPDATE jelolt SET `Nev` = ?, `Szuletesi datum` = ?, `Hivatás` = ?, `Program` = ? WHERE `Szavazas kod` = ?";

        if ($stmt = $conn->prepare($updateQuery)) {
            $stmt->bind_param("ssssi", $newParticipantName, $newBirthdate, $newOccupation, $newProgram, $voteID);
            if ($stmt->execute()) {
                echo "Participant data updated successfully.";
            } else {
                echo "Error updating participant data: " . $stmt->error;
            }
            $stmt->close();
        }
    }
} else {
    echo "You do not have permission to update participant data.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Participant Data</title>
</head>
<body>
    <?php
    if ($canUpdateParticipant) {
        // Display the update form
        echo '<h2>Update Participant Data</h2>';
        echo '<form method="post" action="update_vote.php">';
        echo 'Participant Name: <input type="text" name="new_participant_name" required><br><br>';
        echo 'Birthdate: <input type="date" name="new_birthdate" required><br><br>';
        echo 'Occupation: <input type="text" name="new_occupation" required><br><br>';
        echo 'Program: <input type="text" name="new_program" required><br><br>';
        echo '<input type="submit" value="Update Participant Data">';
        echo '</form>';
    }
    ?>

    <p><a href="homepage.php">Go back to homepage</a></p>
</body>
</html>
