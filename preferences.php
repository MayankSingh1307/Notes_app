<?php
session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['theme'] = $_POST['theme'];
    $_SESSION['fontsize'] = $_POST['fontsize'];
}

// Defaults
$theme = $_SESSION['theme'] ?? 'light';
$fontsize = $_SESSION['fontsize'] ?? 'medium';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Store User Preferences (PHP Session)</title>
  <style>
    body {
      background: <?= $theme === 'dark' ? '#222' : '#fff' ?>;
      color: <?= $theme === 'dark' ? '#fff' : '#000' ?>;
      font-size: <?= $fontsize === 'small' ? '14px' : ($fontsize === 'large' ? '20px' : '16px') ?>;
      font-family: sans-serif;
      padding: 20px;
    }
  </style>
</head>
<body>

<h2>👤 Your Preferences (PHP Session)</h2>

<form method="POST">
  <label>Theme:</label>
  <select name="theme">
    <option value="light" <?= $theme === 'light' ? 'selected' : '' ?>>Light</option>
    <option value="dark" <?= $theme === 'dark' ? 'selected' : '' ?>>Dark</option>
  </select>

  <label>Font Size:</label>
  <select name="fontsize">
    <option value="small" <?= $fontsize === 'small' ? 'selected' : '' ?>>Small</option>
    <option value="medium" <?= $fontsize === 'medium' ? 'selected' : '' ?>>Medium</option>
    <option value="large" <?= $fontsize === 'large' ? 'selected' : '' ?>>Large</option>
  </select>

  <button type="submit">Save</button>
</form>

<p><strong>Stored in PHP session</strong></p>

</body>
</html>
