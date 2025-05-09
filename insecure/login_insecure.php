<?php
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // INSECURE: Weak password hashing using MD5
    // MD5 is fast and unsalted, which makes it vulnerable to:
    // - Rainbow table attacks (precomputed hash lookups)
    // - Brute-force attacks due to its speed
    // - Collisions (different inputs generating the same hash)
    $hashed_password = md5($password);

    // INSECURE: Vulnerable to SQL Injection
    // This query directly includes user input without validation or parameterization.
    // An attacker can inject something like: ' OR '1'='1 to bypass authentication
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
    $result = mysqli_query($conn, $query);

    // Check if user exists
    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch user data and store it in the session
        $user = $result->fetch_assoc();

        // No session hardening (e.g., session_regenerate_id not used)
        // This leaves the application vulnerable to session fixation attacks
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard_insecure.php"); // Redirect to insecure dashboard after successful login
        exit();
        
       
    } else {
        $error = "Invalid username or password.";
    }

    
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Popcprn Opinions</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <h2>Login🎬</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <!-- The form is functional, but back-end security is weak -->
        <form method="POST" action="">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Username"  required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder ="Password" required>
            <button type="submit">Login</button>
            <!-- Register link -->
            <p class="signup-link">Don't have an account? <a href="register_insecure.php">SignUp</a></p>
        </form>
    </div>
</body>
</html>
