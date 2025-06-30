<?php
$conn = new mysqli("localhost", "admin", "redhat", "aauth_system");
if ($conn->connect_error) die("Database error: " . $conn->connect_error);
