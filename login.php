<?php
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$database = "szavazatszamlalo";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM felhasznalo WHERE Email = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $hashed_password = $row['Jelszo'];
                
                if (password_verify($password, $hashed_password)) {
                    echo "Login successful!";
                    // You can redirect the user to a dashboard or another page here.
                } else {
                    echo "Invalid password. Please try again.";
                }
            } else {
                echo "Email not found. Please register first.";
            }
        } else {
            echo "Login failed. Please try again later.";
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
    <title>Login</title>
</head>
<body>
    <h2>Login Form</h2>
    <form method="post" action="login.php"> <!-- Change the action to the current file's name -->
        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
