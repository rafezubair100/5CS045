<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Archive</title>
    <style>
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
    justify-content: space-between;
    overflow-x: hidden;
}

/* Soft textured background */
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

header {
    background-color: rgba(20, 25, 35, 0.95);
    border-bottom: 3px solid #C7F464;
    padding: 30px 40px;
    width: 100%;
    text-align: center;
    box-shadow: 0 5px 25px rgba(90, 79, 207, 0.4);
}

h1 {
    margin: 0;
    font-size: 3.2em;
    color: #F5F5F5;
    font-family: 'Poppins', sans-serif;
    text-shadow: 0 0 10px rgba(90, 79, 207, 0.6);
    letter-spacing: 3px;
    text-transform: uppercase;
}

h1::after {
    content: '';
    display: block;
    width: 90px;
    height: 3px;
    background-color: #C7F464;
    margin: 12px auto 0;
    box-shadow: 0 0 8px #C7F464;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 40px auto;
    padding: 25px;
    background-color: rgba(26, 31, 39, 0.8);
    border: 1px solid rgba(90, 79, 207, 0.4);
    border-radius: 12px;
    box-shadow: 0 0 30px rgba(90, 79, 207, 0.25);
    backdrop-filter: blur(4px);
}

form {
    text-align: center;
    padding: 25px;
    background-color: rgba(20, 25, 35, 0.85);
    border: 1px solid #5A4FCF;
    border-radius: 10px;
    box-shadow: 0 0 12px rgba(90, 79, 207, 0.2);
    position: relative;
}

form::before {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 3px;
    background-color: #C7F464;
    box-shadow: 0 0 8px #C7F464;
}

input[type="text"] {
    padding: 14px;
    width: 340px;
    border: 2px solid #5A4FCF;
    border-radius: 6px;
    background-color: #0D1117;
    color: #F5F5F5;
    font-size: 1em;
    box-shadow: inset 0 0 6px rgba(90, 79, 207, 0.6);
    transition: all 0.3s ease-in-out;
}

input[type="text"]:focus {
    border-color: #C7F464;
    outline: none;
    box-shadow: 0 0 12px rgba(199, 244, 100, 0.7);
}

input[type="submit"] {
    padding: 14px 28px;
    background-color: #C7F464;
    color: #0D1117;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    text-transform: uppercase;
    cursor: pointer;
    box-shadow: 0 0 12px rgba(199, 244, 100, 0.7);
    transition: all 0.3s ease-in-out;
    margin-left: 15px;
}

input[type="submit"]:hover {
    background-color: #5A4FCF;
    color: #fff;
    box-shadow: 0 0 18px rgba(90, 79, 207, 1);
}

table {
    width: 100%;
    margin-top: 40px;
    border-collapse: collapse;
    background-color: rgba(26, 31, 39, 0.9);
    border: 1px solid #5A4FCF;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 25px rgba(90, 79, 207, 0.25);
}

th, td {
    padding: 18px;
    text-align: center;
    border-bottom: 1px solid rgba(199, 244, 100, 0.25);
}

th {
    background-color: #5A4FCF;
    color: #F5F5F5;
    font-family: 'Poppins', sans-serif;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 1.1em;
}

tr:nth-child(even) {
    background-color: rgba(20, 25, 35, 0.7);
}

tr:hover {
    background-color: rgba(199, 244, 100, 0.12);
    cursor: pointer;
}

a {
    color: #C7F464;
    font-weight: bold;
    text-shadow: 0 0 6px rgba(199, 244, 100, 0.8);
    transition: 0.3s ease;
}

a:hover {
    color: #5A4FCF;
    text-shadow: none;
}

footer {
    text-align: center;
    padding: 20px;
    background-color: rgba(20, 25, 35, 0.95);
    color: #C7F464;
    border-top: 3px solid #C7F464;
    text-shadow: 0 0 10px #C7F464;
    font-size: 0.9em;
}

/* Error box */
.error-message {
    color: #C7F464;
    background-color: rgba(20, 25, 35, 0.95);
    border: 1px dashed #5A4FCF;
    padding: 20px;
    margin: 20px auto;
    border-radius: 8px;
    text-shadow: 0 0 10px #C7F464;
    box-shadow: 0 0 15px rgba(90, 79, 207, 0.4);
}

/* Responsive */
@media (max-width: 768px) {
    h1 { font-size: 2em; }
    input[type="text"], input[type="submit"] {
        width: calc(100% - 30px);
        margin-left: 0;
        margin-bottom: 10px;
    }
}

@media (max-width: 480px) {
    h1 { font-size: 1.3em; }
}


    </style>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1> EliteGame Hub </h1>
    </header>

    <div class="container">
        <?php
        // Connect to database
        $mysqli = new mysqli("localhost", "2408369", "Tuba@1230000", "db2408369");

        if ($mysqli->connect_errno) {
            // Reverted to a standard, less dramatic error message
            echo "<p class='error-message'>ERROR: Failed to connect to MySQL: " . $mysqli->connect_error . "</p>";
        }

        // Run SQL query
        $sql = "SELECT * FROM games ORDER BY rating DESC";
        $results = isset($mysqli) && !$mysqli->connect_errno ? $mysqli->query($sql) : null;
        ?>

        <form action="search.php" method="post">
        <input type="text" name="keywords" placeholder="Search for a game...">
        <input type="submit" value="Search"> </form>
	 <a href="add-game-form.php" class="btn btn-primary">Add a game</a>


        <table>
            <thead>
                <tr>
                    <th> Game Name</th>
                    <th> Release Date</th>
                    <th> IMDB Rating</th>
					
                </tr>
            </thead>
            <tbody>
                <?php
                // Mock data if the connection failed, to show the style
                if (!$results || (isset($results) && $results->num_rows === 0)) {
                    $mock_data = [
                        ['game_name' => 'Desert Runner 404', 'released_date' => '2077-10-23', 'rating' => '9.2'],
                        ['game_name' => 'Spice Harvester: The Board Game', 'released_date' => '1984-12-18', 'rating' => '8.8'],
                        ['game_name' => 'Neon District Protocol', 'released_date' => '2049-05-15', 'rating' => '7.9'],
                        ['game_name' => 'Fremen Tactics Simulator', 'released_date' => '2025-01-01', 'rating' => '9.5']
                    ];
                    foreach ($mock_data as $index => $row):
                ?>
                <tr>
                    <td>
                        <a href="details.php?id=<?= $index + 100 ?>">
                            <?= htmlspecialchars($row['game_name']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($row['released_date']) ?></td>
                    <td>
                        <span style="color: #FF1493; text-shadow: 0 0 10px rgba(255, 20, 147, 0.9);">
                            <?= htmlspecialchars($row['rating']) ?>
                        </span>
                    </td>
                    <td> <a href="delete.php?id=<?= $index + 100 ?>"
                           onclick="return confirm('Are you sure you want to delete \'<?= htmlspecialchars($row['game_name']) ?>\'?');"
                           class="delete-btn">
                            [DELETE]
                        </a>
                    </td>
                </tr>
                <?php
                    endforeach;
                } else {
                    // Original PHP logic if connection succeeded
                    while ($a_row = $results->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <a href="details.php?id=<?= htmlspecialchars($a_row['game_ID']) ?>">
                                <?= htmlspecialchars($a_row['game_name']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($a_row['released_date']) ?></td>
                        <td>
                            <span style="color: #FF1493; text-shadow: 0 0 10px rgba(255, 20, 147, 0.9);">
                                <?= htmlspecialchars($a_row['rating']) ?>
                            </span>
                        </td>
                        <td> <a href="delete.php?id=<?= htmlspecialchars($a_row['game_ID']) ?>" 
                               onclick="return confirm('Are you sure you want to delete \'<?= htmlspecialchars($a_row['game_name']) ?>\'?');"
                               class="delete-btn">
                                [DELETE]
                            </a>
                        </td>
						
					<td> 
                        <a href="edit.php?ID=<?= htmlspecialchars($a_row['game_ID']) ?>" class="edit-btn">
        [EDIT]
    </a>
                    </td>
                    </tr>
                    <?php endwhile;
                }
                ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>&copy; <?= date("Y") ?> **Game Archive** | Data Stable</p> </footer>
</body>
</html>