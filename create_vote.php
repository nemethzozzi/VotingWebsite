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
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit;
}

// Fetch Jeloltek options from the jelolt table
$jeloltOptions = "";
$jeloltQuery = "SELECT `Jelolt kod`, `Nev` FROM jelolt";
$result = $conn->query($jeloltQuery);

if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jeloltOptions .= '<option value="' . $row['Nev'] . '">' . $row['Nev'] . '</option>';
    }
} else {
    echo "No data found in the 'jelolt' table.";
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $megnevezes = $_POST['megnevezes'];
    $leiras = $_POST['leiras'];
    $jeloltek = $_POST['jeloltek'];
    $indul = $_POST['indul'];
    $zarul = $_POST['zarul'];

    // Insert the new vote into the database
    $insertSql = "INSERT INTO szavazas (Megnevezes, Leiras, Indul, Zarul, Email, Jeloltek) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($insertSql)) {

        $selectedParticipants = implode(",", $jeloltek);
        $stmt->bind_param("ssssss", $megnevezes, $leiras, $indul, $zarul, $_SESSION['email'], $selectedParticipants);
        
        // Execute the insert query
        if ($stmt->execute()) {
            // Get the auto-generated vote number
            $voteNumber = $stmt->insert_id;

            // Close the statement
            $stmt->close();

        // Loop through the selected participants
        foreach ($jeloltek as $participant) {
            // Get the participant data
            $participantDataSql = "SELECT `Jelolt kod`, `Nev`, `Szuletesi datum`, `Foglalkozas`, `Program`, `Email` FROM jelolt WHERE `Nev` = ?";
            $participantStmt = $conn->prepare($participantDataSql);
        
            if ($participantStmt) {
                $participantStmt->bind_param("s", $participant);
                $participantStmt->execute();
                $participantResult = $participantStmt->get_result();
        
                // Check if participant exists
                if ($participantResult->num_rows > 0) {
                    // Get participant data
                    $participantData = $participantResult->fetch_assoc();
        
                    // Check if the participant already has a vote number
                    if ($participantData['Jelolt kod'] !== null) {
                        // Update the Szavazas kod for the participant
                        $updateParticipantSql = "UPDATE jelolt SET `Szavazas kod` = ? WHERE `Nev` = ?";
                        $updateParticipantStmt = $conn->prepare($updateParticipantSql);
        
                        if ($updateParticipantStmt) {
                            $updateParticipantStmt->bind_param("is", $voteNumber, $participantData['Nev']);
                            $updateParticipantStmt->execute();
                            $updateParticipantStmt->close();
                        } else {
                            // Error in preparing the update statement for the participant
                            echo "Error preparing the update statement for the participant: " . $conn->error;
                        }
                    } else {
                        // Insert the participant data for the new vote
                        $insertParticipantSql = "INSERT INTO jelolt (`Szavazas kod`, `Nev`, `Szuletesi datum`, `Foglalkozas`, `Program`, `Email`) VALUES (?, ?, ?, ?, ?, ?)";
                        $insertParticipantStmt = $conn->prepare($insertParticipantSql);
        
                        if ($insertParticipantStmt) {
                            $insertParticipantStmt->bind_param("isssss", $voteNumber, $participantData['Nev'], $participantData['Szuletesi datum'], $participantData['Foglalkozas'], $participantData['Program'], $participantData['Email']);
                            $insertParticipantStmt->execute();
                            $insertParticipantStmt->close();
                        } else {
                            // Error in preparing the insert statement for the participant
                            echo "Error preparing the insert statement for the participant: " . $conn->error;
                        }
                    }
                } else {
                    // Participant not found
                    echo "Participant not found: " . $participant;
                }
        
                // Close the statement
                $participantStmt->close();
            } else {
                // Error in preparing the statement for participant data
                echo "Error preparing the statement for participant data: " . $conn->error;
            }
        }


            // Participant data inserted successfully
            $successMessage = "Vote and Participants created successfully!";
        } else {
            // Error while executing the query
            echo "Error creating vote: " . $stmt->error;
        }
    } else {
        // Error in preparing the SQL statement
        echo "Error preparing the SQL statement: " . $conn->error;
    }
}


// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Create Vote</title>
    <script>
        // Function to add a new participant selection
        function addParticipant() {
            var selectContainer = document.getElementById('participantContainer');
            var newSelect = document.createElement('select');
            newSelect.name = 'jeloltek[]';
            newSelect.innerHTML = '<?php echo $jeloltOptions; ?>';
            selectContainer.appendChild(newSelect);

            if (selectContainer.childElementCount > 1) {
            var deleteButton = document.createElement('button');
            deleteButton.type = 'delete-button';
            deleteButton.textContent = 'Delete Participant';
            deleteButton.onclick = function() {
                selectContainer.removeChild(newSelect);
                selectContainer.removeChild(deleteButton);
            };
            selectContainer.appendChild(deleteButton);
        }
        }
    </script>
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
    <form method="post" action="create_vote.php">
    <h2>Create a New Vote</h2>
        <label for="megnevezes">Megnevezés:</label>
        <input type="text" name="megnevezes" required><br><br>

        <label for="leiras">Leírás:</label>
        <textarea name="leiras" required></textarea><br><br>

        <label for="jeloltek">Jelöltek:</label>
        <div id="participantContainer">
            <select name="jeloltek[]" required>
                <option value="">Select a Jelolt</option>
                <?php echo $jeloltOptions; ?>
            </select>
            <button type="button" onclick="addParticipant()">Add Participant</button><br><br>
        </div>
        <p><a href="add_participant.php">Add new Participant</a></p>


        <label for="indul">Indul dátuma:</label>
        <input type="date" name="indul" required><br><br>

        <label for="zarul">Zárul dátuma:</label>
        <input type="date" name="zarul" required><br><br>

        <input type="submit" value="Create Vote">
    </form>

    <p><a href="homepage.php">Go back to homepage</a></p>
</body>
</html>
