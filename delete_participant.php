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
    header('Location: login.php');
    exit;
}

// Check if the vote_id is set
if (!isset($_GET['vote_id'])) {
    echo 'Vote ID is missing.';
    exit;
}

$vote_id = $_GET['vote_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a participant is selected for deletion
    if (isset($_POST['participant_name'])) {
        $participant_name = $_POST['participant_name'];

        // Prepare and execute the SQL query to delete the selected participant
        $delete_query = "DELETE FROM jelolt WHERE `Szavazas kod` = ? AND `Nev` = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("is", $vote_id, $participant_name);

        if ($stmt->execute()) {
            $successMessage = "Participant '$participant_name' has been successfully deleted.";
        } else {
            echo "Error deleting participant: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Delete Participant</title>
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
    <form method="post">
    <h2>Delete Participant</h2>
        <input type="hidden" name="vote_id" value="<?php echo $vote_id; ?>">
        Participant Name: 
        <select name="participant_name">
            <?php
            // Fetch participants for the selected vote
            $participants_query = "SELECT `Nev` FROM jelolt WHERE `Szavazas kod` = ?";
            $stmt = $conn->prepare($participants_query);
            $stmt->bind_param("i", $vote_id);
            $stmt->execute();
            $participants_result = $stmt->get_result();
            $stmt->close();

            while ($participant = $participants_result->fetch_assoc()) {
                echo '<option value="' . $participant['Nev'] . '">' . $participant['Nev'] . '</option>';
            }
            ?>
        </select>
        <br><br>
        <input type="submit" value="Delete Participant">
    </form>
    <p><a href="homepage.php">Go back to homepage</a></p>
</body>
</html>
