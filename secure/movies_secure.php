<?php
// FORCE HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect");
    exit();
}

session_start();
include('config.php');

// AUTHENTICATION CHECK
// Only logged-in users can access this page.
// This ensures unauthorized users cannot access or view movies.
if (!isset($_SESSION['user_id'])) {
    header("Location: login_secure.php");
    exit;
}

// FETCH MOVIES SAFELY
// No need for a prepared statement since there's no external input involved.
$movies = [];
$result = $conn->query("SELECT * FROM movies");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Movies</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-container">
    <h2>Choose a Movie</h2>
    <div class="movie-grid">
        <?php foreach ($movies as $movie): ?>
            <div class="movie-card">
                <img src="<?= htmlspecialchars($movie['poster_path']) ?>" alt="poster" height="200"><br>
                <strong><?= htmlspecialchars($movie['title']) ?></strong><br>
                <a href="reviews_secure.php?movie_id=<?= urlencode($movie['id']) ?>">Read Reviews</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
