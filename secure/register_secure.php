<?php
// FORCE HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect");
    exit();
}

include 'config.php'; // Database connection and session start

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = ($_POST['username']);
    $password = $_POST['password']; // Will be hashed securely, never stored in plaintext

    // Secure password hashing using bcrypt via password_hash()
    // Why bcrypt?
    // - Slow by design → resists brute-force attacks
    // - Built-in salt → each hash is unique even if password is the same
    // - Recommended by PHP, OWASP, and NIST for password storage
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Prepared SQL statement
    // Why?
    // - Prevents SQL Injection by keeping data and SQL structure separate
    // - Replaces variables with safe placeholders (?)
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
    
    // Binds parameters safely
    // 'ss' = two string values; data is bound and escaped by the database engine
    $stmt->bind_param("ss", $username, $hashed_password);
    
    // Executes the query securely
    $stmt->execute();

    // Redirects user to login page
    header("Location: login_secure.php");
}
?>
<html>
    <h1>Register</h1>
    <form id="registerform" method="POST">
        <label for="username">Username</label>
        <input name="username" required>
        <label for="password">Password</label>
        <input name="password" type="password" required>
        <button type="submit">Register</button>
    </form>
</html>