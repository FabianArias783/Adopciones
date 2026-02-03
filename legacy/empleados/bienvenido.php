<?php
include 'menu.php';
// Inicia la sesión si no lo has hecho antes

// Verifica si el usuario está logueado
if (isset($_SESSION['usuario'])) {
    // Si el usuario está logueado, puedes acceder al nombre y ID del usuario
    $usuario_nombre = $_SESSION['usuario'];
    $usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;  // Verifica si existe el ID
} else {
    // Si no está logueado, redirige al login
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <meta http-equiv="refresh" content="5;url=empleados_lista.php">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .mensaje {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
            text-align: center;
        }
        p {
            margin: 10px 0;
        }
        .gif-bienvenida {
            max-width: 100%;
            height: auto;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="mensaje">
        <h1>¡Bienvenido, <?php echo htmlspecialchars($usuario_nombre); ?>!</h1>
        <!-- GIF de gatito -->
        <img class="gif-bienvenida" src="https://media.giphy.com/media/JIX9t2j0ZTN9S/giphy.gif" alt="Gatito Bienvenida">
        <p>Has iniciado sesión correctamente. Serás redirigido al listado de empleados en 5 segundos.</p>
    </div>
</body>
</html>

