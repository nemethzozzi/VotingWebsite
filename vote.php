<?php
session_start(); // Start the session for user authentication

if (!isset($_SESSION['username'])) {
    if (isset($_GET['vote_id'])) {
        // Ensure the vote_id is valid and not manipulated

        // Include your database connection code here

        $voteId = $_GET['vote_id'];

        // Check if the vote is open (compare with the current date and the 'Indul' and 'Zarul' dates)
        $checkVoteQuery = "SELECT * FROM szavazas WHERE Szavazas kod = $voteId";
        $checkVoteResult = mysqli_query($connection, $checkVoteQuery);

        if (mysqli_num_rows($checkVoteResult) == 1) {
            $vote = mysqli_fetch_assoc($checkVoteResult);
            $currentDate = date("Y-m-d");

            if ($currentDate >= $vote['Indul'] && $currentDate <= $vote['Zarul']) {
                // Display voting form
                echo "<h1>" . $vote['Megnevezes'] . "</h1>";
                echo "<p>Description: " . $vote['Leiras'] . "</p>";
                echo "<p>Open from " . $vote['Indul'] . " to " . $vote['Zarul'] . "</p>";

                // Fetch the list of candidates for this vote
                $candidateQuery = "SELECT * FROM jelolt WHERE Szavazas kod = $voteId";
                $candidateResult = mysqli_query($connection, $candidateQuery);

                echo "<form action='submit_vote.php' method='post'>";
                echo "<input type='hidden' name='vote_id' value='$voteId'>";
                echo "Select your vote: ";
                echo "<select name='selected_jelolt' required>";
                
                while ($candidate = mysqli_fetch_assoc($candidateResult)) {
                    echo "<option value='" . $candidate['Nev'] . "'>" . $candidate['Nev'] . "</option>";
                }

                echo "</select>";
                echo "<input type='submit' value='Vote'>";
                echo "</form>";
            } else {
                echo "This vote is closed.";
            }
        } else {
            echo "Invalid vote.";
        }

        // Close the database connection
        mysqli_close($connection);
    } else {
        echo "Invalid vote.";
    }
} else {
    echo "You are already logged in.";
}
?>
