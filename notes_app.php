<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ MySQLi Procedural Connection
$conn = mysqli_connect("localhost", "admin", "redhat", "notes_app");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ✅ Add Note
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_note'])) {
    $content = trim($_POST['note_content']);
    if (!empty($content)) {
        $content = mysqli_real_escape_string($conn, $content);
        $sql = "INSERT INTO notes (content) VALUES ('$content')";
        mysqli_query($conn, $sql);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// ✅ Delete Note
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM notes WHERE id = $id";
    mysqli_query($conn, $sql);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ✅ Fetch Notes
$notes = [];
$result = mysqli_query($conn, "SELECT * FROM notes ORDER BY created_at DESC");
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $notes[] = $row;
    }
}
?>

<!-- ✅ HTML UI -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Note App - MySQLi Procedural</title>
  <style>
    body { font-family: Arial; background: #f4f4f4; padding: 20px; }
    h1 { color: #333; }
    form { margin-bottom: 20px; }
    textarea { width: 100%; height: 80px; padding: 10px; font-size: 16px; margin-bottom: 10px; }
    button { padding: 10px 20px; background: #007bff; border: none; color: white; cursor: pointer; }
    .note { background: white; padding: 15px; margin-bottom: 10px; border-left: 5px solid #007bff; position: relative; }
    .note small { color: gray; }
    .delete-link { position: absolute; right: 10px; top: 10px; color: red; text-decoration: none; font-weight: bold; }
  </style>
</head>
<body>

<h1>📝 My Notes</h1>

<form method="POST">
  <textarea name="note_content" placeholder="Write your note..."></textarea>
  <button type="submit" name="add_note">Add Note</button>
</form>

<?php if (count($notes) > 0): ?>
  <?php foreach ($notes as $note): ?>
    <div class="note">
      <a href="?delete=<?= $note['id'] ?>" class="delete-link" onclick="return confirm('Delete this note?')">&times;</a>
      <p><?= htmlspecialchars($note['content']) ?></p>
      <small><?= $note['created_at'] ?></small>
    </div>
  <?php endforeach; ?>
<?php else: ?>
  <p>No notes found.</p>
<?php endif; ?>

</body>
</html>
