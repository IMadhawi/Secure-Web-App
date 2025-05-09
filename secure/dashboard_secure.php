<?php
// START SESSION SECURELY
// Starts the session to access user-specific data like user_id, username, and role.
// In secure applications, this should be started after setting secure session cookie parameters (done in login).
session_start();

// Include the database connection file
include('config.php');

// AUTHENTICATION CHECK
// Only allow access if the user is logged in.
// If the session variable 'user_id' is not set, redirect to the secure login page.
if (!isset($_SESSION['user_id'])) {
    header("Location: login_secure.php");
    exit;
}

// Fetch the currently logged-in user's ID from the session
$user_id = $_SESSION['user_id'];
$bio = "";

// SQL INJECTION PREVENTION
// Uses a prepared statement to safely retrieve the user’s bio from the database.
// Why prepared statements?
// - Prevents SQL injection by treating input as data (not executable SQL)
$stmt = $conn->prepare("SELECT bio FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($bio);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="form-container">
    <!-- XSS PREVENTION -->
    <!-- htmlspecialchars() escapes special characters like <and> -->
    <!-- This prevents attackers from injecting JavaScript in usernames or bios -->
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
    <p><strong>Bio:</strong> <?= htmlspecialchars($bio) ?: 'No bio yet.' ?></p>
    <p>You are logged in as <strong><?= htmlspecialchars($_SESSION['role']) ?></strong>.</p>
    <a href="movies_secure.php">View Movies</a><br>
    <a href="edit_profile_secure.php">Edit Profile</a><br>
    <!-- Log out link (would ideally point to logout.php to destroy the session securely) -->
    <a href="login_secure.php">Log Out</a>
</div>
</body>
</html>

