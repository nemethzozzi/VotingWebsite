<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "szavazatszamlalo";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vote_id = $_POST['vote_id'];
    $participant_name = $_POST['participant_name'];
    $new_name = $_POST['new_name'];
    $new_birthdate = $_POST['new_birthdate'];
    $new_occupation = $_POST['new_occupation'];
    $new_program = $_POST['new_program'];

    // Prepare and execute SQL statement to update the participant data
    $sql = "UPDATE jelolt SET `Nev` = ?, `Szuletesi datum` = ?, `Foglalkozas` = ?, `Program` = ? WHERE `Szavazas kod` = ? AND `Nev` = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssis", $new_name, $new_birthdate, $new_occupation, $new_program, $vote_id, $participant_name);
        if ($stmt->execute()) {
            echo "Participant data updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error in preparing the SQL statement: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Participant Data</title>
</head>
<body>

<h2>Update Participant Data</h2>

<form method="post" action="update_vote.php">
    Select a Vote:
    <select name="vote_id" required>
        <?php
        // Fetch available votes for the current user
        $user_email = $_SESSION['email'];
        $query = "SELECT * FROM szavazas WHERE `Email` = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['Szavazas kod'] . '">' . $row['Megnevezes'] . '</option>';
        }
        $stmt->close();
        ?>
    </select>
    <br><br>

    Participant Name:
    <select name="participant_name" required>
        <?php
        // Fetch available votes for the current user
        $user_email = $_SESSION['email'];
        $query = "SELECT * FROM szavazas WHERE `Email` = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();

        foreach ($participants as $participant) {
            echo '<option value="' . $participant . '">' . $participant . '</option>';
            $addParticipantQuery = "SELECT `Nev` FROM jelolt WHERE `Szavazas kod` = ?";
            if ($stmt = $conn->prepare($addParticipantQuery)) {
                $stmt->bind_param("i", $row['Szavazas kod']);
                $stmt->execute();
                $participantsResult = $stmt->get_result();
                $stmt->close();
            }
            
            while ($participantRow = $participantsResult->fetch_assoc()) {
                echo '<option value="' . $participantRow['Nev'] . '">' . $participantRow['Nev'] . '</option>';
            }
        }
        $stmt->close();
        ?>
    </select>
    <br><br>

    New Name:
    <input type="text" name="new_name" required>
    <br><br>

    New Birthdate:
    <input type="date" name="new_birthdate" required>
    <br><br>

    New Occupation:
    <input type="text" name="new_occupation" required>
    <br><br>

    New Program:
    <input type="text" name="new_program" required>
    <br><br>

    <input type="submit" value="Update Participant Data">
</form>

<p><a href="homepage.php">Go back to homepage</a></p>

</body>
</html>
