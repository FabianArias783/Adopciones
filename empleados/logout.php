<?php
// Iniciar sesión y destruirla
session_start();
session_unset();
session_destroy();

// Redirigir a login (index.php)
header('Location: login.php');
exit();
?>
