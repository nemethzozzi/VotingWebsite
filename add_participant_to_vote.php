<?php
// Database connection setup (same as in your existing code)
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

// Initialize variables
$successMessage = "";
$canAddParticipant = false;

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    // Check if the user has permission to add a participant (implement your permission logic here)
    $canAddParticipant = true; // Implement your permission logic here

    if ($canAddParticipant && $_SERVER["REQUEST_METHOD"] == "POST") {
        $voteID = $_GET['vote_id'];
        $selectedParticipant = $_POST['selected_participant'];

        if ($selectedParticipant != 'new') {
            // The user selected an existing participant
            // Check if the participant is not already assigned to the vote
            $checkAssignmentQuery = "SELECT * FROM jelolt WHERE `Szavazas kod` = ? AND `Nev` = ?";
            if ($stmt = $conn->prepare($checkAssignmentQuery)) {
                $stmt->bind_param("is", $voteID, $selectedParticipant);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                if ($result->num_rows > 0) {
                    $successMessage = "This participant is already assigned to the vote.";
                } else {
                    // Insert the selected participant into the database
                    $insertParticipantQuery = "INSERT INTO jelolt (`Nev`, `Szuletesi datum`, `Foglalkozas`, `Program`, `Szavazas kod`, `Email`) SELECT `Nev`, `Szuletesi datum`, `Foglalkozas`, `Program`, ?, ? FROM jelolt WHERE `Nev` = ?";
                    if ($stmt = $conn->prepare($insertParticipantQuery)) {
                        $stmt->bind_param("iss", $voteID, $_SESSION['email'], $selectedParticipant);
                        if ($stmt->execute()) {
                            $successMessage = "Participant added successfully.";
                        } else {
                            echo "Error adding participant: " . $stmt->error;
                        }
                        $stmt->close();
                    }
                }
            }
        } else {
            // The user wants to add a new participant
            // Retrieve form values correctly
            $participantName = $_POST['participant_name'];
            $participantBirthdate = $_POST['participant_birthdate'];
            $participantOccupation = $_POST['participant_occupation'];
            $participantProgram = $_POST['participant_program'];

            // Insert the new participant into the database
            $insertNewParticipantQuery = "INSERT INTO jelolt (`Nev`, `Szuletesi datum`, `Foglalkozas`, `Program`, `Szavazas kod`, `Email`) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = $conn->prepare($insertNewParticipantQuery)) {
                $stmt->bind_param("ssssis", $participantName, $participantBirthdate, $participantOccupation, $participantProgram, $voteID, $_SESSION['email']);
                if ($stmt->execute()) {
                    $successMessage = "New participant added successfully.";
                } else {
                    echo "Error adding new participant: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Add Participant to Vote</title>
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
    
    if ($canAddParticipant) {
        echo '<form method="post" action="add_participant_to_vote.php?vote_id=' . $_GET['vote_id'] . '">';
        echo '<h2>Add Participant to Vote</h2>';
        
        // Dropdown list of participants not assigned to the vote
        echo 'Select a Participant: <select name="selected_participant">';
        echo '<option value="new">Add New Participant</option>'; // Allow the user to add a new participant
        // Fetch participants not assigned to the vote and populate the dropdown
        $getParticipantsQuery = "SELECT `Nev` FROM jelolt WHERE `Nev` NOT IN (SELECT `Nev` FROM jelolt WHERE `Szavazas kod` = ?)";
        if ($stmt = $conn->prepare($getParticipantsQuery)) {
            $stmt->bind_param("i", $_GET['vote_id']);
            $stmt->execute();
            $participantsResult = $stmt->get_result();
            $stmt->close();
            while ($participantRow = $participantsResult->fetch_assoc()) {
                echo '<option value="' . $participantRow['Nev'] . '">' . $participantRow['Nev'] . '</option>';
            }
        }
        echo '</select><br>';
        
        echo '<input type="submit" value="Add Participant">';
        echo '</form>';
    } else {
        echo "You do not have permission to add a participant.";
    }
    ?>
    <p><a href="homepage.php">Go back to homepage</a></p>
</body>
</html>
