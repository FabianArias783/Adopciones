<?php
include 'menu.php';
require "funciones/conecta.php"; 
include('validar_sesion.php');
$con = conecta();

$id = $_GET['id'];

// Obtener los datos del empleado
$sql = "SELECT * FROM empleados WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$empleado = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Empleado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        .container {
            width: 50%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        p {
            font-size: 1rem;
        }
        button, a {
            background-color: #333; /* Negro */
            color: white;
            padding: 10px 15px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        button:hover, a:hover {
            background-color: #555; /* Gris oscuro al pasar el mouse */
        }
    </style>
</head>
<body>
    <header>
        <h1>Detalle del Empleado</h1>
    </header>

    <div class="container">
        <p><strong>Nombre:</strong> <?php echo $empleado['nombre']; ?></p>
        <p><strong>Apellidos:</strong> <?php echo $empleado['apellidos']; ?></p>
        <p><strong>Correo:</strong> <?php echo $empleado['correo']; ?></p>
        <p><strong>Rol:</strong> <?php echo $empleado['rol'] == 1 ? 'Gerente' : 'Ejecutivo'; ?></p>
        <?php if ($empleado['archivo']) : ?>
            <p><strong>Foto:</strong><br>
            <img src="uploads/<?php echo $empleado['archivo']; ?>" alt="Foto del empleado" width="150px"></p>
        <?php endif; ?>

        <!-- Botón para regresar al listado -->
        <button onclick="window.location.href='empleados_lista.php'">Regresar al listado</button>
    </div>
</body>
</html>
