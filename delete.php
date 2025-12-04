<?php
session_start();

// Check if the user is NOT logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Set up variable for raw input (before escaping)
$game_id_raw = $_GET['id'] ?? '';

// Validate ID input
if ($game_id_raw === '' || !is_numeric($game_id_raw)) {
    // If no valid ID is provided, redirect back to the main page
    header("Location: games.php?error=invalid_id");
    exit();
}

// Connect to database
$mysqli = new mysqli("localhost", "2408369", "Tuba@1230000", "db2408369");

if ($mysqli->connect_errno) {
    // Report connection failure gracefully
    error_log("Failed to connect to MySQL: " . $mysqli->connect_error);
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Escape the raw ID and cast to int to prevent SQL injection
$game_id = (int)$mysqli->real_escape_string($game_id_raw);

// Build SQL statement using the safely escaped value
$sql = "DELETE FROM games WHERE game_ID = {$game_id}";

if ($mysqli->query($sql) === TRUE) {
    // Success: Redirect back to the main games list
    header("Location: games.php");
    exit();
} else {
    // Failure: Display error
    echo "Error deleting record: " . $mysqli->error;
    exit();
}

// Close connection
$mysqli->close();
?>