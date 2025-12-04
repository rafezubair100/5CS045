<?php
// Start output buffering to prevent "Headers already sent" errors
ob_start();
session_start();

// Check if the user is NOT logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Connect to database (This must also happen BEFORE HTML output)
// **NOTE: Please verify and update your database credentials here!**
$mysqli = new mysqli("localhost", "2408369", "Tuba@1230000", "db2408369");

if ($mysqli->connect_errno) {
    // This part can output an error because it stops execution
    die("<p class='error-message'>ERROR: Failed to connect to MySQL: " . $mysqli->connect_error . "</p>");
}

// Run SQL query for the DEFAULT display (all games)
$sql = "SELECT * FROM games ORDER BY rating DESC";
$results = $mysqli->query($sql);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Archive</title>
    <style>
       /* STYLES (Kept for completeness, based on your files) */
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

        header {
            background-color: rgba(20, 25, 30, 0.95);
            width: 100%;
            padding: 20px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
            display: flex;
            flex-direction: column; 
            align-items: center;
        }

        h1 {
            color: #C7F464; /* Neon Green */
            text-shadow: 0 0 10px rgba(199, 244, 100, 0.8);
            margin-bottom: 10px;
            font-size: 2.5rem;
        }

        nav a {
            color: #4dd0e1; /* Cyan/Teal */
            text-decoration: none;
            padding: 10px 15px;
            margin: 0 10px;
            transition: color 0.3s, text-shadow 0.3s;
        }

        nav a:hover {
            color: #FF1493; /* Neon Pink */
            text-shadow: 0 0 10px rgba(255, 20, 147, 0.8);
        }
        
        /* NEW: Search Bar Styles */
        .search-container {
            width: 80%;
            max-width: 400px;
            margin: 10px 0 20px 0;
        }

        #live-search-input {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #4dd0e1; /* Cyan Border */
            border-radius: 5px;
            background-color: #1A1F27; /* Dark background */
            color: #F5F5F5;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        #live-search-input:focus {
            outline: none;
            border-color: #C7F464; /* Focus color neon green */
            box-shadow: 0 0 8px rgba(199, 244, 100, 0.6);
        }

        .table-responsive {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #252a34; /* Slightly lighter inner background */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            overflow: hidden; 
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #363b45;
        }

        th {
            background-color: #1A1F27;
            color: #C7F464;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        tr:hover td {
            background-color: #2e353f;
        }

        a {
            color: #4dd0e1;
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: #FF1493;
        }

        .delete-btn {
            color: #FF6B6B; /* Soft Red */
        }

        .edit-btn {
            color: #C7F464; /* Neon Green */
        }

        footer {
            margin-top: auto;
            padding: 15px 0;
            background-color: rgba(20, 25, 30, 0.95);
            width: 100%;
            text-align: center;
            color: #888;
            font-size: 0.8rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            /* ... (existing responsive styles) ... */
            table, thead, tbody, th, td, tr { display: block; }
            thead tr { position: absolute; top: -9999px; left: -9999px; }
            tr { border: 1px solid #363b45; margin-bottom: 10px; border-radius: 5px; }
            td { border: none; position: relative; padding-left: 50%; text-align: right; }
            td:before {
                content: attr(data-label);
                position: absolute;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
                color: #C7F464;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>🎮 Game Archive</h1>
        <nav>
            <a href="add-game-form.php">Add New Game</a>
            <a href="logout.php">Logout</a>
        </nav>
        
        <div class="search-container">
            <input type="text" id="live-search-input" placeholder="Live search by game name..." autocomplete="off">
        </div>
        </header>

    <main class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Game</th>
                    <th>Date Released</th>
                    <th>IMDB Rating</th>
                    <th>Actions</th>
                </tr>
            </thead>
            
            <tbody id="results-body">
                <?php
                // This is the default display of all games
                if ($results && $results->num_rows > 0) {
                    while ($a_row = $results->fetch_assoc()): 
                ?>
                    <tr>
                        <td data-label="Game Name">
                            <a href="details.php?id=<?= htmlspecialchars($a_row['game_ID']) ?>">
                                <?= htmlspecialchars($a_row['game_name']) ?>
                            </a>
                        </td>
                        <td data-label="Release Date"><?= htmlspecialchars($a_row['released_date']) ?></td>
                        <td data-label="IMDB Rating">
                            <span style="color: #FF1493; text-shadow: 0 0 10px rgba(255, 20, 147, 0.9);">
                                <?= htmlspecialchars($a_row['rating']) ?>
                            </span>
                        </td>
                        <td data-label="Actions"> 
                             <a href="delete.php?id=<?= htmlspecialchars($a_row['game_ID']) ?>" 
                               onclick="return confirm('Are you sure you want to delete \'<?= htmlspecialchars($a_row['game_name']) ?>\'?');"
                               class="delete-btn">
                                [DELETE]
                            </a>
                            <a href="edit.php?ID=<?= htmlspecialchars($a_row['game_ID']) ?>" class="edit-btn">
                                [EDIT]
                            </a>
                        </td>
                    </tr>
                    <?php endwhile;
                } else {
                    // Display message if no games are found initially
                    echo "<tr><td colspan='4' style='text-align: center; color: #888;'>No games in the archive.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> **Game Archive** | Data Stable</p> 
    </footer>
    
    <script>
    // Get the DOM elements
    const searchInput = document.getElementById('live-search-input');
    const resultsBody = document.getElementById('results-body');
    
    // Check if elements exist before attaching listeners
    if (searchInput && resultsBody) {

        // Store the initial content of the table body after the page loads. 
        // This is crucial for restoring the full list when the search box is cleared.
        const defaultBodyContent = resultsBody.innerHTML; 

        let debounceTimeout;

        // Listen for user typing in the search box
        searchInput.addEventListener('input', function() {
            // 1. Debounce: Clear the previous timer and set a new one
            // This prevents an AJAX request on *every* single keystroke, improving performance.
            clearTimeout(debounceTimeout);
            
            // Wait 300ms after the last keypress before searching
            debounceTimeout = setTimeout(() => {
                const keywords = searchInput.value.trim();

                if (keywords.length > 0) {
                    
                    // Show a temporary loading message to the user
                    resultsBody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #FF1493;">Loading results...</td></tr>';

                    // 2. Make the AJAX request using the native Fetch API
                    // Sends the keywords to live_search.php as a GET parameter
                    fetch(`live_search.php?keywords=${encodeURIComponent(keywords)}`)
                        .then(response => {
                            // Check for HTTP errors (e.g., 404, 500)
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            // 3. Get the response body as plain text (which is the HTML rows)
                            return response.text();
                        })
                        .then(html => {
                            // 4. Update the table body with the server's HTML response
                            resultsBody.innerHTML = html;
                        })
                        .catch(error => {
                            // Handle network or server errors
                            console.error('Fetch error:', error);
                            resultsBody.innerHTML = `<tr><td colspan='4' style='color: red;'>Error fetching results: ${error.message}</td></tr>`;
                        });
                } else {
                    // If the search box is empty, restore the original list of all games
                    resultsBody.innerHTML = defaultBodyContent;
                }
            }, 300); // 300ms debounce time
        });
    }
</script>
</body>
</html>
<?php
// End output buffering and flush it (sends all the content to the browser)
ob_end_flush();
?>