<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login_insecure.php");
    exit;
}

// Fetch movies
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <div class="login-logo">
        <img src="images/Popcorn opinions logo.png" alt="Popcorn Logo" class="logo-img">
        <div class="app-title">Popcorn Opinions</div>
    </div>
    <h2>Choose a Movie</h2>
    <div class="movie-grid">
        <?php foreach ($movies as $movie): ?>
            <div class="movie-card">
                <img src="<?= htmlspecialchars($movie['poster_path']) ?>" alt="poster" height="200"><br>
                <strong><?= htmlspecialchars($movie['title']) ?></strong><br>
                <a href="reviews_insecure.php?movie_id=<?= urlencode($movie['id']) ?>">Read Reviews</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
