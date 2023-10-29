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
    if ($stmt = $conn->prepare($checkVoteSql)) {
        $stmt->bind_param("i", $vote_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $vote = $result->fetch_assoc();
            // Check if the selected participant is valid
            $participants = explode(',', $vote['Jeloltek']);
            if (in_array($selected_participant, $participants)) {
                // Check if the user has already voted for this vote
                $user_email = $_SESSION['email'];
                
                // Fetch the username based on the user's email
                $fetchUsernameSql = "SELECT Felhasznalonev FROM felhasznalo WHERE Email = ?";
                if ($stmt = $conn->prepare($fetchUsernameSql)) {
                    $stmt->bind_param("s", $user_email);
                    $stmt->execute();
                    $stmt->bind_result($user_username);
                    $stmt->fetch();
                    $stmt->close();
                }
                
                // Check if the user has already voted for this vote
                $checkUserVoteSql = "SELECT * FROM szavazat WHERE `Melyik szavazas` = ? AND `Email` = ?";
                if ($stmt = $conn->prepare($checkUserVoteSql)) {
                    $stmt->bind_param("si", $vote_id, $user_email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        echo "You have already voted for this vote.";
                    } else {
                        // Insert the vote into the database with the 'Felhasznalonev' field
                        $insertVoteSql = "INSERT INTO szavazat (`Melyik szavazas`, `Melyik jeloltre`, `Email`, `Felhasznalonev`, `Idopont`) VALUES (?, ?, ?, ?, CURRENT_DATE)";
                        if ($stmt = $conn->prepare($insertVoteSql)) {
                            $stmt->bind_param("isss", $vote_id, $selected_participant, $user_email, $user_username);
                            if ($stmt->execute()) {
                                echo "Your vote has been recorded.";
                            } else {
                                echo "Failed to record your vote.";
                            }
                        }
                    }
                }
            } else {
                echo "Invalid participant selection.";
            }
        } else {
            echo "The vote is either closed or doesn't exist.";
        }
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>
