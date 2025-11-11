<?php
require "funciones/conecta.php";
$con = conecta();
include 'menu.php';
include('validar_sesion.php');

// Obtener el ID del producto desde la URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consultar los datos del producto
$sql = "SELECT nombre, codigo, descripcion, archivo FROM productos WHERE id = ? AND eliminado = 0";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $producto = $result->fetch_assoc();
} else {
    echo "Producto no encontrado.";
    exit;
}

$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Producto</title>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #fffaf0;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #ffb6b9;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            font-family: 'Pacifico', cursive;
        }
        header h1 {
            font-size: 2.5rem;
            margin: 0;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 0 15px rgba(255, 182, 193, 0.3);
            text-align: center;
        }
        img {
            max-width: 100%;
            height: auto;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        .label {
            font-weight: bold;
            color: #ff6f61;
            font-size: 1.1rem;
        }
        .value {
            margin-bottom: 20px;
            font-size: 1rem;
            color: #333;
        }
        .en-adopcion {
            font-size: 1.2rem;
            color: #ff6f61;
            font-weight: bold;
            margin: 15px 0;
        }
        button {
            background-color: #ffb6b9;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #ff6f61;
        }
        a {
            text-decoration: none;
            color: white;
        }
        .gif-decorativo {
            height: 50px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>🐶 Detalle del Amiguito 🐱</h1>
    </header>

    <div class="container">
        <img src="uploads_productos/<?= htmlspecialchars($producto['archivo']) ?>" alt="Imagen del producto">

        <div>
            <span class="label">Nombre:</span>
            <div class="value"><?= htmlspecialchars($producto['nombre']) ?></div>
        </div>

        <div>
            <span class="label">Código:</span>
            <div class="value"><?= htmlspecialchars($producto['codigo']) ?></div>
        </div>

        <div>
            <span class="label">Descripción:</span>
            <div class="value"><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></div>
        </div>

        <div class="en-adopcion">EN ADOPCIÓN</div>

        <img src="https://media.tenor.com/7Vw7Km4Nip4AAAAi/cat-love.gif" alt="Decoración" class="gif-decorativo">

        <br><br>
        <button><a href="productos_lista.php">Regresar al Listado</a></button>
    </div>
</body>
</html>
