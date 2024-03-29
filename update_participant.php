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

$updateSuccess = false; // Variable to track the update success

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['vote_id']) && isset($_POST['selected_participant']) && isset($_POST['name']) && isset($_POST['birthdate']) && isset($_POST['occupation']) && isset($_POST['program'])) {
        $vote_id = $_POST['vote_id'];
        $selected_participant = $_POST['selected_participant'];
        $name = $_POST['name'];
        $birthdate = $_POST['birthdate'];
        $occupation = $_POST['occupation'];
        $program = $_POST['program'];

        // Update the selected participant's data for the specific vote
        $updateParticipantQuery = "UPDATE jelolt SET `Nev` = ?, `Szuletesi datum` = ?, `Foglalkozas` = ?, `Program` = ? WHERE `Szavazas kod` = ? AND `Nev` = ?";
        if ($stmt = $conn->prepare($updateParticipantQuery)) {
            $stmt->bind_param("ssssis", $name, $birthdate, $occupation, $program, $vote_id, $selected_participant);
            if ($stmt->execute()) {
                $updateSuccess = true; // Update was successful
            }
            $stmt->close();
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
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Update Participant Data</title>
</head>
<body>
    <?php
        if ($updateSuccess) {
        echo '<p style="color: green;">Participant data updated successfully.</p>';
        echo '<script>
            setTimeout(function(){
                window.location.href = "homepage.php";
            }, 2000);
        </script>';
    }
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <h2>Update Participant Data for Vote</h2>
        <input type="hidden" name="vote_id" value="<?php echo $vote_id; ?>">
        <label for="selected_participant">Select Participant:</label>
        <select name="selected_participant">
            <?php
            foreach ($participants as $participant) {
                echo '<option value="' . $participant . '">' . $participant . '</option>';
            }
            ?>
        </select><br><br>

        <label for="name">Name:</label>
        <input type="text" name="name" required><br><br>

        <label for="birthdate">Birthdate:</label>
        <input type="date" name="birthdate" required><br><br>

        <label for="occupation">Occupation:</label>
        <input type="text" name="occupation" required><br><br>

        <label for="program">Program:</label>
        <input type="text" name="program" required><br><br>

        <input type="submit" value="Update Participant Data">
    </form>

    <p><a href="homepage.php">Go back to homepage</a></p>
</body>
</html>
