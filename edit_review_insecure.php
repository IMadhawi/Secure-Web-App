<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login_insecure.php");
    exit;
}

$review_id = (int)$_GET['id'];
$movie_id = (int)$_GET['movie_id'];
$user_id = $_SESSION['user_id'];

// Fetch the review to verify ownership/admin
$stmt = $conn->prepare("SELECT * FROM reviews WHERE id = ?");
$stmt->bind_param("i", $review_id);
$stmt->execute();
$result = $stmt->get_result();
$review = $result->fetch_assoc();
$stmt->close();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_review = $_POST['review'];
    $stmt = $conn->prepare("UPDATE reviews SET review_content = ? WHERE id = ?");
    $stmt->bind_param("si", $new_review, $review_id);
    $stmt->execute();
    $stmt->close();
    header("Location: reviews_insecure.php?movie_id=$movie_id");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Review</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-container">
    <h2>Edit Review</h2>
    <form method="POST">
        <textarea name="review" rows="4"><?= htmlspecialchars($review['review_content']) ?></textarea><br>
        <button type="submit">Save</button>
    </form>
</div>
</body>
</html>
