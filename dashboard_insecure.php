<?php
// Start the session to access user session variables
session_start();

// Include the database connection file
include('config.php');

// Check if the user is not logged in (no user_id in session)
if (!isset($_SESSION['user_id'])) {
    header("Location: login_insecure.php");
    exit;
}

// Get the current user's ID from the session
$user_id = $_SESSION['user_id'];
$bio = "";

// Securely fetch the user's bio using a prepared statement
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
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
    <p><strong>Bio:</strong> <?= htmlspecialchars($bio) ?: 'No bio yet.' ?></p>
    <p>You are logged in as <strong><?= htmlspecialchars($_SESSION['role']) ?></strong>.</p>
    <a href="movies_insecure.php">View Movies</a><br>
    <a href="edit_profile_insecure.php">Edit Profile</a><br>
    <!-- Insecure: this shows admin links to everyone -->
    <a href="admin.php">Admin Panel</a><br>
    <a href="login_insecure.php">Log Out</a>
</div>
</body>
</html>