<?php
// FORCE HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect");
    exit();
}

session_start();
include('config.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login_secure.php");
    exit;
}

$review_id = (int)$_GET['id'];
$movie_id = (int)$_GET['movie_id'];
$user_id = $_SESSION['user_id'];

// FETCH REVIEW TO VERIFY OWNERSHIP OR ADMIN PRIVILEGE
$stmt = $conn->prepare("SELECT * FROM reviews WHERE id = ?");
$stmt->bind_param("i", $review_id);
$stmt->execute();
$result = $stmt->get_result();
$review = $result->fetch_assoc();
$stmt->close();

// ROLE-BASED ACCESS CONTROL (RBAC) ENFORCEMENT (Server-side)
// Although the Edit/Delete buttons are conditionally shown on the frontend,
// this check is still necessary to protect against manual URL tampering.
if (!$review || ($review['user_id'] != $user_id && $_SESSION['role'] !== 'admin')) {
    exit("Unauthorized");
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_review = $_POST['review'];
    $stmt = $conn->prepare("UPDATE reviews SET review_content = ? WHERE id = ?");
    $stmt->bind_param("si", $new_review, $review_id);
    $stmt->execute();
    $stmt->close();
    header("Location: reviews_secure.php?movie_id=$movie_id");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Review</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <div class="login-logo">
        <img src="images/Popcorn opinions logo.png" alt="Popcorn Logo" class="logo-img">
        <div class="app-title">Popcorn Opinions</div>
    </div>
    <h2>Edit Review</h2>
    <form method="POST">
        <textarea name="review" rows="4"><?= htmlspecialchars($review['review_content']) ?></textarea><br>        
        <button type="submit">Save</button>
    </form>
</div>
</body>
</html>
