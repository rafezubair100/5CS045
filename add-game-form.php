<?php
session_start();

// Check if the user is NOT logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add a game</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
crossorigin="anonymous">
</head>
<body>
<div class="container">
<h1>Add a game</h1>
<form action="add-game.php" method="post">
<div class="mb-3">
<label for="GameName" class="form-label">Game name</label>
<input type="text" class="form-control" Id="GameName" name="GameName">
</div>
<div class="mb-3">
<label for="GameDescription" class="form-label">Description</label>
<textarea class="form-control" Id="GameDescription" name="GameDescription" rows="5"></textarea>
</div>
<div class="mb-3">
<label for="DateReleased" class="form-label">Date Released</label>
<input type="date" class="form-control" Id="DateReleased" name="DateReleased">
</div>
<div class="mb-3">
<label for="GameRating" class="form-label">Game Rating (1-10)</label>
<input type="text" class="form-control" Id="GameRating" name="GameRating">
</div>
<button type="submit" class="btn btn-primary">Add Game</button>
<a href="games.php" class="btn btn-secondary">Cancel</a>
</form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
integrity="sha384-C6SgDGEjF38o1V1M7R4y71/X5qY6xY8zT8/5r7q6Q4r1k5f2u4b7z9v5T0D8F1O"
crossorigin="anonymous"></script>
</body>
</html>