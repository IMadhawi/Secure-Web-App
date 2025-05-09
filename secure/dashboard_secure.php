<?php
// FORCE HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect");
    exit();
}

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
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="dashboard-container">
    <!-- XSS PREVENTION -->
    <!-- htmlspecialchars() escapes special characters like <and> -->
    <!-- This prevents attackers from injecting JavaScript in usernames or bios -->
    <div class="dashboard-card">
        <header class="dashboard-header">
            <div class="login-logo">
                <img src="images/Popcorn opinions logo.png" alt="Popcorn Logo" class="logo-img">
                <div class="app-title">Popcorn Opinions</div>
            </div>
            <div class="welcome">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</div>
        </header>

        <main>
            <h2>Profile</h2>
            <p><strong>Username:</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($_SESSION['role']) ?></p>
            <p><strong>Bio:</strong> <?= htmlspecialchars($bio) ?: 'No bio yet.' ?></p>

            <div class="dashboard-buttons">
                <a href="edit_profile_secure.php" class="btn">✏️ Edit Profile</a>
                <a href="movies_secure.php" class="btn">🎬 View Movies</a>
                <a href="login_secure.php" class="btn logout">↩️ Log Out</a>
            </div>
        </main>
    </div>
</div>
</body>
</html>



