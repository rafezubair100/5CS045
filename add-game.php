<?php
session_start();

// Check if the user is NOT logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Set up variables for raw input (before escaping)
$game_name_raw = $_POST['GameName'] ?? '';
$game_description_raw = $_POST['GameDescription'] ?? '';
$game_released_date_raw = $_POST['DateReleased'] ?? '';
$game_rating_raw = $_POST['GameRating'] ?? '';

// Connect to database
$mysqli = new mysqli("localhost", "2408369", "Tuba@1230000", "db2408369");

if ($mysqli->connect_errno) {
    // Report connection failure gracefully
    error_log("Failed to connect to MySQL: " . $mysqli->connect_error);
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Escape all raw values to prevent SQL syntax errors and SQL injection.
$game_name = $mysqli->real_escape_string($game_name_raw);
$game_description = $mysqli->real_escape_string($game_description_raw);
$game_released_date = $mysqli->real_escape_string($game_released_date_raw);
$game_rating = $mysqli->real_escape_string($game_rating_raw);

// Build SQL statement using the safely escaped values
$sql = "INSERT INTO games (game_name, game_description, released_date, rating)
        VALUES ('{$game_name}', '{$game_description}', '{$game_released_date}', '{$game_rating}')";

if ($mysqli->query($sql) === TRUE) {
    // Success: Redirect back to the main games list
    header("Location: games.php");
    exit();
} else {
    // Failure: Display error
    echo "Error: " . $sql . "<br>" . $mysqli->error;
    exit();
}
// Close connection
$mysqli->close();
?>