<?php
// Use output buffering to ensure the header redirect works, even if
// there are stray characters/whitespace before the opening PHP tag.
ob_start();

// 1. Start the session
session_start();

// 2. Clear all session variables
$_SESSION = array();

// 3. Destroy the session cookie (if it exists)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Destroy the session on the server
session_destroy();

// 5. Redirect the user to the login page
header("Location: login.php");
exit;
?>