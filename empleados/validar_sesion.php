<?php
// Verifica si la sesión ya está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Solo inicia la sesión si no está activa
}

// Verifica si la sesión está activa
if (!isset($_SESSION['usuario'])) {
    // Si no está logueado, redirige al login
    header('Location: login.php');
    exit(); // Detiene la ejecución del código
}
?>
