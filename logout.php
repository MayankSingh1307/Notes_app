<?php
session_start();
session_unset();
session_destroy();

setcookie(session_name(), '', time() - 3600, '/');
setcookie('remember_email', '', time() - 3600, '/');
setcookie('remember_password', '', time() - 3600, '/');

header("Location: login.php");
exit();
