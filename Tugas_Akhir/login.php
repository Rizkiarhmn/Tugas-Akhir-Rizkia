<?php
session_start();

class LoginHandler {

    private $valid_users;

    public function __construct() {
        $this->valid_users = [
            'admin' => 'adminpassword', 
        ];
    }
    // Validasi Admin
    public function checkLogin($username, $password) {
        if (isset($this->valid_users[$username]) && $this->valid_users[$username] === $password) {
            $_SESSION['username'] = $username; 
            return true; 
        } else {
            return false; 
        }
    }
}

$loginHandler = new LoginHandler();

// Hasil ketika dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($loginHandler->checkLogin($username, $password)) {
        header('Location: dashboard.php'); 
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h1>Login Admin</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= $error; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
