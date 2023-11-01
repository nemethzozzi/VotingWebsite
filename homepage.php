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
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Voting Website</title>
</head>
<body>
    <?php
    // Check if the user is logged in or not
    if (isset($_SESSION['email'])) {
        // User is logged in, display a link to logout.php
        echo '<p>Hello ' . $_SESSION['username'] . '</p>';
        echo '<a href="logout.php">Logout</a><br>';
        echo '<a href="create_vote.php">Create a New Vote</a><br>';

        // Display a list of available votes
        $query = "SELECT * FROM szavazas";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo '<h2>Available Votes:</h2>';
            while ($row = $result->fetch_assoc()) {
                echo '<h3>' . $row['Megnevezes'] . '</h3>';
                echo '<p>' . $row['Leiras'] . '</p<br>';
                echo '<p>Indul: ' . $row['Indul'] . '</p>';
                echo '<p>Zarul: ' . $row['Zarul'] . '</p>';

                // Display voting results if the vote is closed
                $currentDate = date('Y-m-d');
                if ($currentDate > $row['Zarul']) {
                    echo '<h3>Voting Results:</h3>';
                    // Fetch and display voting results here
                } else {
                    // Voting form
                    echo '<h3>Vote for a Participant:</h3>';
                    echo '<form action="add_vote.php" method="post">';
                    echo '<input type="hidden" name="vote_id" value="' . $row['Szavazas kod'] . '">';
                    echo '<select name="selected_participant">';
                    // Create options for participants here
                    $participants = explode(',', $row['Jeloltek']);
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
                    echo '</select>';
                    echo '<input type="submit" value="Vote">';
                    echo '</form>';

                    echo '<a href="add_new_participant.php?vote_id=' . $row['Szavazas kod'] . '">Add New Participant</a>';
                    echo '<a href="extend_date.php?vote_id=' . $row['Szavazas kod'] . '">Extend Date</a>';
                    echo '<a href="delete_participant.php?vote_id=' . $row['Szavazas kod'] . '">Delete Participant</a>';
                    echo '<a href="update_participant.php?vote_id=' . $row['Szavazas kod'] . '">Update Participant Data</a>';
                    echo '<a href="withdraw_participant.php?vote_id=' . $row['Szavazas kod'] . '">Withdraw Participant</a>';  
                    
                }
            }
        } else {
            echo 'No available votes.';
        }
    } else {
        // User is not logged in, display links to login.php and signup.php
        echo '<li><a href="login.php">Login</a></li>';
        echo '<li><a href="signup.php">Signup</a></li>';
        
        // Display a list of available votes for non-logged users
        $query = "SELECT * FROM szavazas";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            echo '<h2>Available Votes:</h2>';
            while ($row = $result->fetch_assoc()) {
                echo '<h3>' . $row['Megnevezes'] . '</h3>';
                echo '<p>' . $row['Leiras'] . '</p><br>';
                echo '<p>Indul: ' . $row['Indul'] . '</p>';
                echo '<p>Zarul: ' . $row['Zarul'] . '</p';
                
                // Display voting results if the vote is closed
                $currentDate = date('Y-m-d');
                if ($currentDate > $row['Zarul']) {
                    echo '<h3>Voting Results:</h3>';
                    // Fetch and display voting results here
                }
            }
        } else {
            echo 'No available votes.';
        }
    }
    ?>
</body>
</html>
