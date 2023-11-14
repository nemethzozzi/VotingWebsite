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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Prepare and execute SQL statement to insert a new participant
    $sql = "INSERT INTO jelolt (`Nev`, `Szuletesi datum`, `Foglalkozas`, `Program`, `Email`) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssss", $name, $birthdate, $occupation, $program, $_SESSION['email']);
        if ($stmt->execute()) {
            $successMessage = "New participant added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error in preparing the SQL statement: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Add New Participant</title>
</head>
<body>
    <?php
    if (isset($successMessage)) {
        echo '<p style="color: green;">' . $successMessage . '</p>';
        echo '<script>
        setTimeout(function(){
            window.location.href = "homepage.php";
        }, 1500);
        </script>';
    }
    ?>
    <form method="post" action="add_new_participant.php">
    <h2>Add New Participant</h2>
        Name: <input type="text" name="name" required><br><br>
        Birthdate: <input type="date" name="birthdate" required><br><br>
        Occupation: <input type="text" name="occupation" required><br><br>
        Program: <input type="text" name="program" required><br><br>
        <input type="submit" value="Add Participant">
    </form>

    <p><a href="homepage.php">Go back to homepage</a></p>

    </body>
</html>
