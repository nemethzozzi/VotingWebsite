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

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['vote_id']) && isset($_POST['selected_participant'])) {
        $vote_id = $_POST['vote_id'];
        $selected_participant = $_POST['selected_participant'];

        // Remove the selected participant from the specific vote
        $removeParticipantQuery = "DELETE FROM jelolt WHERE `Szavazas kod` = ? AND `Nev` = ?";
        if ($stmt = $conn->prepare($removeParticipantQuery)) {
            $stmt->bind_param("is", $vote_id, $selected_participant);
            $stmt->execute();
            $stmt->close();
            
            $successMessage = "Participant '$selected_participant' has been succesfully withdrawed";

        } else {
            echo "Error removing the participant from the vote.";
        }
    }
}

if (isset($_GET['vote_id'])) {
    $vote_id = $_GET['vote_id'];

    // Retrieve the participants of the specific vote
    $getParticipantsQuery = "SELECT `Nev` FROM jelolt WHERE `Szavazas kod` = ?";
    if ($stmt = $conn->prepare($getParticipantsQuery)) {
        $stmt->bind_param("i", $vote_id);
        $stmt->execute();
        $participantsResult = $stmt->get_result();
        $stmt->close();

        $participants = array();
        while ($participantRow = $participantsResult->fetch_assoc()) {
            $participants[] = $participantRow['Nev'];
        }
    } else {
        echo "Error retrieving participants for the vote.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Withdraw Participant</title>
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
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <h2>Withdraw Participant from Vote</h2>
        <input type="hidden" name="vote_id" value="<?php echo $vote_id; ?>" readonly><br><br>

        <label for="selected_participant">Select Participant to Withdraw:</label>
        <select name="selected_participant">
            <?php
            foreach ($participants as $participant) {
                echo '<option value="' . $participant . '">' . $participant . '</option>';
            }
            ?>
        </select><br><br>

        <input type="submit" value="Withdraw Participant">
    </form>
    <p><a href="homepage.php">Go back to homepage</a></p>
</body>
</html>
