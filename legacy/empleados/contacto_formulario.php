<?php
session_start(); // Asegúrate de que la sesión esté iniciada

// Verificar si el cliente está autenticado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login_cliente.php");
    exit(); // Detener la ejecución del código
}

include 'menu_cliente.php';
include 'funciones/conecta.php';
$con = conecta();
$cliente_id = $_SESSION['cliente_id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Contacto</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header-bar {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 1.5rem;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-size: 1rem;
            color: #333;
        }
        input, textarea {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }
        button {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            width: 150px;
            margin-top: 10px;
        }
        button:hover {
            background-color: #555;
        }
        .boton-accion {
            display: inline-block;
            text-decoration: none;
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            text-align: center;
            margin-top: 20px;
        }
        .boton-accion:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="header-bar">Formulario de Contacto</div>

    <div class="container">
        <form action="contacto_envia.php" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje" rows="5" required></textarea>

            <button type="submit">Enviar</button>
        </form>

        <a class="boton-accion" href="home.php">Regresar</a>
    </div>
</body>
</html>
