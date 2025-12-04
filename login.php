<?php
session_start();
// Redirect to games.php if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: games.php");
    exit;
}

// Include the database connection setup
$mysqli = new mysqli("localhost", "2408369", "Tuba@1230000", "db2408369");

if ($mysqli->connect_errno) {
    die("<p class='error-message'>ERROR: Failed to connect to MySQL: " . $mysqli->connect_error . "</p>");
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Use prepared statements to prevent SQL injection
    $stmt = $mysqli->prepare("SELECT user_ID, password FROM users WHERE username = ?");
    if (!$stmt) {
        $error = "Database preparation failed: " . $mysqli->error;
    } else {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verify the hashed password
            if (password_verify($password, $user['password'])) {
                // Login successful - create session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['user_ID'];
                $_SESSION['username'] = $username;

                // Redirect to the main game list page
                header("Location: games.php");
                exit;
            } else {
                // Invalid password
                $error = "Invalid username or password.";
            }
        } else {
            // Invalid username
            $error = "Invalid username or password.";
        }
        $stmt->close();
    }
}
$mysqli->close();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(145deg, #0D1117 0%, #1A1F27 100%); color: #F5F5F5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: #1A1F27; padding: 40px; border-radius: 8px; box-shadow: 0 0 20px rgba(90, 79, 207, 0.4); width: 100%; max-width: 380px; }
        h2 { text-align: center; color: #C7F464; margin-bottom: 25px; }
        label { display: block; margin-top: 15px; font-weight: 500; color: #4dd0e1; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #5A4FCF; border-radius: 4px; box-sizing: border-box; background: #0D1117; color: #F5F5F5; transition: border-color 0.3s; }
        input[type="text"]:focus, input[type="password"]:focus { border-color: #C7F464; outline: none; }
        button { margin-top: 30px; padding: 12px 25px; background: #C7F464; color: #0D1117; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-weight: bold; transition: background 0.3s, color 0.3s; }
        button:hover { background: #5A4FCF; color: #fff; }
        .error { color: #FF6B6B; margin-top: 10px; text-align: center; font-weight: 500; }
        .register-link { text-align: center; margin-top: 20px; font-size: 0.9em; }
        .register-link a { color: #4dd0e1; text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .register-link a:hover { color: #C7F464; }
    </style>
</head>
<body>
<div class="card">
    <h2>🔒 User Login</h2>
    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Log In</button>
    </form>

    <div class="register-link">
        Don't have an account? <a href="register.php">Register here</a>
    </div>
</div>
</body>
</html>