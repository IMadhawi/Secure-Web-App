<?php
// FORCE HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect");
    exit();
}

include('config.php');

// SESSION SECURITY CONFIGURATION
//  We configure the session cookie with the following properties:
// - secure: Ensures the session cookie is sent only over HTTPS (prevents sniffing on public networks)
// - httponly: Prevents JavaScript from accessing the session cookie (protects against XSS)
// - samesite: Prevents CSRF by disallowing cross-site usage of the cookie
session_set_cookie_params([
    'secure' => true,     
    'httponly' => true,     
    'samesite' => 'Strict'
]);

session_start();
// SESSION FIXATION PROTECTION:
// We regenerate the session ID after login to prevent session fixation attacks,
// where an attacker might force a user to use a known session ID.
session_regenerate_id(true); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username and password
    $username = $_POST['username'];
    $password = $_POST['password']; // Plain-text user input, will be verified securely

    // SQL INJECTION PREVENTION:
    // We use prepared statements with placeholders (?) to separate SQL logic from user input.
    // This protects against injection attacks like: ' OR '1'='1
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // SECURE PASSWORD VERIFICATION:
        // We use password_verify() to compare the entered password with the hashed password.
        // The password was hashed using bcrypt at registration time.
        //   Why bcrypt?
        // - It’s slow and computationally expensive → resists brute-force attacks
        // - It includes automatic salting → protects against rainbow tables (precomputed hash lookups)
        if (password_verify($password, $user['password'])) {
            
            // MINIMAL SESSION DATA:
            // We only store necessary data in the session (no passwords).
            // These values are used to personalize the session and enforce role-based access control.
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Used for RBAC in secure pages
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard_secure.php"); // Redirect to dashboard after successful login
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Secure Login - CineRate</title>
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
            <p class="signup-link">Don't have an account? <a href="register_secure.php">SignUp</a></p>
        </form>
    </div>
</body>
</html>
