<?php
// Starts the session without any security settings (no cookie protection, no regeneration)
session_start();

// Include the database connection file
include('config.php');

// Check if the user is not logged in (no user_id in session)
if (!isset($_SESSION['user_id'])) {
    header("Location: login_insecure.php");
    exit;
}

// Fetch the currently logged-in user's ID from the session
$user_id = $_SESSION['user_id'];
$bio = "";

// fetch the user's bio using a prepared statement
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
    <div class="dashboard-card">
        <header class="dashboard-header">
            <div class="app-title">Popcorn Opinions</div>
            <div class="welcome">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</div>
        </header>

        <main>
            <h2>Profile</h2>
            <p><strong>Username:</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($_SESSION['role']) ?></p>
            <p><strong>Bio:</strong> <?= htmlspecialchars($bio) ?: 'No bio yet.' ?></p>

            <div class="dashboard-buttons">
                <a href="edit_profile_insecure.php" class="btn">✏️ Edit Profile</a>
                <a href="movies_insecure.php" class="btn">🎬 View Movies</a>
                <a href="login_insecure.php" class="btn logout">↩️ Log Out</a>
            </div>
        </main>
    </div>
</div>
</body>
</html>