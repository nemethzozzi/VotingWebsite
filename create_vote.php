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
        $jeloltOptions .= '<option value="' . $row['Jelolt kod'] . '">' . $row['Nev'] . '</option>';
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
    $insertSql = "INSERT INTO szavazas (Megnevezes, Leiras, Jeloltek, Indul, Zarul, Email) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($insertSql)) {
        // Bind the parameters
        $stmt->bind_param("ssssss", $megnevezes, $leiras, $jeloltek, $indul, $zarul, $_SESSION['email']);

        // Execute the insert query
        if ($stmt->execute()) {
            // Vote created successfully
            echo "Vote created successfully!";
        } else {
            // Error while executing the query
            echo "Error creating vote: " . $stmt->error;
        }
        $stmt->close();
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
    <title>Create Vote</title>
</head>
<body>
    <h2>Create a New Vote</h2>
    <form method="post" action="create_vote.php">
        <label for="megnevezes">Megnevezés:</label>
        <input type="text" name="megnevezes" required><br><br>

        <label for="leiras">Leírás:</label>
        <textarea name="leiras" required></textarea><br><br>

        <label for="jeloltek">Jelöltek:</label>
        <select name="jeloltek" required>
            <option value="">Select a Jelolt</option>
            <?php echo $jeloltOptions; ?>
        </select><br><br>

        <label for="indul">Indul dátuma:</label>
        <input type="date" name="indul" required><br><br>

        <label for="zarul">Zárul dátuma:</label>
        <input type="date" name="zarul" required><br><br>

        <input type="submit" value="Create Vote">
    </form>
</body>
</html>
