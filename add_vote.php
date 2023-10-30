<?php
// Start a session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit;
}

// Database connection setup (replace with your database credentials)
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $vote_id = $_POST['vote_id'];
    $selected_participant = $_POST['selected_participant'];

    // Check if the vote exists and is still open
    $checkVoteSql = "SELECT * FROM szavazas WHERE `Szavazas kod` = ? AND `Zarul` >= CURRENT_DATE";
    $stmtCheckVote = $conn->prepare($checkVoteSql);
    $stmtCheckVote->bind_param("i", $vote_id);
    $stmtCheckVote->execute();
    $resultVote = $stmtCheckVote->get_result();
    
    if ($resultVote->num_rows > 0) {
        $vote = $resultVote->fetch_assoc();
        // Check if the selected participant is valid
        $participants = explode(',', $vote['Jeloltek']);
        
        if (in_array($selected_participant, $participants)) {
            // Check if the user has already voted for this vote
            $user_email = $_SESSION['email'];

            // Fetch the username based on the user's email
            $fetchUsernameSql = "SELECT Felhasznalonev FROM felhasznalo WHERE Email = ?";
            $stmtFetchUsername = $conn->prepare($fetchUsernameSql);
            $stmtFetchUsername->bind_param("s", $user_email);
            $stmtFetchUsername->execute();
            $stmtFetchUsername->bind_result($user_username);
            $stmtFetchUsername->fetch();
            $stmtFetchUsername->close();
            
            // Check if the user has already voted for this vote
            $checkUserVoteSql = "SELECT * FROM szavazat WHERE `Melyik szavazas` = ? AND `Email` = ?";
            $stmtCheckUserVote = $conn->prepare($checkUserVoteSql);
            $stmtCheckUserVote->bind_param("is", $vote_id, $user_email);
            $stmtCheckUserVote->execute();
            $resultUserVote = $stmtCheckUserVote->get_result();
            
            if ($resultUserVote->num_rows > 0) {
                echo "You have already voted for this vote.";
            } else {
                // Insert the vote into the database with the 'Felhasznalonev' field
                $insertVoteSql = "INSERT INTO szavazat (`Melyik szavazas`, `Melyik jeloltre`, `Email`, `Felhasznalonev`, `Idopont`) VALUES (?, ?, ?, ?, CURRENT_DATE)";
                $stmtInsertVote = $conn->prepare($insertVoteSql);
                $stmtInsertVote->bind_param("isss", $vote_id, $selected_participant, $user_email, $user_username);
                if ($stmtInsertVote->execute()) {
                    echo "Your vote has been recorded.";
                } else {
                    echo "Failed to record your vote.";
                }
                $stmtInsertVote->close();
            }
            $stmtCheckUserVote->close();
        } else {
            echo "Invalid participant selection.";
        }
    } else {
        echo "The vote is either closed or doesn't exist.";
    }

    // Close all prepared statements
    $stmtCheckVote->close();
}

// Close the database connection
$conn->close();
?>
