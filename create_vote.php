<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

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

$successMessage = "";
$errorMessage = "";

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

    // Validation: Ensure the selected Jelolt is not empty
    if (empty($jeloltek)) {
        $errorMessage = "Please select a Jelolt.";
    } else {
        // Insert the new vote into the database
        $insertSql = "INSERT INTO szavazas (Megnevezes, Leiras, Jeloltek, Indul, Zarul, Email) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($insertSql)) {
            // Bind the parameters
            $stmt->bind_param("ssssss", $megnevezes, $leiras, $jeloltek, $indul, $zarul, $_SESSION['email']);
            if ($stmt->execute()) {
                $successMessage = "Vote created successfully!";
                $stmt->close();
            } else {
                $errorMessage = "Error creating vote: " . $stmt->error;
                $stmt->close();
            }
        } else {
            $errorMessage = "Error preparing the SQL statement: " . $conn->error;
        }
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
</head>
<body>
    <?php
    if (!empty($successMessage)) {
        echo '<p style="color: green;">' . $successMessage . '</p>';
        echo '<script>
        setTimeout(function(){
            window.location.href = "homepage.php";
        }, 2000);
        </script>';
    }
    if (!empty($errorMessage)) {
        echo '<p style="color: red;">' . $errorMessage . '</p>';
    }
    ?>
    <form method="post" action="create_vote.php">
    <h2>Create a New Vote</h2>
        <label for="megnevezes">Megnevezés:</label>
        <input type="text" name="megnevezes" required><br><br>

        <label for="leiras">Leírás:</label>
        <textarea name="leiras" required></textarea><br><br>

        <label for="jeloltek">Jelöltek:</label>
        <select name="jeloltek" required>
            <option value="">Select a Jelolt</option>
            <?php echo $jeloltOptions; ?>
        </select>
        <a href="add_participant.php">Add a Participant</a><br><br>

        <label for="indul">Indul dátuma:</label>
        <input type="date" name="indul" required><br><br>

        <label for="zarul">Zárul dátuma:</label>
        <input type="date" name="zarul" required><br><br>

        <input type="submit" value="Create Vote">
    </form>

    <p><a href="homepage.php">Go back to homepage</a></p>
</body>
</html>
