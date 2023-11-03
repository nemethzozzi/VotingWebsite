<?php
session_start(); // Start the session

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
    $password = $_POST['password'];

    $sql = "SELECT Jelszo, Felhasznalonev FROM felhasznalo WHERE Email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $stmt->bind_result($hashed_password, $username);
            $stmt->fetch();

            $stmt->close(); // Close the first prepared statement here

            if (password_verify($password, $hashed_password)) {
                // Login successful, set the session variable and update last login time
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $username;

                // Update the last login time
                $updateSql = "UPDATE felhasznalo SET `Legutobbi belepes` = NOW() WHERE Email = ?";
                if ($updateStmt = $conn->prepare($updateSql)) {
                    $updateStmt->bind_param("s", $email);
                    $updateStmt->execute();
                    $updateStmt->close();
                }

                echo "Logged in successfully.";
                echo '<script>
                    setTimeout(function() {
                        window.location.href = "homepage.php";
                    }, 2000);
                </script>';
                exit; // Ensure no further processing of the script
            } else {
                echo "Login failed. Please check your credentials.";
            }
        } else {
            echo "Error executing the query: " . $stmt->error;
        }
    } else {
        echo "Error in preparing the SQL statement: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Login</title>
</head>
<body>
    <form method="post" action="login.php">
    <h2>Login Form</h2>
        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Login">

        <p>Don't have an account? <a href="signup.php">Signup Now!</a></p>
    </form>

    
    <p><a href="homepage.php">Go back to homepage</a></p>

</body>
</html>
