<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $birthdate = $_POST['birthdate'];
    $occupation = $_POST['occupation'];
    $program = $_POST['program'];

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
    <title>Add Participant</title>
</head>
<body>
    <?php
    if (isset($successMessage)) {
        echo '<p style="color: green;">' . $successMessage . '</p>';
        echo '<script>
        setTimeout(function(){
            window.location.href = "create_vote.php";
        }, 1500);
        </script>';
    }
    ?>
    <form method="post" action="add_participant.php">
    <h2>Add Participant</h2>
        Name: <input type="text" name="name" required><br><br>
        Birthdate: <input type="date" name="birthdate" required><br><br>
        Occupation: <input type="text" name="occupation" required><br><br>
        Program: <input type="text" name="program" required><br><br>
        <input type="submit" value="Add Participant">
    </form>

    <p><a href="homepage.php">Go back to homepage</a></p>

    </body>
</html>
