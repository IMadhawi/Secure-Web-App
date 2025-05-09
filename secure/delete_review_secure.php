<?php
// Start the session to access logged-in user info
session_start();

// Include database connection
include('config.php');

// Redirect if user is not logged in or no review ID provided
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login_secure.php");
    exit;
}

// Get the review ID and movie ID from the URL
$review_id = (int)$_GET['id'];
$movie_id = (int)$_GET['movie_id'];
$user_id = $_SESSION['user_id'];

// Prepare a query to get the review's owner
$stmt = $conn->prepare("SELECT user_id FROM reviews WHERE id = ?");
$stmt->bind_param("i", $review_id);
$stmt->execute();
$stmt->bind_result($owner_id);
$stmt->fetch();
$stmt->close();

// ROLE-BASED ACCESS CONTROL (RBAC) ENFORCEMENT (Server-side)
// Although the Edit/Delete buttons are conditionally shown on the frontend,
// this check is still necessary to protect against manual URL tampering.
if ($owner_id != $user_id && $_SESSION['role'] !== 'admin') {
    exit("Unauthorized");
}

// Authorized: Delete the review
$stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
$stmt->bind_param("i", $review_id);
$stmt->execute();
$stmt->close();

// Redirect back to the reviews page for the same movie
header("Location: reviews_secure.php?movie_id=$movie_id");
exit;
?>
