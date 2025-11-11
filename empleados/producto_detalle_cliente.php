<?php
// Incluir el menú de navegación
include 'menu_cliente.php';
// Incluir la conexión a la base de datos
require 'funciones/conecta.php';
$con = conecta();

// Iniciar sesión para verificar si el usuario está logueado
session_start();
$cliente_logueado = isset($_SESSION['cliente_id']); // Se verifica si el cliente está logueado

// Verificar si se pasó el ID del producto en la URL
if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];

    // Obtener el producto desde la base de datos por su ID
    $sql = "SELECT * FROM productos WHERE id = $producto_id AND eliminado = 0"; // Solo productos no eliminados
    $res = $con->query($sql);

    if ($res->num_rows > 0) {
        $producto = $res->fetch_assoc();
    } else {
        die("Producto no encontrado.");
    }
} else {
    die("ID de producto no proporcionado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Producto</title>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
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
            margin: 20px auto;
        }
        .producto-detalle {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 0 15px rgba(255, 160, 122, 0.5);
        }
        .producto-detalle img {
            max-width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .producto-detalle h2 {
            font-family: 'Pacifico', cursive;
            font-size: 2.5rem;
            color: #ff6f61;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 3px 3px 5px rgba(255, 160, 122, 0.5);
        }
        .producto-detalle .descripcion {
            font-family: 'Quicksand', sans-serif;
            font-size: 1.2rem;
            margin-bottom: 20px;
            text-align: justify;
            color: #666;
            line-height: 1.6;
        }
        .producto-detalle form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .producto-detalle button {
            background-color: #ff6f61;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .producto-detalle button:hover {
            background-color: #ff8a80;
        }
        @media screen and (max-width: 768px) {
            .producto-detalle {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Detalle del Producto</h1>
    </header>

    <div class="container">
        <div class="producto-detalle">
            <!-- Mostrar la imagen del producto -->
            <img src="uploads_productos/<?php echo htmlspecialchars($producto['archivo']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">

            <!-- Nombre del producto -->
            <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>

            <!-- Descripción del producto -->
            <div class="descripcion">
                <strong>Descripción:</strong>
                <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
            </div>

            <!-- Si el cliente está logueado, se muestra el formulario para solicitar adopción -->
            <?php if ($cliente_logueado): ?>
                <form action="carrito_agregar.php" method="POST">
                    <input type="hidden" name="producto_id" value="<?php echo intval($producto['id']); ?>">
                    <button type="submit">Solicitar adopcion</button>
                </form>
            <?php else: ?>
                <p>Por favor, inicia sesión para agregar productos al carrito.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
