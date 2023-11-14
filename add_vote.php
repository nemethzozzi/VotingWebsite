<?php
// Start a session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit;
}

// Database connection setup (replace with your database credentials)
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $vote_id = $_POST['vote_id'];
    $selected_participant = $_POST['selected_participant'];

    // Check if the vote exists and is still open
    $checkVoteSql = "SELECT * FROM szavazas WHERE `Szavazas kod` = ? AND `Zarul` >= CURRENT_DATE";
    $stmtCheckVote = $conn->prepare($checkVoteSql);
    $stmtCheckVote->bind_param("i", $vote_id);
    $stmtCheckVote->execute();
    $resultVote = $stmtCheckVote->get_result();

    if ($resultVote->num_rows > 0) {
        $vote = $resultVote->fetch_assoc();
        // Check if the selected participant is valid
        $participants = explode(',', $vote['Jeloltek']);

        // Fetch participants for this specific vote from add_participant.php
        $addParticipantQuery = "SELECT `Nev` FROM jelolt WHERE `Szavazas kod` = ?";
        $stmtAddParticipant = $conn->prepare($addParticipantQuery);
        $stmtAddParticipant->bind_param("i", $vote_id);
        $stmtAddParticipant->execute();
        $participantsResult = $stmtAddParticipant->get_result();
        $stmtAddParticipant->close();

        // Fetch participants for this specific vote from add_new_participant.php
        $addNewParticipantQuery = "SELECT `Nev` FROM jelolt WHERE `Szavazas kod` = ?";
        $stmtAddNewParticipant = $conn->prepare($addNewParticipantQuery);
        $stmtAddNewParticipant->bind_param("i", $vote_id);
        $stmtAddNewParticipant->execute();
        $newParticipantsResult = $stmtAddNewParticipant->get_result();
        $stmtAddNewParticipant->close();

        $allParticipants = array();
        while ($participantRow = $participantsResult->fetch_assoc()) {
            $allParticipants[] = $participantRow['Nev'];
        }
        while ($newParticipantRow = $newParticipantsResult->fetch_assoc()) {
            $allParticipants[] = $newParticipantRow['Nev'];
        }

        if (in_array($selected_participant, $allParticipants)) {
            // Check if the user has already voted for this vote
            $user_email = $_SESSION['email'];

            // Fetch the username based on the user's email
            $fetchUsernameSql = "SELECT Felhasznalonev FROM felhasznalo WHERE Email = ?";
            $stmtFetchUsername = $conn->prepare($fetchUsernameSql);
            $stmtFetchUsername->bind_param("s", $user_email);
            $stmtFetchUsername->execute();
            $stmtFetchUsername->bind_result($user_username);
            $stmtFetchUsername->fetch();
            $stmtFetchUsername->close();

            // Check if the user has already voted for this vote
            $checkUserVoteSql = "SELECT * FROM szavazat WHERE `Melyik szavazas` = ? AND `Email` = ?";
            $stmtCheckUserVote = $conn->prepare($checkUserVoteSql);
            $stmtCheckUserVote->bind_param("is", $vote_id, $user_email);
            $stmtCheckUserVote->execute();
            $resultUserVote = $stmtCheckUserVote->get_result();

            if ($resultUserVote->num_rows > 0) {
                echo "You have already voted for this vote.";
            } else {
                // Insert the vote into the database with the 'Felhasznalonev' field
                $insertVoteSql = "INSERT INTO szavazat (`Melyik szavazas`, `Melyik jeloltre`, `Email`, `Felhasznalonev`, `Szavazas kod`, `Idopont`) VALUES (?, ?, ?, ?, ?, CURRENT_DATE)";
                $stmtInsertVote = $conn->prepare($insertVoteSql);
                $stmtInsertVote->bind_param("isssi", $vote_id, $selected_participant, $user_email, $user_username, $vote_id);
                if ($stmtInsertVote->execute()) {
                    echo "Your vote has been recorded.";
                } else {
                    echo "Failed to record your vote.";
                }
                $stmtInsertVote->close();
            }
            $stmtCheckUserVote->close();
        } else {
            echo "Invalid participant selection.";
        }
    } else {
        echo "The vote is either closed or doesn't exist.";
    }

    // Close all prepared statements
    $stmtCheckVote->close();
}

// Close the database connection
$conn->close();
echo '<script type="text/javascript">
setTimeout(function() {
    window.location = "homepage.php";
}, 3000);
</script>';
?>
