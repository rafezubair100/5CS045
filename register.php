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
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Basic validation
    if (empty($username) || empty($password) || empty($password_confirm)) {
        $error = "All fields are required.";
    } elseif ($password !== $password_confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // 1. Check if username already exists using a prepared statement
        $check_stmt = $mysqli->prepare("SELECT user_ID FROM users WHERE username = ?");
        if (!$check_stmt) {
             $error = "Database preparation failed: " . $mysqli->error;
        } else {
            $check_stmt->bind_param("s", $username);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                $error = "This username is already taken.";
            } else {
                // 2. Hash the password securely
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // 3. Insert the new user into the database
                $insert_stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                if (!$insert_stmt) {
                     $error = "Database insertion preparation failed: " . $mysqli->error;
                } else {
                    $insert_stmt->bind_param("ss", $username, $hashed_password);

                    if ($insert_stmt->execute()) {
                        // Registration successful
                        $success = "Registration successful! You can now log in.";
                        // Optional: Redirect immediately to login page
                        // header("Location: login.php?registered=true");
                        // exit;
                    } else {
                        $error = "Registration failed: " . $insert_stmt->error;
                    }
                    $insert_stmt->close();
                }
            }
            $check_stmt->close();
        }
    }
}
$mysqli->close();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        .success { color: #C7F464; margin-top: 10px; text-align: center; font-weight: 500; }
        .login-link { text-align: center; margin-top: 20px; font-size: 0.9em; }
        .login-link a { color: #4dd0e1; text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .login-link a:hover { color: #C7F464; }
    </style>
</head>
<body>
<div class="card">
    <h2>📝 Register New User</h2>
    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="password_confirm">Confirm Password:</label>
        <input type="password" id="password_confirm" name="password_confirm" required>

        <button type="submit">Register</button>
    </form>

    <div class="login-link">
        Already have an account? <a href="login.php">Log in here</a>
    </div>
</div>
</body>
</html>