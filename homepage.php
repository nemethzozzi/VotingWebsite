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
    <link rel="stylesheet" type="text/css" href="homepage.css">
    <title>Voting Website</title>
</head>
<body>
<header class="header">
    <div class="greeting">
        <?php
        // Check if the user is logged in or not
        if (isset($_SESSION['email'])) {
            // User is logged in, display a greeting and the username
            echo 'Hello ' . $_SESSION['username'];
        }
        ?>
    </div>
    <div class="links">
        <?php
        if (isset($_SESSION['email'])) {
            // User is logged in, display the Logout link
            echo '<form action="logout.php" method="post" class="logout-form">';
            echo '<button type="submit" class="logout-button">Logout</button>';
            echo '</form>';
        } else {
            // User is not logged in, display links to login.php and signup.php
            echo '<a href="login.php" class="login-signup-container">';
            echo '<button class="login-button">Login</button>';
            echo '</a>';
            
            echo '<a href="signup.php" class="login-signup-container">';
            echo '<button class="signup-button">Signup</button>';
            echo '</a>';


        }
        ?>
    </div>
</header>

<div class="create-vote-container">
    <?php
        if (isset($_SESSION['email'])) {
            echo '</select>';
            echo '<a class="create-vote-button" href="create_vote.php">Create Vote</a>';
            echo '</form>';

        }
    ?>
</div>

<div class="votes-container">
    <?php
    // Check if the user is logged in
    if (isset($_SESSION['email'])) {
        // User is logged in, display available votes
        $query = "SELECT * FROM szavazas";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="vote">';
                echo '<div class="voting">';
                echo '<h3 class="vote-title">' . $row['Megnevezes'] . '</h3>';
                echo '<p class="vote-description">' . $row['Leiras'] . '</p>';
                echo '<p class="vote-start">Starts: ' . $row['Indul'] . '</p>';
                echo '<p class="vote-end">Ends: ' . $row['Zarul'] . '</p>';

                // Voting form
                $currentDate = date('Y-m-d');
                if ($currentDate <= $row['Zarul']) {
                    echo '<h3 class="section-title">Vote for a Participant:</h3>';
                    echo '<form action="add_vote.php" method="post" class="vote-form">';
                    echo '<input type="hidden" name="vote_id" value="' . $row['Szavazas kod'] . '">';
                    echo '<select name="selected_participant" class="participant-select">';

                    $participants = explode(',', $row['Jeloltek']);
                    $existingParticipants = array(); // Create an array to store existing participants

                    // Add participants from the vote's existing list
                    foreach ($participants as $participant) {
                        if (!in_array($participant, $existingParticipants)) {
                            echo '<option value="' . $participant . '">' . $participant . '</option>';
                            $existingParticipants[] = $participant; // Add the participant to the existing list
                        }
                    }

                    // Add participants from add_participant.php
                    $addParticipantQuery = "SELECT DISTINCT `Nev` FROM jelolt WHERE `Szavazas kod` = ?";
                    if ($stmt = $conn->prepare($addParticipantQuery)) {
                        $stmt->bind_param("i", $row['Szavazas kod']);
                        $stmt->execute();
                        $participantsResult = $stmt->get_result();
                        $stmt->close();

                        while ($participantRow = $participantsResult->fetch_assoc()) {
                            $participant = $participantRow['Nev'];
                            if (!in_array($participant, $existingParticipants)) {
                                echo '<option value="' . $participant . '">' . $participant . '</option>';
                                $existingParticipants[] = $participant; // Add the participant to the existing list
                            }
                        }
                    }

                    echo '</select>';
                    echo '<input type="submit" value="Vote" class="button primary-button">';
                    echo '</form>';
                }
                echo '</div>';
                // Voting results on the right side
                echo '<div class="result">';
                $vote_id = $row['Szavazas kod'];
                $votingResultsQuery = "SELECT `Melyik jeloltre`, COUNT(*) AS `Szavazatok szama` FROM szavazat WHERE `Szavazas kod` = ? GROUP BY `Melyik jeloltre`";
                if ($stmt = $conn->prepare($votingResultsQuery)) {
                    $stmt->bind_param("i", $vote_id);
                    $stmt->execute();
                    $results = $stmt->get_result();
                    $stmt->close();
                    if ($results->num_rows > 0) {
                        echo '<table>';
                        echo '<tr>';
                        echo '<th>Jelolt</th>';
                        echo '<th>Szavazatok szama</th>';
                        echo '</tr>';
                        while ($resultRow = $results->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $resultRow['Melyik jeloltre'] . '</td>';
                            echo '<td>' . $resultRow['Szavazatok szama'] . '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    } else {
                        echo 'No votes for this vote.';
                    }
                }
                echo '</div>';
                
                // Links on the right side
                echo '<div class="vote-links">';
                echo '<a href="add_new_participant.php?vote_id=' . $row['Szavazas kod'] . '" class="button">Add New Participant</a>';
                echo '<a href="extend_date.php?vote_id=' . $row['Szavazas kod'] . '" class="button">Extend Date</a>';
                echo '<a href="delete_participant.php?vote_id=' . $row['Szavazas kod'] . '" class="button">Delete Participant</a>';
                echo '<a href="update_participant.php?vote_id=' . $row['Szavazas kod'] . '" class="button">Update Participant Data</a>';
                echo '<a href="withdraw_participant.php?vote_id=' . $row['Szavazas kod'] . '" class="button">Withdraw Participant</a>';
                echo '</div>';

                echo '</div>'; // Close the vote container
            }
        } else {
            echo '<h2>No available votes.</h2>';
        }
    } else {
        // Display a list of available votes for non-logged users
        $query = "SELECT * FROM szavazas";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="non-logged-vote">';
                echo '<div class="non-logged-voting">';
                echo '<h3 class="vote-title">' . $row['Megnevezes'] . '</h3>';
                echo '<p class="vote-description">' . $row['Leiras'] . '</p>';
                echo '<p class="vote-start">Starts: ' . $row['Indul'] . '</p>';
                echo '<p class "vote-end">Ends: ' . $row['Zarul'] . '</p>';
                echo '</div>';

                echo '<div class="non-logged-result">';
                $vote_id = $row['Szavazas kod'];
                $votingResultsQuery = "SELECT `Melyik jeloltre`, COUNT(*) AS `Szavazatok szama` FROM szavazat WHERE `Szavazas kod` = ? GROUP BY `Melyik jeloltre`";
                if ($stmt = $conn->prepare($votingResultsQuery)) {
                    $stmt->bind_param("i", $vote_id);
                    $stmt->execute();
                    $results = $stmt->get_result();
                    $stmt->close();
                    if ($results->num_rows > 0) {
                        echo '<table>';
                        echo '<tr>';
                        echo '<th>Jelolt</th>';
                        echo '<th>Szavazatok szama</th>';
                        echo '</tr>';
                        while ($resultRow = $results->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $resultRow['Melyik jeloltre'] . '</td>';
                            echo '<td>' . $resultRow['Szavazatok szama'] . '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    } else {
                        echo 'No votes for this vote.';
                    }
                }
                echo '</div>';
                echo '</div>'; // Close the vote container

            }
        } else {
            echo '<h2>No available votes.</h2>';
        }
    }
    ?>
</div>


</body>
</html>
