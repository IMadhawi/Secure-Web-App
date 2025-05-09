<?php
include 'config.php'; // Connects to the database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];

    //  Weak password hashing using MD5
    // MD5 is outdated and insecure for the following reasons:
    // - It's fast and unsalted, making it easy to brute-force
    // - Vulnerable to rainbow table attacks (precomputed hash lookups)
    $password = md5($_POST['password']);

    // Builds SQL by directly inserting user input (SQL Injection risk!)
    // An attacker can inject something like: ' OR '1'='1 to bypass authentication or corrupt data
    $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'user')";
    
    // Executes query without validation or protection
    $conn->query($sql);

    // Redirects to the login page
    header("Location: login_insecure.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>
<body class="login-page">
    <div class="login-container">
        <h2>Register</h2>
        <form method="POST">
            <label for="username">Username</label>
            <input name="username" placeholder = "Username" required>
            <label for="password">Password</label>
            <input name="password" type="password" placeholder = "Password" required>
            <button type="submit">Register</button>
        </form>
        <p class="signup-link">Already have an account? <a href="login_insecure.php">Login</a></p>
    </div>
</body>
</html>