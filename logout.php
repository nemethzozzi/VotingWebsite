<?php
session_start(); // Start the session
session_unset(); // Clear all session data
session_destroy(); // Destroy the session
header("Location: homepage.php"); // Redirect to the homepage or any other page
?>
