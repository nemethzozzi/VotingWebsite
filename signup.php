<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password != $password_confirm) {
        die("Passwords do not match.");
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO felhasznalo (Email, Felhasznalonev, Jelszo, `Legutobbi belepes`) VALUES (?, ?, ?, NOW())";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $email, $username, $hashed_password);
        if ($stmt->execute()) {
            echo "Sign Up successful!";
        } else {
            echo "Sign Up failed. Please try again later.";
        }
        $stmt->close();
    } else {
        echo "Error in preparing the SQL statement.";
    }
} 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
</head>
<body>
    <h2>Sign Up Form</h2>
    <form method="post" action="signup.php"> 
        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>

        <label for="username">Username:</label>
        <input type="text" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>

        <label for="password_confirm">Confirm Password:</label>
        <input type="password" name="password_confirm" required><br><br>

        <input type="submit" value="Register">
    </form>

    <p>Already have an account? <a href="login.php">Login Now!</a></p>
</body>
</body>
</html>