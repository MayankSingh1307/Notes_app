<?php
require 'db.php';
$register_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $dob        = $_POST['dob'];
    $role       = $_POST['role'];
    $bio        = trim($_POST['bio']);
    $hobbies    = isset($_POST['hobbies']) ? implode(", ", $_POST['hobbies']) : "";

    $profile_image = "default.png";
    if (!empty($_FILES['profile_image']['name'])) {
        $img_name = time() . "_" . basename($_FILES['profile_image']['name']);
        $target = "uploads/" . $img_name;
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target)) {
            $profile_image = $target;
        }
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows) {
        $register_msg = "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, dob, role, hobbies, bio, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $username, $email, $password, $dob, $role, $hobbies, $bio, $profile_image);
        $stmt->execute();
        $register_msg = "Registration successful! <a href='login.php'>Login</a>";
    }
}
?>

<h2>Register</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="username" required placeholder="Username"><br>
    <input type="email" name="email" required placeholder="Email"><br>
    <input type="password" name="password" required placeholder="Password"><br>

    <label>DOB:</label><br>
    <input type="date" name="dob" required><br>

    <label>Role:</label><br>
    <select name="role" required>
        <option value="">Select Role</option>
        <option value="admin">Admin</option>
        <option value="editor">Editor</option>
        <option value="viewer">Viewer</option>
    </select><br>

    <label>Hobbies:</label><br>
    <input type="checkbox" name="hobbies[]" value="Reading"> Reading
    <input type="checkbox" name="hobbies[]" value="Traveling"> Traveling
    <input type="checkbox" name="hobbies[]" value="Coding"> Coding
    <input type="checkbox" name="hobbies[]" value="Gaming"> Gaming<br>

    <label>Bio:</label><br>
    <textarea name="bio" rows="3" cols="30" placeholder="Write about yourself..."></textarea><br>

    <label>Profile Photo:</label><br>
    <input type="file" name="profile_image"><br><br>

    <button type="submit">Register</button>
</form>
<p style="color:green"><?= $register_msg ?></p>
<a href="login.php">Back to Login</a>
