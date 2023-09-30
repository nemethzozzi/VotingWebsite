<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link rel="stylesheet" type="text/css" href="register.css">
</head>
<body>
<header>
        <nav>
            <ul>
                <li><a href="homepage.php">Go back</a></li>
            </ul>
        </nav>
    </header>
    <div class="registration-container">
        <h2>Registration</h2>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <li><a href="login.php">Already have an account? Log in now!</a></li>
            <button type="submit" name="register">Register</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
            // Handle form submission
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

            // TODO: Perform database insertion and validation here.
            // This is a simplified example; ensure you sanitize and validate user inputs.
            
            // Provide feedback to the user
            echo "<p>Registration successful for $username!</p>";
        }
        ?>
    </div>
</body>
</html>
