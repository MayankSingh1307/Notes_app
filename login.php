<?php
session_start();
require 'db.php';

if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}

$login_error = '';
$email = '';
$password = '';

if (isset($_COOKIE['remember_email']) && isset($_COOKIE['remember_password'])) {
    $email = $_COOKIE['remember_email'];
    $password = $_COOKIE['remember_password'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];
    $remember = isset($_POST['remember']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {
        $user = $res->fetch_assoc();
        if (password_verify($password_input, $user['password'])) {
            $_SESSION['user'] = $user;

            if ($remember) {
                setcookie('remember_email', $email, time() + (86400 * 30), "/"); // 30 days
                setcookie('remember_password', $password_input, time() + (86400 * 30), "/");
            } else {
                setcookie('remember_email', '', time() - 3600, "/");
                setcookie('remember_password', '', time() - 3600, "/");
            }

            header("Location: dashboard.php");
            exit;
        } else {
            $login_error = "Invalid password.";
        }
    } else {
        $login_error = "User not found.";
    }
}
?>

<h2>Login</h2>
<form method="POST">
    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required placeholder="Email"><br>
    <input type="password" name="password" value="<?= htmlspecialchars($password) ?>" required placeholder="Password"><br>
    <label><input type="checkbox" name="remember" <?= isset($_COOKIE['remember_email']) ? 'checked' : '' ?>> Remember Me</label><br>
    <button type="submit">Login</button>
</form>
<p style="color:red"><?= $login_error ?></p>
<a href="register.php">Register</a> | <a href="forgot_password.php">Forgot Password?</a>
