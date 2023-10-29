<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

// Check if the vote_id is set
if (!isset($_GET['vote_id'])) {
    echo 'Vote ID is missing.';
    exit;
}

$vote_id = $_GET['vote_id'];

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

// Check if the user has access to this vote
$email = $_SESSION['email'];
$access_query = "SELECT * FROM szavazas WHERE `Szavazas kod` = ? AND `Email` = ?";
$stmt = $conn->prepare($access_query);
$stmt->bind_param("is", $vote_id, $email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows === 0) {
    echo 'You do not have access to this vote.';
    exit;
}

// Handle form submission for updating vote dates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['start_date'], $_POST['end_date'])) {
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        // Prepare and execute the SQL query to update the vote dates
        $update_query = "UPDATE szavazas SET Indul = ?, Zarul = ? WHERE `Szavazas kod` = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssi", $start_date, $end_date, $vote_id);

        if ($stmt->execute()) {
            echo "Vote dates updated successfully!";
        } else {
            echo "Error updating vote dates: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Vote Dates</title>
</head>
<body>
    <h2>Update Vote Dates</h2>
    <form method="post">
        <input type="hidden" name="vote_id" value="<?php echo $vote_id; ?>">
        Start Date: <input type="date" name="start_date" required><br><br>
        End Date: <input type="date" name="end_date" required><br><br>
        <input type="submit" value="Update Vote Dates">
    </form>
    <p><a href="homepage.php">Go back to homepage</a></p>
</body>
</html>
