<?php
session_start();

// Check if the user is NOT logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// edit.php - Edit a game record

// DB connection (same credentials you used elsewhere)
$mysqli = new mysqli("localhost", "2408369", "Tuba@1230000", "db2408369");
if ($mysqli->connect_errno) {
    die("<p class='error-message'>ERROR: Failed to connect to MySQL: " . $mysqli->connect_error . "</p>");
}

$error = '';
$id = 0; // Initialize ID

// --- Handle POST Request (Form Submission) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $id = intval($_POST['game_id']);
    $game_name = trim($_POST['game_name']);
    $game_description = trim($_POST['game_description']);
    $released_date = trim($_POST['released_date']);
    $rating = trim($_POST['rating']);

    if (empty($game_name) || empty($released_date) || empty($rating)) {
        $error = "All fields are required.";
    } elseif (!is_numeric($rating)) {
        $error = "Rating must be a number.";
    } else {
        // Use prepared statements for update
        $stmt = $mysqli->prepare("UPDATE games SET game_name=?, game_description=?, released_date=?, rating=? WHERE game_ID=?");
        if (!$stmt) {
            $error = "Prepare failed: " . $mysqli->error;
        } else {
            // FIX: Changed 'd' (double) to 's' (string) for rating for reliable database conversion.
            $stmt->bind_param("ssssi", $game_name, $game_description, $released_date, $rating, $id);
            if ($stmt->execute()) {
                // Success: Redirect back to the main games list
                header("Location: games.php");
                exit;
            } else {
                $error = "Error updating record: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// --- Handle GET Request (Fetching Data for the Form) ---

// Get the ID from GET (initial load) or POST (if form submission failed)
if (isset($_GET['ID'])) {
    $id = intval($_GET['ID']);
} elseif (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} elseif (isset($_POST['game_id'])) {
    $id = intval($_POST['game_id']);
}

// If no valid ID is found, stop execution
if ($id === 0) {
    die("No valid game selected for editing.");
}

// Fetch existing game data
$stmt = $mysqli->prepare("SELECT game_ID, game_name, game_description, released_date, rating FROM games WHERE game_ID = ?");
if (!$stmt) { die("Prepare failed: " . $mysqli->error); }
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Game not found.");
}

$game = $result->fetch_assoc();
$stmt->close();
$mysqli->close(); // Close connection before outputting HTML

// Check if a POST failed (if the form variables exist, use them to re-populate the form)
// This is important so the user doesn't lose their data on a failed submission.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($error)) {
    $game['game_name'] = $game_name;
    $game['game_description'] = $game_description;
    $game['released_date'] = $released_date;
    $game['rating'] = $rating;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Game: <?= htmlspecialchars($game['game_name']) ?></title>
    <style>
        /* Minimalist Style for demonstration */
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .navbar { background-color: #333; color: white; padding: 15px; text-align: center; }
        .container { max-width: 600px; margin: 30px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .card { padding: 20px; border: 1px solid #ccc; border-radius: 6px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input[type="text"], input[type="date"], textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button[type="submit"] { background: #5cb85c; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; margin-top: 20px; }
        button[type="submit"]:hover { background: #4cae4c; }
        .back-btn { display: inline-block; padding: 8px 15px; margin-top: 10px; background: #999; color: #fff; text-decoration: none; border-radius: 4px; }
        .back-btn:hover { background: #777; }
        .error { color: red; margin-top: 10px; }
    </style>

</head>
<body>
<div class="navbar"><h2>✏ Edit Game Record</h2></div>
<div class="container">
    <div class="card">
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="game_id" value="<?= htmlspecialchars($game['game_ID']) ?>">

            <label for="game_name">Game Name:</label>
            <input type="text" id="game_name" name="game_name" value="<?= htmlspecialchars($game['game_name']) ?>" required>

            <label for="game_description">Game Description:</label>
            <textarea id="game_description" name="game_description" rows="5" required><?= htmlspecialchars($game['game_description']) ?></textarea>

            <label for="released_date">Release Date:</label>
            <input type="date" id="released_date" name="released_date" value="<?= htmlspecialchars($game['released_date']) ?>" required>

            <label for="rating">Rating:</label>
            <input type="text" id="rating" name="rating" value="<?= htmlspecialchars($game['rating']) ?>" required>

            <button type="submit">Update Game</button>
            <a href="games.php" class="back-btn">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>