<?php
require "funciones/conecta.php";
$con = conecta();
include 'menu.php';
include('validar_sesion.php');
$con = conecta();

$error = ""; // Variable para almacenar el mensaje de error

// Variables para repoblar los campos del formulario en caso de error
$nombre = "";
$codigo = "";
$descripcion = "";
$costo = "";
$stock = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $nombre = $_POST['nombre'];
    $codigo = $_POST['codigo'];
    $descripcion = $_POST['descripcion'];
    $costo = $_POST['costo'];
    $stock = $_POST['stock'];

    // Validar que el código no esté duplicado (activo y no eliminado)
    $sql = "SELECT id FROM productos WHERE codigo = ? AND eliminado = 0";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Error: El código ya está registrado.";
    } else {
        // Procesar la imagen
        $imagen = $_FILES['foto'];
        $imagenNombre = $imagen['name'];
        $imagenTmp = $imagen['tmp_name'];

        // Validar que la imagen no esté vacía
        if (empty($imagenNombre)) {
            $error = "Error: La imagen es obligatoria.";
        } else {
            // Generar nombre encriptado para la imagen
            $imagenEncriptada = uniqid('img_') . '.' . pathinfo($imagenNombre, PATHINFO_EXTENSION);

            // Subir la imagen a la carpeta 'uploads'
            $uploadDir = 'uploads_productos/';
            $uploadFile = $uploadDir . $imagenEncriptada;

            if (move_uploaded_file($imagenTmp, $uploadFile)) {
                // Insertar el producto en la base de datos
                $sql = "INSERT INTO productos (nombre, codigo, descripcion, costo, stock, archivo_n, archivo, eliminado) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("sssdiss", $nombre, $codigo, $descripcion, $costo, $stock, $imagenNombre, $imagenEncriptada);

                if ($stmt->execute()) {
                    header('Location: productos_lista.php'); // Redirigir al listado de productos
                    exit;
                } else {
                    $error = "Error: No se pudo registrar el producto.";
                }
                $stmt->close();
            } else {
                $error = "Error: No se pudo subir la imagen.";
            }
        }
    }

    $stmt->close();
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Producto</title>
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
        .error {
            color: red;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        label {
            font-size: 1rem;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="number"], textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #555;
        }
        a {
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>
    <header>
        <h1>Alta de Producto</h1>
    </header>

    <div class="container">
        <form action="productos_alta.php" method="POST" enctype="multipart/form-data">
            <?php if (!empty($error)): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>

            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required>

            <label>Código:</label>
            <input type="text" name="codigo" value="<?= htmlspecialchars($codigo) ?>" required>

            <label>Descripción:</label>
            <textarea name="descripcion" rows="4" required><?= htmlspecialchars($descripcion) ?></textarea>

            <label>Costo:</label>
            <input type="number" step="0.01" name="costo" value="<?= htmlspecialchars($costo) ?>" required>

            <label>Stock:</label>
            <input type="number" name="stock" value="<?= htmlspecialchars($stock) ?>" required>

            <label>Foto:</label>
            <input type="file" name="foto" accept="image/*" required><br>

            <button type="submit">Agregar Producto</button>
            <button type="button" onclick="window.location.href='productos_lista.php'">Regresar</button>
        </form>
    </div>
</body>
</html>
