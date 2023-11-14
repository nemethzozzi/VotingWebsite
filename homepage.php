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
                        echo '<h2 class="vote-result">Vote Result</h2>';
                        echo '<table>';
                        echo '<tr>';
                        echo '<th>Nominated</th>';
                        echo '<th>Number of votes</th>';
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
                echo '<a href="add_participant_to_vote.php?vote_id=' . $row['Szavazas kod'] . '" class="button">Add Participant to the vote</a>';
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
                echo '<p class="vote-end">Ends: ' . $row['Zarul'] . '</p>';
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
                        echo '<h2 class="vote-result">Vote Result</h2>';
                        echo '<table>';
                        echo '<tr>';
                        echo '<th>Nominated</th>';
                        echo '<th>Number of votes</th>';
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


<div class="sql-import-container">
    <?php
    // Your SQL query
    $sql_import_votes = 'SELECT
            s.`Megnevezes` AS SzavazasMegnevezes,
            j.`Nev` AS JeloltNev,
            COUNT(sz.`Szavazat kod`) AS SzavazatokSzama
        FROM
            `szavazat` sz
        JOIN
            `szavazas` s ON sz.`Szavazas kod` = s.`Szavazas kod`
        JOIN
            `jelolt` j ON sz.`Melyik jeloltre` = j.`Nev`
        WHERE
            sz.`Idopont` BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() AND s.`Zarul` < CURDATE()
        GROUP BY
            s.`Megnevezes`, j.`Nev`
        ORDER BY
            s.`Megnevezes`, SzavazatokSzama DESC;';

    // Execute the query
    $result = $conn->query($sql_import_votes);

    // Check for errors
    if (!$result) {
        echo "Error fetching vote results: " . $conn->error;
    } else {
        // Display the results in a table
        echo '<table border="1">';
        echo '<tr><th>Szavazás Megnevezése</th><th>Jelölt Neve</th><th>Szavazatok Száma</th></tr>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['SzavazasMegnevezes'] . '</td>';
            echo '<td>' . $row['JeloltNev'] . '</td>';
            echo '<td>' . $row['SzavazatokSzama'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    ?>
</div>


<div class="sql-import-container">
    <?php
    // Your SQL query
    $sql_import_participants = 'SELECT
            j.`Nev` AS JeloltNev,
            j.`Foglalkozas` AS Foglalkozas,
            s.`Megnevezes` AS SzavazasMegnevezes,
            s.`Indul` AS SzavazasKezdete,
            s.`Zarul` AS SzavazasVege
        FROM
            `jelolt` j
        JOIN
            `szavazas` s ON j.`Szavazas kod` = s.`Szavazas kod`
        ORDER BY
            j.`Nev`, s.`Indul`;';

    // Execute the query
    $result = $conn->query($sql_import_participants);

    // Check for errors
    if (!$result) {
        echo "Error fetching participant information: " . $conn->error;
    } else {
        // Display the results in a table
        echo '<table border="1">';
        echo '<tr><th>Jelölt Neve</th><th>Foglalkozás</th><th>Szavazás Megnevezése</th><th>Szavazás Kezdete</th><th>Szavazás Vége</th></tr>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['JeloltNev'] . '</td>';
            echo '<td>' . $row['Foglalkozas'] . '</td>';
            echo '<td>' . $row['SzavazasMegnevezes'] . '</td>';
            echo '<td>' . $row['SzavazasKezdete'] . '</td>';
            echo '<td>' . $row['SzavazasVege'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    ?>
</div>


<div class="sql-import-container">
    <?php
    // Your SQL query
    $sql_import_upcoming_vote = 'SELECT
            Megnevezes,
            Jeloltek,
            Zarul AS LezarasIdeje
        FROM
            szavazas
        WHERE
            Zarul >= CURDATE()
        ORDER BY
            Zarul DESC
        LIMIT 1;';

    // Execute the query
    $result = $conn->query($sql_import_upcoming_vote);

    // Check for errors
    if (!$result) {
        echo "Error fetching upcoming vote information: " . $conn->error;
    } else {
        // Display the results
        while ($row = $result->fetch_assoc()) {
            echo '<p>Megnevezés: ' . $row['Megnevezes'] . '</p>';
            echo '<p>Jelöltek: ' . $row['Jeloltek'] . '</p>';
            echo '<p>Lezárás Ideje: ' . $row['LezarasIdeje'] . '</p>';
        }
    }
    ?>
</div>

<div class="sql-import-container">
    <?php
    $sql_import_vote_statistics = 'SELECT
            YEAR(Idopont) AS Ev,
            MONTH(Idopont) AS Honap,
            COUNT(*) AS SzavazatokSzama
        FROM
            szavazat
        WHERE
            Idopont >= \'2020-01-01\'
        GROUP BY
            Ev, Honap
        ORDER BY
            Ev, Honap;';

    // Execute the query
    $result = $conn->query($sql_import_vote_statistics);

    // Check for errors
    if (!$result) {
        echo "Error fetching vote statistics: " . $conn->error;
    } else {
        // Display the results in a table
        echo '<table border="1">
                <tr>
                    <th>Év</th>
                    <th>Hónap</th>
                    <th>Szavazatok Száma</th>
                </tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['Ev'] . '</td>';
            echo '<td>' . $row['Honap'] . '</td>';
            echo '<td>' . $row['SzavazatokSzama'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    }
    ?>
</div>


</body>
</html>
