<?php
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Insecure password hashing using MD5
    $hashed_password = md5($password);

    // Vulnerable SQL query
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
    $result = mysqli_query($conn, $query);

    // Check if user exists
    if ($result && mysqli_num_rows($result) > 0) {
        // Only store the username in the session
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - CineRate</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
