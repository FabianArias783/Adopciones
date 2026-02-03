<?php
session_start();

// Destruir todas las sesiones
session_unset();
session_destroy();

// Redirigir a la página de inicio o login
header("Location: login_cliente.php");
exit;
?>
