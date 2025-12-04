<?php
// Connect to database
$mysqli = new mysqli("localhost", "2408369", "Tuba@1230000", "db2408369");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Grabs id value from URL safely
$id = $_GET['id'] ?? null;

// Validate and escape the ID safely
$id = $mysqli->real_escape_string($id);
if (!is_numeric($id)) {
    echo "Invalid game ID.";
    exit();
}
$id = (int)$id; // cast to integer for safety

// Use backticks around column names with hyphens
$sql = "SELECT `game_ID`, `game_name`, `game_description`, `released_date`, `rating`
        FROM `games`
        WHERE `game_ID` = {$id}";

$rst = $mysqli->query($sql);

if (!$rst) {
    echo "Query error: " . $mysqli->error;
    exit();
}

$a_row = $rst->fetch_assoc();

if (!$a_row) {
    echo "Game not found.";
    exit();
}
?>


<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($a_row['game_name']) ?></title>
<style>
    /* STYLES copied and adapted from games.php for a detail view */

    /* New Modern Palette */
    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(145deg, #0D1117 0%, #1A1F27 100%);
        color: #F5F5F5;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        overflow-x: hidden;
    }

    /* Soft textured background (From games.php) */
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.05;
        pointer-events: none;
    }

    /* Header (Matches games.php) */
    header {
        background-color: rgba(20, 24, 28, 0.9);
        width: 100%;
        padding: 15px 40px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        margin-bottom: 30px;
        z-index: 10;
        text-align: center;
    }
    header h1 {
        color: #C7F464; /* Highlight color */
        font-size: 2.5rem;
        margin: 0;
        text-shadow: 0 0 5px rgba(199, 244, 100, 0.5);
    }
    
    /* Content Card (Adapted from games.php aesthetic) */
    .container {
        width: 90%;
        max-width: 800px;
        background-color: #161B22; /* Darker than body gradient end */
        border: 1px solid #2F363D;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
        margin-top: 20px;
        margin-bottom: 40px;
    }

    /* Game Title */
    h2 {
        font-size: 2.2rem;
        color: #4dd0e1; /* Cyan/Teal highlight */
        border-bottom: 2px solid #2F363D;
        padding-bottom: 10px;
        margin-top: 0;
        margin-bottom: 25px;
        word-wrap: break-word;
    }
    
    /* Detail Labels/Strong Text */
    p strong {
        color: #C7F464; /* Highlight for labels */
        display: block; /* Makes the label appear on its own line */
        margin-bottom: 5px;
        font-size: 1.1rem;
    }

    /* Paragraphs (for description/details) */
    p {
        line-height: 1.6;
        margin-bottom: 25px;
        color: #C5C9CD;
    }

    /* Anchor/Link style (From games.php) */
    .back-link {
        color: #4dd0e1;
        text-decoration: none;
        transition: color 0.3s;
        font-weight: 500;
        display: inline-block;
        padding: 8px 15px;
        border: 1px solid #4dd0e1;
        border-radius: 4px;
        margin-top: 10px;
    }
    .back-link:hover {
        color: #C7F464; /* Hover highlight color */
        border-color: #C7F464;
        text-decoration: none;
        background-color: rgba(77, 208, 225, 0.1);
    }
    
    /* Footer (Matches games.php) */
    footer {
        width: 100%;
        padding: 15px 0;
        margin-top: auto; /* Pushes footer to the bottom */
        text-align: center;
        background-color: #1A1F27;
        color: #6A737D;
        border-top: 1px solid #2F363D;
    }
</style>
</head>
<body>
    
<header>
    <h1>Game Archive</h1>
</header>

<div class="container">
    <h2><?= htmlspecialchars($a_row['game_name']) ?></h2>

    <p><strong>Description:</strong></p>
    <p><?= nl2br(htmlspecialchars($a_row['game_description'])) ?></p>

    <p><strong>Release Date:</strong></p>
    <p><?= htmlspecialchars($a_row['released_date']) ?></p>

    <p><strong>Rating:</strong></p>
    <p>
        <span style="color: #FF1493; text-shadow: 0 0 10px rgba(255, 20, 147, 0.9); font-weight: bold; font-size: 1.2rem;">
            <?= htmlspecialchars($a_row['rating']) ?>
        </span>
    </p>

    <a href="games.php" class="back-link">← Back to Game List</a>
</div>

<footer>
    <p>&copy; <?= date("Y") ?> **Game Archive** | Data Stable</p>
</footer>

</body>
</html>