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

        // Remove the selected participant from the vote
        $removeParticipantQuery = "DELETE FROM jelolt WHERE `Szavazas kod` = ? AND `Nev` = ?";
        if ($stmt = $conn->prepare($removeParticipantQuery)) {
            $stmt->bind_param("is", $vote_id, $selected_participant);
            $stmt->execute();
            $stmt->close();
            
            // Redirect back to the homepage or any other appropriate page
            header("Location: homepage.php");
            exit();
        } else {
            echo "Error removing the participant from the vote.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Withdraw Participant</title>
</head>
<body>
    <h2>Withdraw Participant from Vote</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="vote_id">Vote ID:</label>
        <input type="text" name="vote_id" required><br><br>

        <label for="selected_participant">Select Participant to Withdraw:</label>
        <input type="text" name="selected_participant" required><br><br>

        <input type="submit" value="Withdraw Participant">
    </form>
</body>
</html>
