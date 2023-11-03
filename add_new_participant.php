<?php
// Database connection setup
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

// Get the user's email from the session
$userEmail = $_SESSION['email'];

// Check if the vote ID is provided in the URL
if (isset($_GET['vote_id'])) {
    $voteID = $_GET['vote_id'];
} else {
    echo "Vote ID is missing.";
    exit;
}

// ...
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the new participant's data from the form
    $newParticipantName = $_POST['new_participant_name'];
    $birthdate = $_POST['birthdate'];
    $occupation = $_POST['occupation'];
    $program = $_POST['program'];

    // Get the user's email from the session
    $userEmail = $_SESSION['email'];

    // Insert the new participant into the database, associating them with the specific vote
    $insertQuery = "INSERT INTO jelolt (`Nev`, `Szavazas kod`, `Szuletesi datum`, `Foglalkozas`, `Program`, `Email`) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($insertQuery)) {
        $stmt->bind_param("sissss", $newParticipantName, $voteID, $birthdate, $occupation, $program, $userEmail);
        if ($stmt->execute()) {
            $successMessage = "New participant added successfully.";
        } else {
            echo "Error adding new participant: " . $stmt->error;
        }
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
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
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Add New Participant</title>
</head>
<body>
    <form method="post" action="add_new_participant.php?vote_id=<?php echo $voteID; ?>">
    <h2>Add New Participant</h2>
        New Participant Name: <input type="text" name="new_participant_name" required><br><br>
        Birthdate: <input type="date" name="birthdate" required><br><br>
        Occupation: <input type="text" name="occupation" required><br><br>
        Program: <input type="text" name="program" required><br><br>
        <input type="submit" value="Add Participant">
    </form>
    <p><a href="homepage.php">Go back to homepage</a></p>
</body>
</html>
