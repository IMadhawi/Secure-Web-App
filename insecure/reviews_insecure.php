<?php
// Start session to access user data
session_start();

// Include database configuration
include('config.php');

// Redirect to login page if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login_insecure.php");
    exit;
}

// Get user ID and movie ID from session and URL
$user_id = $_SESSION['user_id'];
$movie_id = isset($_GET['movie_id']) ? (int)$_GET['movie_id'] : 0;

// Handle new review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review']) && $movie_id) {
    $review = $_POST['review'];

    // Insert new review using prepared statement (secure)
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, movie_id, review_content) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $movie_id, $review);
    $stmt->execute();
    $stmt->close();
}

// Fetch movie details
$movie = null;
$stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();
$stmt->close();

// Fetch all reviews for the selected movie along with usernames
$reviews = [];
$stmt = $conn->prepare("
    SELECT reviews.*, users.username 
    FROM reviews 
    JOIN users ON reviews.user_id = users.id 
    WHERE movie_id = ?
");
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reviews - <?= htmlspecialchars($movie['title']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <div class="login-logo">
        <img src="images/Popcorn opinions logo.png" alt="Popcorn Logo" class="logo-img">
        <div class="app-title">Popcorn Opinions</div>
    </div>
    <h2>Reviews for <?= htmlspecialchars($movie['title']) ?></h2>

    <!-- Form to post a new review -->
    <form method="POST">
        <textarea name="review" required placeholder="Write your review..."></textarea><br>
        <button type="submit">Post Review</button>
    </form>

    <hr>
    <!-- Display all reviews -->
    <?php foreach ($reviews as $r): ?>
        <p><strong><?= htmlspecialchars($r['username']) ?>:</strong><br>

        <!-- XSS VULNERABILITY -->
        <!-- If a user submits <script>alert('XSS')</script>, it will execute here -->
        <?= $r['review_content'] ?>

        <!-- No access control check (anyone can see edit/delete buttons) -->
        <!-- RBAC is ignored — user or admin logic is missing -->
        <a href="edit_review_insecure.php?id=<?= $r['id'] ?>&movie_id=<?= $movie_id ?>">Edit</a>
        <a href="delete_review_insecure.php?id=<?= $r['id'] ?>&movie_id=<?= $movie_id ?>">Delete</a>
    <hr>

    <?php endforeach; ?>
</div>
</body>
</html>
