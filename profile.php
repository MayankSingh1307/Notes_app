<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user'])) header("Location: login.php");

$user = $_SESSION['user'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $username = $_POST['username'];
    $uid = $user['id'];

    if (!empty($_FILES['profile_image']['name'])) {
        $target = "uploads/" . basename($_FILES['profile_image']['name']);
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $target);
        $stmt = $conn->prepare("UPDATE users SET username=?, profile_image=? WHERE id=?");
        $stmt->bind_param("ssi", $username, $target, $uid);
        $_SESSION['user']['profile_image'] = $target;
    } else {
        $stmt = $conn->prepare("UPDATE users SET username=? WHERE id=?");
        $stmt->bind_param("si", $username, $uid);
    }
    $stmt->execute();
    $_SESSION['user']['username'] = $username;
    $msg = "Profile updated!";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_pass'])) {
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    $pass_msg = '';

    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if (!password_verify($old, $res['password'])) {
        $pass_msg = "Old password incorrect.";
    } elseif ($new !== $confirm) {
        $pass_msg = "New passwords don't match.";
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $hash, $user['id']);
        $stmt->execute();
        $pass_msg = "Password changed!";
    }
}
?>
<h2>My Profile</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="username" value="<?= $user['username'] ?>" required><br>
    <input type="email" value="<?= $user['email'] ?>" readonly><br>
    <input type="file" name="profile_image"><br>
    <button type="submit" name="update">Update Info</button>
</form>
<p><?= $msg ?></p>

<h3>Change Password</h3>
<form method="POST">
    <input type="password" name="old_password" placeholder="Old Password" required><br>
    <input type="password" name="new_password" placeholder="New Password" required><br>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
    <button type="submit" name="change_pass">Change Password</button>
</form>
<p><?= $pass_msg ?? '' ?></p>
<a href="dashboard.php">Back to Dashboard</a>
