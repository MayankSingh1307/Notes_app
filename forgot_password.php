<?php
require 'db.php';
$forgot_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows) {
        $forgot_msg = "Reset link sent (simulated)";
    } else {
        $forgot_msg = "Email not found!";
    }
}
?>
<h2>Forgot Password</h2>
<form method="POST">
    <input type="email" name="email" required placeholder="Registered Email"><br>
    <button type="submit">Send Reset Link</button>
</form>
<p style="color:green"><?= $forgot_msg ?></p>
<a href="login.php">Back to Login</a>
