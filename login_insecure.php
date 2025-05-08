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
        $user = $result->fetch_assoc();
        // Store required session data
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        
        header("Location: dashboard_insecure.php");
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
