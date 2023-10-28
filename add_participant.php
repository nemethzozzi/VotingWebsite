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

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit;
}

// Check if the vote_id and participant_name are provided in the POST request
if (isset($_POST['vote_id']) && isset($_POST['participant_name'])) {
    $voteId = $_POST['vote_id'];
    $participantName = $_POST['participant_name'];

    // Perform input validation and sanitation here if necessary

    // Insert the new participant into the 'jelolt' table
    $insertParticipantSQL = "INSERT INTO jelolt (`Nev`, `Szavazas kod`, `Email`) VALUES (?, ?, ?)";
    $insertParticipantStmt = $conn->prepare($insertParticipantSQL);
    if ($insertParticipantStmt) {
        $insertParticipantStmt->bind_param("sis", $participantName, $voteId, $_SESSION['email']);
        if ($insertParticipantStmt->execute()) {
            // Participant added successfully
            header("Location: homepage.php"); // Redirect to the homepage
        } else {
            // Error occurred while adding the participant
            echo "Error: Unable to add the participant.";
        }
        $insertParticipantStmt->close();
    } else {
        echo "Error: Unable to prepare the SQL statement.";
    }
} else {
    // Redirect to the homepage if vote_id or participant_name is not provided
    header("Location: homepage.php");
}

// Close the database connection
$conn->close();
?>
