<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login_insecure.php");
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

    header("Location: dashboard_insecure.php");
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
<div class="form-container">
    <div class="login-logo">
        <img src="images/Popcorn opinions logo.png" alt="Popcorn Logo" class="logo-img">
        <div class="app-title">Popcorn Opinions</div>
    </div>
    <h2>Edit Your Bio</h2>
    <form method="POST">

    <!-- XSS VULNERABILITY: Bio is printed directly without sanitization -->
    <!-- An attacker could inject something like: <script>alert('Hacked!')</script> -->
        <textarea name="bio" rows="4"><?= $bio ?></textarea>
        <button type="submit">Update</button>
    </form>
</div>
</body>
</html>
