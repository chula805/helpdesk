<?php
session_start();
include 'config/db.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Login</title>
</head>
<body>
    <div class="login-container">
                <h2>Login</h2>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required pattern="^[a-zA-Z0-9_]{3,15}$" title="Username should be 3-15 characters long and can contain letters, numbers, and underscores.">
                <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required minlength="8" title="Password must be at least 8 characters long.">
                <button type="submit" name="login">Login</button>
                </form>
           
    </div>
</body>
<script>
document.querySelector('form').addEventListener('submit', function(event) {
    let username = document.getElementById('username').value;
    let password = document.getElementById('password').value;
    
    if (username.length < 3 || username.length > 15) {
        alert("Username must be between 3 and 15 characters.");
        event.preventDefault(); // Stop form submission
    }
    
    if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
        event.preventDefault();
    }
});
</script>

</html>
