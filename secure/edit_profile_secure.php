<?php
// FORCE HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect");
    exit();
}

session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login_secure.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$bio = "";

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_bio = $_POST['bio'];
    $stmt = $conn->prepare("UPDATE users SET bio = ? WHERE id = ?");
    $stmt->bind_param("si", $new_bio, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard_secure.php");
    exit;
}

// Fetch current bio
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
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container1">
    <h2>Edit Your Bio</h2>
    <form method="POST">
        <!-- XSS PREVENTION -->
        <!-- htmlspecialchars() is used to escape special characters in the bio text,
        which prevents execution of any malicious scripts -->
        <textarea name="bio" rows="4"><?= htmlspecialchars($bio) ?></textarea><br>
        <button type="submit">Update</button>
    </form>
</div>
</body>
</html>