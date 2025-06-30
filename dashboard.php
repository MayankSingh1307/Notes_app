<?php
session_start();
if (!isset($_SESSION['user'])) header("Location: login.php");
$user = $_SESSION['user'];
?>
<h2>Hi, <?= $user['username'] ?></h2>
<div style="float:right">
    <img src="<?= $user['profile_image'] ?? 'default.png' ?>" width="40" height="40" style="border-radius:50%;" onclick="toggleDropdown()" />
    <div id="dropdown" style="display:none; position:absolute; right:10px; background:#ddd; padding:10px;">
        <a href="profile.php">My Profile</a><br>
        <a href="logout.php">Logout</a>
    </div>
</div>
<script>
    function toggleDropdown() {
        let d = document.getElementById('dropdown');
        d.style.display = d.style.display === 'block' ? 'none' : 'block';
    }
</script>
